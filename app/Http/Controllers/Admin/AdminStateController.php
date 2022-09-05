<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Cities;
use App\Models\Talukas;
use App\Models\Regions;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminStateController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.states.state_list');
    }


    /**
     * Search OR Sorting State (DataTable).
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

            $query = State::getSelectQuery();

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('sName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'state' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $editRoute   = route('states.edit', $params);
                $deleteRoute = route('states.destroy', $params);
                $statusRoute = route('states.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['sname'] = $row['sName']??"-";
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
        $stateInfo = State::find($id);
        if (!empty($stateInfo)) {


          return view('admin.states.state_view',compact('stateInfo'));
        }

        return redirect(route('states.index'))->with('error', trans('messages.state.not_found', ['module' => 'state']));
    }

    /**
     * Change status of the State.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $state = State::find($id);

        if (empty($state)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'state']), 0);
        }

        $state->isActive = !$state->isActive;
        $status = '';
        if ($state->save()) {
            $status = $state->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'state', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'state', 'type' => $status]), 0);
    }

     /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.states.state_create');
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
            $message = trans('messages.msg.updated.success', ['module' => 'state']);
            $action = 'Edit';

            $state = State::find($request->id);

        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'state']);

            $state = new State();
        }

        $state->sName = $request->sName;

        if ($state->save()) {
            return redirect()->route('states.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('states.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'state']), 0);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $stateDetail = State::where('id', $id)->first();
        return view('admin.states.state_create', compact('stateDetail'));
    }

       /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $state = State::where('id', $id)->first();

        if ($state) {

            $state->delete();

            $city = Cities::where('stateId', $id)->delete();
            $taluka = Talukas::where('stateId', $id)->delete();
            $region = Regions::where('stateId', $id)->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'state']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'state']), 0);
    }
}
