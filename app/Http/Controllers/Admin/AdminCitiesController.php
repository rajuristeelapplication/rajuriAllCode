<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Cities;
use App\Models\Talukas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class AdminCitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $stateNameList = State::selectRaw('id,sName')
        //                 ->orderByRaw('sName asc')
        //                 ->get();


        $stateNameList = Cities::selectRaw('states.id,states.sName')
                        ->join('states','states.id','cities.stateId')
                        ->distinct('cities.stateId')
                        ->orderByRaw('sName ASC')
                        ->get();


        return view('admin.cities.city_list',compact('stateNameList'));
    }

      /**
     * Search OR Sorting Cities (DataTable).
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

            $query = Cities::getState();

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);


            if(!empty($request->stateId)){
                $query = $query->where('cities.stateId','=',$request->stateId);
            }


            $query->where(function ($query) use ($request) {
                $query->orWhere('cName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('sName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'city' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                // $viewRoute   = route('cities.show', $params);
                $editRoute   = route('cities.edit', $params);
                $deleteRoute = route('cities.destroy', $params);
                $statusRoute = route('cities.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['sname'] = $row['cName']??"-";
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                // $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Cities Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
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
        $cityInfo = Cities::find($id);
        $states    = State::where('isActive',1)->get();
        if (!empty($cityInfo)) {


          return view('admin.cities.city_view',compact('cityInfo','states'));
        }

        return redirect(route('cities.index'))->with('error', trans('messages.city.not_found', ['module' => 'city']));
    }


    /**
     * Change status of the cities.
     *
     * @param  string  $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $city = Cities::find($id);

        if (empty($city)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'city']), 0);
        }

        $city->isActive = !$city->isActive;
        $status = '';
        if ($city->save()) {
            $status = $city->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'city', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'city', 'type' => $status]), 0);
    }

   /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        $states = State::where('isActive',1)->get();
        return view('admin.cities.city_create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->id) {
            $message = trans('messages.msg.updated.success', ['module' => 'city']);
            $action = 'Edit';

            $city = Cities::find($request->id);

        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'city']);

            $city = new Cities();
        }
        $city->cName = $request->cName;
        $city->stateId = $request->sName;

        if ($city->save()) {

            return redirect()->route('cities.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('cities.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'city']), 0);
    }

    /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $cityDetail = Cities::where('id', $id)->first();
        $states    = State::where('isActive', 1)->get();
        return view('admin.cities.city_create', compact('cityDetail', 'states'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $city = Cities::where('id', $id)->first();

        if ($city) {

            $city->delete();

            Talukas::where('cityId',$id)->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'city']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'city']), 0);
    }
}
