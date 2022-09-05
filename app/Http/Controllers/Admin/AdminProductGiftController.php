<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\UserProductQty;
use App\Models\ProductAttribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;


class AdminProductGiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.product-gift.list');
    }

     /**
     * Search OR Sorting Product Gift (DataTable).
     *
     * @param Request $request
     *
     * @return json
     */

    public function search(Request $request)
    {

        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = Product::selectRaw('id, pName, pType, isActive, createdAt');


            if(!empty($request->pType)){
                $query = $query->where('pType','=',$request->pType);
            }


            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('pName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('pType', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'products_gift' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('products-gift.show', $params);
                $editRoute   = route('products-gift.edit', $params);
                $deleteRoute = route('products-gift.destroy', $params);
                $statusRoute = route('products-gift.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['pName'] = Str::limit($row['pName'], 100, $end = '...') ?? "-";
                $rows['data'][$key]['pType'] = $row['pType'] ?? "-";
                $rows['data'][$key]['createdAt'] = $row['createdAt'] ?? "-";
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Department Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.product-gift.create');
    }

     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productInfo = Product::where('id', $id)->first();
        $productAttributeInfo = ProductAttribute::where(['productId' => $id])->get();

        if (!empty($productInfo)) {

            return view('admin.product-gift.view', compact('productInfo', 'productAttributeInfo'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.product.not_found', ['module' => 'product']));
    }


    /**
     * Change status of the Product Gift.
     *
     * @param string $id
     *
     * @return json
     */

    public function changeStatus($id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'product']), 0);
        }

        $product->isActive = !$product->isActive;
        $status = '';
        if ($product->save()) {
            $status = $product->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'product', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'product', 'type' => $status]), 0);
    }

    /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->id) {
            $message = trans('messages.msg.updated.success', ['module' => 'product']);
            $action = 'Edit';
        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'product']);
        }

        if ($request->radio == 0 && isset($request->pName)) {

            $save = Product::updateOrCreate(['id' => $request->id], ['pName'=> $request->pName, 'pType'=> $request->pType]);

            $productOptionCheck = array_values($request->productOptionName);

            if(!empty($productOptionCheck[0]))
            {
                ProductAttribute::where(['productId' => $save->id])->delete();
                UserProductQty::where(['productId' => $save->id])->delete();
            }

            ProductAttribute::updateOrCreate(['productId' => $save->id], ['productId'=> $save->id,'totalQty' => 0,'isDescription' => !empty($request->isDescription) ? 1 :0]);
        }else{

            $save = Product::updateOrCreate(['id' => $request->id], ['pName'=> $request->pName, 'pType'=> $request->pType]);

            // if(!empty($request->productOptionName))
            // {
            //     ProductAttribute::where(['productId' => $save->id])->delete();
            // }

            //First Delete all head or add again

            if (isset($request->removeProductAttribute)) {
                $getProductAttributeId =  explode(',', $request->removeProductAttribute);
                ProductAttribute::whereIn('id', $getProductAttributeId)->delete();
                UserProductQty::whereIn('productAttributesId', $getProductAttributeId)->delete();
            }

            $productOptionName =  $request->productOptionName;
            $isDescription =  $request->isDescription;

            foreach ($productOptionName as $key => $value) {
                $data = [
                    'productId' => $save->id,
                    'productOptionName' => $value,
                    'isDescription' => isset($isDescription[$key]) ? 1 :0,
                    'isSubProduct'=>1
                ];
                $prId= ProductAttribute::updateOrCreate(['id' => $key], $data);
            }
        }

        if ($save) {
            return redirect()->route('products-gift.index')->with('success', $message);
        }

        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('products-gift.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'product']), 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $productDetail = Product::where('id', $id)->first();
        $productAttributeDetail = ProductAttribute::where(['productId' => $id])->get();

        if(empty($productDetail)){
            return redirect()->route('products-gift.index')->with('error', 'Product Not Found');
        }

        return view('admin.product-gift.create', compact('productDetail', 'productAttributeDetail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */


    public function destroy($id)
    {
        $product = Product::where('id', $id)->first();
        if ($product) {
            $product->delete();
            UserProductQty::where('productId',$id)->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'product']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'product']), 0);
    }


    /**
     * product Gift Allocation Specific User List
     *
     * @param  mixed $request
     *
     * @return view
     */
    public function productGiftAllocationUser(Request $request)
    {
        $data['userDetail'] = User::selectRaw('id,fullName')
                            ->where(['userStatus' => 'Approved'])
                            ->whereIn('roleId',User::whichUserLogin())
                            ->get();



        $userId = $request->userId ?? NULL;

        $data['userId'] = $userId;

        $type = 'gift';

        $products =  Product::getSelectQuery('',$userId,'allocationUser')->where('pType',$type);
        $products = $products->orderBy('products.createdAt', 'desc');
        $data['getProducts']  = $products->get();

        return view('admin.product-gift.allocation_user',$data);
    }

    /**
     * product Gift Allocation User Gift Assign Qty
     *
     * @param  Request $request
     *
     * @return Redirect
     */
    public  function productGiftAllocationUserStore(Request $request)
    {
        $productAllocations =  $request->productAllocation;

        $opArray = [];


        if(!empty($productAllocations))
        {
            foreach($productAllocations as $key => $productAllocation)
            {
                    $productAttributesCheck = ProductAttribute::where(['id' => $key])->first();

                    if(!empty($productAttributesCheck))
                    {
                        $opArray = [
                            'userId' => $request->userId,
                            'productAttributesId' => $productAttributesCheck->id,
                            'productId'     => $productAttributesCheck->productId,
                            'totalQty' => $productAllocation,
                        ];

                        $prId= UserProductQty::updateOrCreate(['productAttributesId' => $key,'userId' =>$request->userId], $opArray);
                    }
            }
        }

        return redirect()->route('products-gift.index')->with('success', trans('messages.msg.updated.success', ['module' => 'Allocation Product User']));


    }
}
