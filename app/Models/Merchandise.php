<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Merchandise extends CustomModel
{
    use HasFactory;

    protected $table = 'merchandises';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','rjDealerId','mType','mDate','mTime','mAddress',
        'mStateId','mSName','mCityId','mCName','mTalukaId','mTName',
        'mPinCode','mPhoto','itemNames','orderNumber','mStatusUpdateUserBy',
        'price','totalPrice','cLocation'
    ];

    protected $maps = [
        'sType' => 'sType'
      ];

    protected $append = ['sType'];


    public function getSType()
    {
    return $this->attributes['sType'];
    }


    public function orderProducts()
    {
        return $this->hasMany('App\Models\MerchandisesOrder', 'merchandisesOrderId', 'id');
    }

    public static function getSelectQuery()
    {
        $pathPanImage = config('constant.baseUrlS3') . config('constant.merchandises_image');

        return self::selectRaw('merchandises.id,merchandises.userId,merchandises.rjDealerId,merchandises.mType,merchandises.orderNumber,dealers.fType,
                                dealers.dealerId,dealers.firmName,dealers.name,merchandises.mStatus,merchandises.mAddress,
                                merchandises.mStateId,merchandises.mCityId,merchandises.mTalukaId,merchandises.createdAt,users.fullName,itemQty,totalPriceOrder,itemListShow,itemListShow1,
                                itemNames,users.roleId,roles.roleName,
                                IF(ISNULL(mPhoto) or mPhoto = "", "", CONCAT("'.$pathPanImage.'","/",mPhoto)) as mPhoto,
                                (select GROUP_CONCAT(sType) from schedules where schedules.rjDealerId  =   merchandises.rjDealerId  limit 1) as sType
                                ')
                            ->leftjoin('dealers','dealers.id','merchandises.rjDealerId')
                            ->leftjoin('users','users.id','merchandises.userId')
                            ->leftjoin('roles','roles.id','users.roleId');

                            //  ,schedules.sType ->leftjoin('schedules','schedules.rjDealerId','merchandises.rjDealerId');
    }

    public static function merchandisesDetails()
    {

        // $pathPanImage = url('storage/images/merchandises');
        $pathPanImage = config('constant.baseUrlS3') . config('constant.merchandises_image');

        return  self::getSelectQuery()->selectRaw('merchandises.*,
                DATE_FORMAT(merchandises.mDate, "' . config('constant.schedule_date_format') . '") as mDateFormate,
                DATE_FORMAT(merchandises.mTime, "' . config('constant.schedule_time_format') . '") as mTimeFormate,
                IF(ISNULL(mPhoto) or mPhoto = "", "", CONCAT("'.$pathPanImage.'","/",mPhoto)) as mPhoto,
                mPhoto as shortMPhoto
            ')->with([
                'orderProducts' => function ($query) use($pathPanImage) {

                    $query->selectRaw('merchandises_orders.*,
                    IF(ISNULL(orderAttachment) or orderAttachment = "", "", CONCAT("'.$pathPanImage.'","/",orderAttachment)) as orderAttachment,
                    product_attributes.productOptionName,product_attributes.productId,products.pName,product_attributes.isSubProduct
                    ')
                    ->leftJoin('product_attributes','product_attributes.id','merchandises_orders.productOptionsId')
                    ->leftJoin('products','products.id','product_attributes.productId');
                }
            ]);
    }

    public static function getOrderProductMerchandise($orderProducts)
    {



        $orderProducts = $orderProducts;

        // echo "<pre>";
        // print_r($orderProducts);
        // exit;

        $product = [];

        if(!empty($orderProducts))
        {
            $orderProducts  = collect($orderProducts);

            $orderProducts1  = $orderProducts->groupBy('productId');


            $i = 0;

            foreach($orderProducts1 as $key=>$value)
            {
                $totalSum = $value->sum('orderQty');
                $totalPrice = $value->sum('totalPrice');

                $product[$i]['id'] = $value[0]['productId'];
                $product[$i]['pName'] = $value[0]['pName'];
                $product[$i]['productOptionName'] = $value[0]['productOptionName'];
                $product[$i]['orderDesc'] = $value[0]['orderDesc'] ?? NULL;
                $product[$i]['price'] = $value[0]['price'] ?? 0;
                $product[$i]['totalPrice'] = $totalPrice;
                // $product[$i]['totalPrice'] = $value[0]['totalPrice'] ?? 0;
                // $product[$i]['orderQty'] = $totalSum;
                $product[$i]['totalQty'] = $totalSum;
                $product[$i]['isProductOption'] = $value[0]['isSubProduct'];
                $product[$i]['productOptions'] = $value;
                $i++;
            }
            return $product;
        }

    }

    public static function orderProductCheck($orderProducts,$merchandisesRecord,$insertUpdate = 'insert')
    {

        // echo "<pre>";
        // print_r($orderProducts);

        $orderProducts = collect($orderProducts);
        $orderProducts  = $orderProducts->groupBy('productId');

        // echo "<pre>";
        // print_r($orderProducts->toArray());


        $mtArray = [];
        $productListView = [];
        $totalQty = 0;
        $totalPrice = 0;
        $totalPriceOrder = 0;

        if (!empty($orderProducts)) {
            foreach ($orderProducts as $key => $orderProduct) {

                // echo "<pre>";
                // print_r($orderProduct->toArray());
                // exit;

                $mainProductName = ProductAttribute::ProductAvailabilityCheck(['product_attributes.productId' => $key],$merchandisesRecord);

                if (empty($mainProductName)) {
                    return ['status' => 0,'msg' => trans('api.product.not_found')];
                }

                $mainProductName1 = $mainProductName['pName'];
                $mainProductIsSubProduct =  $mainProductName['isSubProduct'];

                $totalSum = 'qty: ' . $orderProduct->sum('orderQty');


                if($mainProductName['isSubProduct'] == 1 && ($merchandisesRecord->mType == "Order" || $merchandisesRecord->mType == "order" ))
                {
                    foreach ($orderProduct as $orderPro) {

                      $mainProductName2 = ProductAttribute::ProductAvailabilityCheck(['product_attributes.productId' => $orderPro['productId'],'product_attributes.id' => $orderPro['id']],$merchandisesRecord);

                      $totalPriceOrder +=  $mainProductName2['totalQty'] * $orderPro['orderQty'];
                    }

                    $totalPriceOrder = ' TotalPrice : ₹ ' . $totalPriceOrder;
                }

                if(($merchandisesRecord->mType == "Gift" || $merchandisesRecord->mType == "gift"))
                {
                    $titleKey = ($mainProductName['isSubProduct'] == 0) ?  $mainProductName1 : "{$mainProductName1}  | {$totalSum} (";
                }else{
                    $titleKey = ($mainProductName['isSubProduct'] == 0) ?  $mainProductName1 : "{$mainProductName1}  | {$totalSum} {$totalPriceOrder} (";
                }


                // echo "<pre>";
                // print_r($orderProduct);
                // exit;


                foreach ($orderProduct as $orderPro) {

                    $productOptionsId = $orderPro['id'];
                    $orderQty = $orderPro['orderQty'];

                    $totalOrderMainProduct = $orderPro['orderQty']  * $mainProductName['totalQty'];


                    //   if($insertUpdate == "update")
                    //     {
                    //       $getOldQty = MerchandisesOrder::where(['productOptionsId' => $productOptionsId,'merchandisesOrderId' => $merchandisesRecord->id])->first();

                    //       $oldQty = 0;

                    //       if(!empty($getOldQty))
                    //       {
                    //         $oldQty = $getOldQty->orderQty;
                    //         $query =  ProductAttribute::where(['id' => $productOptionsId])->increment('totalQty',$oldQty);
                    //       }
                    //     }

                    $productCheck = ProductAttribute::ProductAvailabilityCheck(['product_attributes.id' => $productOptionsId],$merchandisesRecord);

                    if (empty($productCheck)) {


                        // if($insertUpdate == "update")
                        // {
                        //     if(!empty($getOldQty))
                        //     {
                        //       $oldQty = $getOldQty->orderQty;
                        //       $query =  ProductAttribute::where(['id' => $productOptionsId])->decrement('totalQty',$oldQty);
                        //     }
                        // }
                        return ['status' => 0,'msg' => trans('api.product.not_found')];
                    }

                    $productQty = $productCheck->totalQty;

                    if ( ($merchandisesRecord->mType == "Gift" || $merchandisesRecord->mType == "gift") &&  $productQty < $orderQty) {

                        // if(!empty($getOldQty))
                        // {
                        //   $oldQty = $getOldQty->orderQty;
                        //   $query =  ProductAttribute::where(['id' => $productOptionsId])->decrement('totalQty',$oldQty);
                        // }

                        $nameFlag = !empty($productCheck->productOptionName) ?  $productCheck['productOptionName'] : $productCheck['pName'];
                        return ['status' => 0,'msg' => trans('api.product.qty_not_available', ['qty' => $nameFlag])];
                    }

                    $productName = $productCheck->pName;
                    $productNameAttributes = $productCheck->productOptionName;

                    if ($mainProductIsSubProduct == 0) {

                        if(($merchandisesRecord->mType == "Gift" || $merchandisesRecord->mType == "gift"))
                        {
                            $titleKey .=  " | qty: {$orderQty} ";
                        }else{
                            $titleKey .=  " | qty: {$orderQty}  TotalPrice : ₹ {$totalOrderMainProduct}";
                        }
                    } else {
                        // $titleKey .=  "{$orderQty}-{$productNameAttributes},";
                        $titleKey .=  "{$productNameAttributes}-{$orderQty},";
                    }

                    $totalPriceQty = ($orderQty * $productQty);

                    $mtArray[] = [
                        'id'         => \Str::uuid(),
                        'userId' => $merchandisesRecord->userId,
                        'moType' => $merchandisesRecord->mType,
                        'merchandisesOrderId' => $merchandisesRecord->id,
                        'productOptionsId'     => $productOptionsId,
                        'orderQty' => $orderQty,
                        'totalQty' => $orderQty,
                        'price' => $productQty,
                        'totalPrice' =>  $totalPriceQty,
                        'isDescription' => $productCheck->isDescription,
                        'orderDesc'   => $orderPro['orderDesc'] ?? NULl,
                        'orderAttachment' => $orderPro['orderAttachment'] ?? NULL
                    ];
                        $totalQty += $orderQty;
                        $totalPrice +=  $totalPriceQty;
                }

                $titleKey = ($mainProductIsSubProduct == 1) ? substr_replace($titleKey, "", -1)  : $titleKey;
                $titleKey = ($mainProductIsSubProduct == 1) ? $titleKey . ')'  : $titleKey;

                $productListView[]  = $titleKey;

                $titleKey = '';
            }

            $title1 = implode("\n ",$productListView);
        }

        MerchandisesOrder::where(['merchandisesOrderId' => $merchandisesRecord->id])->delete();

        MerchandisesOrder::insert($mtArray);
        $merchandisesRecord->itemListShow = json_encode($productListView);
        $merchandisesRecord->itemListShow1 = $title1;
        $merchandisesRecord->itemQty = json_encode($totalQty);
        $merchandisesRecord->totalPriceOrder = json_encode($totalPrice);

        if(($merchandisesRecord->mType == "Gift" || $merchandisesRecord->mType == "gift"))
        {
            $merchandisesRecord->mStatus = 'Approved';
        }

        $merchandisesRecord->save();
        \DB::commit();

        return ['status' => 1];
    }
}
