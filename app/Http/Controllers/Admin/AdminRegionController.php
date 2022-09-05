<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Regions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class AdminRegionController extends Controller
{

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.regions.region_list');
    }


     /**
     * Search OR Sorting Region (DataTable).
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

            $query = Regions::getStateTaluka();

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('sName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('rName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'region' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('regions.show', $params);
                $editRoute   = route('regions.edit', $params);
                $deleteRoute = route('regions.destroy', $params);
                $statusRoute = route('regions.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['sname'] = $row['sName']??"-";
                $rows['data'][$key]['rname'] = $row['rName']??"-";
                $rows['data'][$key]['createdAt'] = $row['createdAt']??"-";
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action'] = '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $regionInfo = Regions::find($id);
        $states    = State::where('isActive',1)->get();

        if (!empty($regionInfo)) {
          return view('admin.regions.region_view',compact('regionInfo','states'));
        }

        return redirect(route('regions.index'))->with('error', trans('messages.region.not_found', ['module' => 'region']));
    }


    /**
     * Change status of the  Region.
     *
     * @param string $id
     *
     * @return json
     */

    public function changeStatus($id)
    {
        $region = Regions::find($id);

        if (empty($region)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'region']), 0);
        }

        $region->isActive = !$region->isActive;
        $status = '';
        if ($region->save()) {
            $status = $region->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'region', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'region', 'type' => $status]), 0);
    }

     /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        $states = State::where('isActive',1)->get();

        return view('admin.regions.region_create', compact('states'));
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
            $message = trans('messages.msg.updated.success', ['module' => 'region']);
            $action = 'Edit';

            $region = Regions::find($request->id);

        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'region']);

            $region = new Regions();
        }

        $region->rName = $request->rName;
        $region->stateId = $request->sName;

        if ($region->save()) {

            return redirect()->route('regions.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('regions.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'region']), 0);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $regionDetail = Regions::where('id', $id)->first();
        $states    = State::where('isActive', 1)->get();

        return view('admin.regions.region_create', compact('regionDetail', 'states'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $region = Regions::where('id', $id)->first();

        if ($region) {

            $region->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'region']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'region']), 0);
    }


}
