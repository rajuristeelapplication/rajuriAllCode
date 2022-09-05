<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Merchandise;
use App\Models\ProductAttribute;
use App\Models\MerchandisesOrder;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MerchandisesValidation;

class MerchandisesController extends Controller
{
    /**
     *  Get Products
     *
     * @param string pType
     *
     * @return json
     */
    public function getProducts(Request $request)
    {

        $productPagination = config('constant.productPagination');

        $this->validate($request, [
            'pType' => 'required|in:Gift,Order',
        ]);

        $user = \Auth::user();

        $userId = $user->id;
        $pType = $request->pType;

        $getProducts = Product::getSelectQuery($pType,$userId)->where('pType',$request->pType);

        if($pType == "Gift")
        {
            $getProducts = $getProducts->having('isGift','>',0);
        }

        if($pType == "Order")
        {
            $getProducts = $getProducts->having('isShowRecord',1);
        }

        $getProducts = $getProducts->orderBy('products.createdAt', 'desc')
            ->paginate($productPagination);

        return $this->toJson([
            'hasMore' => $getProducts->hasMorePages(),
            'totalCount' => $getProducts->total(),
            'getProducts' => $getProducts->items(),
        ]);
    }

    /**
     * Create Merchandises Order
     *
     * @param MerchandisesValidation $request
     *
     * @return json
     */

    public function addMerchandises(MerchandisesValidation $request)
    {

        $orderProducts =  $request->productOptions;

        $user       = \Auth::user();
        $userId     =  $user->id;


        \DB::beginTransaction();

        $typeMsg = 'Create';

        $orderNumber = UtilityHelper::generateUniqueCode('merchandises', 'orderNumber');

        $merchandisesRecord = Merchandise::create($request->merge(['userId' => $userId, 'orderNumber' => $orderNumber])->all());

        $orderProducts =  $request->productOptions;


        $result =  Merchandise::orderProductCheck($orderProducts,$merchandisesRecord);

        if($result['status'] == 0)
        {
            return $this->toJson([], $result['msg'], 0);
        }

        if($merchandisesRecord->mType == "Order")
        {
            NotificationHelper::orderPlace($merchandisesRecord,1);
        }

        return $this->toJson([
            'merchandisesRecord' => $merchandisesRecord,
        ], trans('api.merchandises.success', ['type' => $typeMsg]));
    }


    /**
     * product Qty Available Yes or no Checking
     *
     * @param  mixed $orderProducts  => Request Front Side Array
     * @param  mixed $merchandisesRecord
     * @return array
     */
    public function orderProductCheck($orderProducts,$merchandisesRecord)
    {

        // $orderProducts =  $request->orderProduct;

        $orderProducts = collect($orderProducts);

        $orderProducts  = $orderProducts->groupBy('productId');

        $mtArray = [];
        $productListView = [];

        if (!empty($orderProducts)) {
            foreach ($orderProducts as $key => $orderProduct) {
                $mainProductName = ProductAttribute::ProductAvailabilityCheck(['product_attributes.productId' => $key]);

                if (empty($mainProductName)) {
                    return ['status' => 0,'msg' => trans('api.product.not_found')];
                }


                $mainProductName1 = $mainProductName['pName'];
                $mainProductIsSubProduct =  $mainProductName['isSubProduct'];

                $totalSum = $orderProduct->sum('orderQty');

                $titleKey = ($mainProductName['isSubProduct'] == 0) ?  $mainProductName1 : "{$mainProductName1}  | {$totalSum}(";

                foreach ($orderProduct as $orderPro) {

                    $productOptionsId = $orderPro['id'];
                    $orderQty = $orderPro['orderQty'];

                    $productCheck = ProductAttribute::ProductAvailabilityCheck(['product_attributes.id' => $productOptionsId]);

                    if (empty($productCheck)) {
                        return ['status' => 0,'msg' => trans('api.product.not_found')];
                    }

                    $productQty = $productCheck->totalQty;

                    if ($productQty < $orderQty) {
                        $nameFlag = !empty($productCheck->productOptionName) ?  $productCheck['productOptionName'] : $productCheck['pName'];
                        return ['status' => 0,'msg' => trans('api.product.qty_not_available', ['qty' => $nameFlag])];
                    }
                    $productName = $productCheck->pName;
                    $productNameAttributes = $productCheck->productOptionName;

                    if ($mainProductIsSubProduct == 0) {
                        $titleKey .=  " | {$orderQty} piece";
                    } else {
                        $titleKey .=  "{$orderQty}-{$productNameAttributes},";
                    }

                    $mtArray[] = [
                        'id'         => \Str::uuid(),
                        'merchandisesOrderId' => $merchandisesRecord->id,
                        'productOptionsId'     => $productOptionsId,
                        'orderQty' => $orderQty,
                        'totalQty' => $orderQty,
                        'isDescription' => $productCheck->isDescription,
                        'orderDesc'   => $orderPro['orderDesc'] ?? NULl,
                        'orderAttachment' => $orderPro['orderAttachment'] ?? NULL
                    ];
                }

                $titleKey = ($mainProductIsSubProduct == 1) ? substr_replace($titleKey, "", -1)  : $titleKey;
                $titleKey = ($mainProductIsSubProduct == 1) ? $titleKey . ')'  : $titleKey;

                $productListView[]  = $titleKey;

                $titleKey = '';
            }
        }

        MerchandisesOrder::insert($mtArray);

        $merchandisesRecord->itemListShow = json_encode($productListView);
        $merchandisesRecord->save();

        \DB::commit();

        return ['status' => 1];

    }

