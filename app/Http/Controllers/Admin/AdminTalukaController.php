<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Cities;
use App\Models\Talukas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class AdminTalukaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $stateNameList = State::selectRaw('states.id,states.sName')
                    // ->join('states','states.id','talukas.stateId')
                    // ->distinct('talukas.stateId')
                    ->orderByRaw('sName ASC')
                    ->get();

        // $stateNameList = Talukas::selectRaw('states.id,states.sName')
        //             ->join('states','states.id','talukas.stateId')
        //             ->distinct('talukas.stateId')
        //             ->orderByRaw('sName ASC')
        //             ->get();

        $cityNameList = Talukas::selectRaw('cities.id,cities.cName')
                        ->join('cities','cities.id','talukas.cityId')
                        ->distinct('cities.id')
                        ->orderByRaw('cName ASC')
                        ->get();

        return view('admin.talukas.taluka_list',compact('stateNameList','cityNameList'));
    }

    /**
     * Search OR Sorting Taluka (DataTable).
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

            $query = Talukas::getStateCity();

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);



            if(!empty($request->stateId)){
                $query = $query->where('talukas.stateId','=',$request->stateId);
            }

            if(!empty($request->cityId)){
                $query = $query->where('talukas.cityId','=',$request->cityId);
            }


            $query->where(function ($query) use ($request) {
                $query->orWhere('cName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('sName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('tName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'taluka' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('talukas.show', $params);
                $editRoute   = route('talukas.edit', $params);
                $deleteRoute = route('talukas.destroy', $params);
                $statusRoute = route('talukas.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['sname'] = $row['sName']??"-";
                $rows['data'][$key]['cname'] = $row['cName']??"-";
                $rows['data'][$key]['tname'] = $row['tName']??"-";
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
        $talukaInfo = Talukas::find($id);
        $states    = State::where('isActive',1)->get();
        $city    = Cities::where('isActive',1)->get();
        if (!empty($talukaInfo)) {


          return view('admin.talukas.taluka_view',compact('talukaInfo','states', 'city'));
        }

        return redirect(route('talukas.index'))->with('error', trans('messages.taluka.not_found', ['module' => 'taluka']));
    }


    /**
     * Change status of the taluka.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $taluka = Talukas::find($id);

        if (empty($taluka)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'taluka']), 0);
        }

        $taluka->isActive = !$taluka->isActive;
        $status = '';
        if ($taluka->save()) {
            $status = $taluka->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'taluka', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'taluka', 'type' => $status]), 0);
    }

  /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        $states = State::where('isActive',1)->get();
        $city = Cities::where('isActive',1)->get();
        return view('admin.talukas.taluka_create', compact('states', 'city'));
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
            $message = trans('messages.msg.updated.success', ['module' => 'taluka']);
            $action = 'Edit';

            $taluka = Talukas::find($request->id);

        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'taluka']);

            $taluka = new Talukas();
        }

        $taluka->tName = $request->tName;
        $taluka->stateId = $request->sName;
        $taluka->cityId = $request->cName;

        if ($taluka->save()) {

            return redirect()->route('talukas.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('talukas.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'taluka']), 0);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $talukaDetail = Talukas::where('id', $id)->first();
        $states    = State::where('isActive', 1)->get();
        $city    = Cities::where('isActive', 1)->get();
        $city    = Cities::where('isActive', 1)->get();
        return view('admin.talukas.taluka_create', compact('talukaDetail', 'states', 'city'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $taluka = Talukas::where('id', $id)->first();

        if ($taluka) {

            $taluka->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'taluka']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'taluka']), 0);
    }

    /**
     * Get City.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getCity(Request $request)
    {

        if(!empty($request->page))
        {
            $data['city'] = Cities::
            // join('talukas','talukas.cityId','cities.id')
            where('cities.stateId',$request->s_name)
            ->where('cities.isActive',1)
            ->orderBy('cName','asc')
            ->groupBy('cities.id')
            ->get(["cities.cName","cities.id"]);
        }else{


            $data['city'] = Cities::join('talukas','talukas.cityId','cities.id')
            ->where('cities.stateId',$request->s_name)
            ->where('cities.isActive',1)
            ->orderBy('cName','asc')
            ->groupBy('cities.id')
            ->get(["cities.cName","cities.id"]);

            // echo "<pre>";
            // print_r($data['city']->toArray());
            // exit;
        }


        return response()->json($data);
    }

     /**
     * Get Taluka.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getTaluka(Request $request)
    {
        $data['taluka'] = Talukas::where('cityId',$request->cityId)->where('isActive',1)->orderBy('tName','asc')->get(["tName","id"]);

        return response()->json($data);
    }



}
