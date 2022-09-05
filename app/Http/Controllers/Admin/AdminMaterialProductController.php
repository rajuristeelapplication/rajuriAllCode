<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\MaterialType;
use App\Models\materialSubType;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminMaterialProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.material_product.list');
    }

    /**
     * Search OR Sorting Material Product (DataTable).
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

            $query = MaterialType::selectRaw('*');

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('mName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'material_product' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $editRoute   = route('material-products.edit', $params);
                $deleteRoute = route('material-products.destroy', $params);
                $statusRoute = route('material-products.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action'] = '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }

    /**
     * Change status of the Material Product.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $companyProfile = MaterialType::find($id);

        if (empty($companyProfile)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'Material Products']), 0);
        }

        $companyProfile->isActive = !$companyProfile->isActive;
        $status = '';
        if ($companyProfile->save()) {
            $status = $companyProfile->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'Material Products', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'Material Products', 'type' => $status]), 0);
    }

     /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.material_product.create');
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
            $message = trans('messages.msg.updated.success', ['module' => 'Material Products']);
            $action = 'Edit';
            $companyProfile = MaterialType::find($request->id);

            $checkSubProduct = $companyProfile->isSubOption;
        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'Material Products']);

            $companyProfile = new MaterialType();
        }

        $companyProfile->mName = $request->mName;
        $companyProfile->isSubOption = $request->isSubOption;

        if ($companyProfile->save()) {

            if($action == 'Add' && $companyProfile->isSubOption == 1)
            {
                $twoSubTypes = ['Bend','Straight'];
                $mtValuesNames = ['6mm','8mm','12mn','16mm','20mm','25mm','28mm','32mm','40mm'];

                foreach($twoSubTypes as $key=>$twoSubType)
                {
                    foreach($mtValuesNames as $key1=>$mtValuesName)
                    {
                        materialSubType::updateOrCreate(
                            ['materialTypeId' => $companyProfile->id,'msType'=>$twoSubType,'msName' => $mtValuesName],
                            ['materialTypeId' => $companyProfile->id,'msType'=>$twoSubType,'msName' => $mtValuesName,'orderByKey'=>$key1]
                        );
                    }
                }
            }

            if($action == 'Edit' && $checkSubProduct != $companyProfile->isSubOption)
            {
                if($companyProfile->isSubOption == 1)
                {
                    $twoSubTypes = ['Bend','Straight'];
                    $mtValuesNames = ['6mm','8mm','12mn','16mm','20mm','25mm','28mm','32mm','40mm'];

                    foreach($twoSubTypes as $key=>$twoSubType)
                    {
                        foreach($mtValuesNames as $key1=>$mtValuesName)
                        {
                            materialSubType::updateOrCreate(
                                ['materialTypeId' => $companyProfile->id,'msType'=>$twoSubType,'msName' => $mtValuesName],
                                ['materialTypeId' => $companyProfile->id,'msType'=>$twoSubType,'msName' => $mtValuesName,'orderByKey'=>$key1]
                            );
                        }
                    }
                }

                if($companyProfile->isSubOption == 0)
                {
                    materialSubType::where('materialTypeId',$companyProfile->id)->delete();
                }
            }

            return redirect()->route('material-products.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('material-products.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'Material Products']), 0);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companyProfileDetail = MaterialType::where('id', $id)->first();

        if (empty($companyProfileDetail)) {
            return redirect()->route('material-products.index')->with('success',  trans('messages.msg.not_found', ['module' => 'Material Products']));
        }

        return view('admin.material_product.create', compact('companyProfileDetail'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */
    public function destroy($id)
    {
        $companyProfile = MaterialType::where('id', $id)->first();
        if ($companyProfile) {
            $companyProfile->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'Material Products']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'Material Products']), 0);
    }
}