    /**
     *  Get Merchandises List
     *
     * (Required Parameters)
     *
     * @param string $mType
     * @param string $sort
     *
     * (Optional Parameters)
     *
     * @param string $search
     * @param string $stateId
     * @param string $cityId
     * @param string $vType
     * @param string $fType
     *
     * @return json
     */
    public function getMerchandises(Request $request)
    {
        $this->validate($request, [
            'mType' => 'required|in:Gift,Order',

        ]);

        if(!empty($request->sort))
        {
            $this->validate($request, [
                'sort' => 'required|in:asc,desc'
            ]);
        }

        $merchandisesPagination = config('constant.merchandisesPagination');

        $user = \Auth::user();

        $search = $request->search;
        $stateId = $request->stateId ?? '';
        $cityId = $request->cityId ?? '';
        $talukaId = $request->talukaId ?? '';
        $sort = $request->sort ?? '';
        $vType = $request->vType ?? '';
        $fType = isset($request->fType) ? $request->fType  : [];

        try {

        $getMerchandises = Merchandise::getSelectQuery()
            ->when(!empty($request->mType), function ($query) use ($request) {
                $query->where(['merchandises.mType' => $request->mType]);
            })
            ->when(!empty($stateId), function ($query) use ($request) {
                return $query->whereIn('merchandises.mStateId',  $request->stateId);
            })
            ->when(!empty($cityId), function ($query) use ($request) {
                return $query->whereIn('merchandises.mCityId',  $request->cityId);
            })
            ->when(!empty($talukaId), function ($query) use ($request) {
                return $query->whereIn('merchandises.mTalukaId',  $request->talukaId);
            })
            ->when(!empty($fType), function ($query) use ($request) {
                return $query->whereIn('dealers.fType',  $request->fType);
            })

            ->when(!empty($search), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->where('merchandises.orderNumber', "like", "%" . $request->search . "%")
                    ->orWhere('dealers.fType', "like", "%" . $request->search . "%")
                    ->orWhere('dealers.name', "like", "%" . $request->search . "%");
                });
            })

              ->when(!empty($vType), function ($query) use ($request) {
                return $query->having('sType', "like", "%" . $request->vType . "%");
                // return $query->having('sType', $request->vType );
            })

            ->when(!empty($sort), function ($query) use ($sort) {
                    return $query->orderBy('dealers.name',  $sort);
            })

            ->where(['merchandises.userId' => $user->id])
            ->orderBy('merchandises.createdAt', 'desc')
            ->paginate($merchandisesPagination);
        }
        catch(\Exception $e) {
          return $this->toJson([], $e->getMessage(), 0);
        }

        return $this->toJson([
            'hasMore' => $getMerchandises->hasMorePages(),
            'totalCount' => $getMerchandises->total(),
            'getMerchandises' => $getMerchandises->items(),
        ]);
    }

    /**
     *  Merchandises Details
     *
     * @param string $id
     *
     * @return json
     */

    public function merchandisesDetails(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $user = \Auth::user();

        $id = $request->id ?? '';
        $merchandisesDetails =  Merchandise::merchandisesDetails()->where(['merchandises.id' => $id, 'merchandises.userId' => $user->id])->first();

        if (empty($merchandisesDetails)) {
            return $this->toJson([], trans('api.merchandises.not_found'), 0);
        }

        $orderProducts = Merchandise::getOrderProductMerchandise($merchandisesDetails->orderProducts);

        if(!empty($orderProducts))
        {
            unset($merchandisesDetails->orderProducts);
            $merchandisesDetails->getProducts = $orderProducts;
        }

        return $this->toJson([
            'merchandisesDetails' => $merchandisesDetails,
        ]);
    }

}
