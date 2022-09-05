<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.products.list');
    }

     /**
     * Search OR Sorting Product (DataTable).
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
                    'product' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('products.show', $params);
                $editRoute   = route('products.edit', $params);
                $deleteRoute = route('products.destroy', $params);
                $statusRoute = route('products.status', $params);
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
        return view('admin.products.create');
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

            return view('admin.products.view', compact('productInfo', 'productAttributeInfo'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.product.not_found', ['module' => 'product']));
    }


     /**
     * Change status of the Product.
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

        if ($request->radio == 0 && isset($request->pName) && isset($request->totalQtySingle)) {

            $save = Product::updateOrCreate(['id' => $request->id], ['pName'=> $request->pName, 'pType'=> $request->pType]);

            if($request->totalQtySingle != 0 &&  !empty($request->productOptionName))
            {
                ProductAttribute::where(['productId' => $save->id])->delete();
            }

            ProductAttribute::updateOrCreate(['productId' => $save->id], ['productId'=> $save->id,'totalQty' => $request->totalQtySingle,'isDescription' => !empty($request->isDescription) ? 1 :0]);
        }else{
            // dd($request->all());
            $save = Product::updateOrCreate(['id' => $request->id], ['pName'=> $request->pName, 'pType'=> $request->pType]);

            if($request->totalQtySingle != 0 &&  !empty($request->productOptionName))
            {
                ProductAttribute::where(['productId' => $save->id])->delete();
            }
            //First Delete all head or add again

            if (isset($request->removeProductAttribute)) {
                $getProductAttributeId =  explode(',', $request->removeProductAttribute);
                ProductAttribute::whereIn('id', $getProductAttributeId)->delete();
            }

            $productOptionName =  $request->productOptionName;
            $totalQty =  $request->totalQty;
            $isDescription =  $request->isDescription;
            $isAttachment =  $request->isAttachment;

            foreach ($productOptionName as $key => $value) {
                $data = [
                    'productId' => $save->id,
                    'productOptionName' => $value,
                    'totalQty' => $totalQty[$key],
                    'isDescription' => isset($isDescription[$key]) ? 1 :0,
                    'isAttachment' => isset($isAttachment[$key]) ? 1 :0,
                    'isSubProduct'=>1
                ];
                $prId= ProductAttribute::updateOrCreate(['id' => $key], $data);

            }
        }

        if ($save) {
            return redirect()->route('products.index')->with('success', $message);
        }

        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('products.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'product']), 0);
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
            return view('admin.products.list');
        }

        return view('admin.products.create', compact('productDetail', 'productAttributeDetail'));
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
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'product']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'product']), 0);
    }
}
