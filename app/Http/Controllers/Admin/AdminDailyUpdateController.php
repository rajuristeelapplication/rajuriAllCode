<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Dealer;
use App\Models\Schedule;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;


class AdminDailyUpdateController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $userNameList = Schedule::selectRaw('users.id,users.fullName,users.roleId,dStateId,dSName,dCityId,dCName,dTalukaId,dTName')
            ->join('users', 'users.id', 'schedules.userId')
            ->distinct('users.id')
            // ->whereIn('roleId', User::whichUserLogin())
            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
            })

            ->where('schedules.isReport', 1)
            ->orderByRaw('fullName ASC')
            ->get();


            if($request->ajax()){

                if(!empty($request->s_name))
                {
                    $data['city'] = $userNameList->unique('dSName')->where('dStateId',$request->s_name)->whereNotNull('dSName');
                }

                if(!empty($request->cityId))
                {
                    $data['taluka'] = $userNameList->unique('dTName')->where('dCityId',$request->cityId)->whereNotNull('dTName');
                }

                return response()->json($data);
            }

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('dSName')->whereNotNull('dSName');
        $districtNameLists = $userNameList->unique('dCName')->whereNotNull('dCName');
        $talukaNameLists = $userNameList->unique('dTName')->whereNotNull('dTName');

            // users.roleId,
        $dealerNameList = Schedule::selectRaw('dealers.id,dealers.name,schedules.rjDealerId')
            ->join('dealers', 'dealers.id', 'schedules.rjDealerId')
            ->join('users', 'users.id', 'schedules.userId')
            ->distinct('dealers.id')
            ->where('schedules.isReport', 1)
            // ->whereIn('roleId', User::whichUserLogin())
            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
            })
            ->orderByRaw('name ASC')
            ->get();


            $getExpense = ExpenseType::where('type','Brand')->get(['id','eName']);


        return view('admin.dailyUpdate.list', compact('userNameList', 'dealerNameList','stateNameLists','districtNameLists','talukaNameLists','getExpense'));
    }

    /**
     * Search OR Sorting DVR (DataTable).
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

            $query = Schedule::scheduleDetails()->selectRaw('users.fullName as createdBy')
                ->where('schedules.isReport', 1)
                ->join('users', 'users.id', 'schedules.userId');

            if (!empty($request->userType)) {
                $query = $query->where('dealers.fType', '=', $request->userType);
            }

            if (!empty($request->employeeType)) {
                $query = $query->where('schedules.userId', '=', $request->employeeType);
            }

            if (!empty($request->dealerType)) {
                $query = $query->where('dealers.id', '=', $request->dealerType);
            }

            if(!empty($request->scheduleDate) ){
                $query =  $query->whereDate('schedules.dvrDate',$request->scheduleDate);
            }

            if (!empty($request->scheduleType)) {
                $query = $query->where('schedules.sType', '=', $request->scheduleType);
            }

            if (!empty($request->otherBrand)) {
                $query = $query->where('schedules.otherBrandName', 'like', '%' . $request->otherBrand . '%');
            }

            if(!empty($request->cityId)){
                $query = $query->where('schedules.dCityId','=',$request->cityId);
            }

            if(!empty($request->talukaId)){
                $query = $query->where('schedules.dTalukaId','=',$request->talukaId);
            }
            if(!empty($request->stateId)){
                $query = $query->where('schedules.dStateId','=',$request->stateId);
            }


            $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
            });

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('dealers.fType', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('dealers.dealerId', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('schedules.dSName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('schedules.dCName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('schedules.dTName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'dailyUpdate' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('dailyUpdates.show', $params);
                $editRoute   = route('dailyUpdates.edit', $params);
                $deleteRoute = route('dailyUpdates.destroy', $params);
                $statusRoute = route('dailyUpdates.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';


                $rows['data'][$key]['fType'] = $row['fType'] ?? "-";
                $rows['data'][$key]['sType'] = $row['sType'] ?? "-";
                $rows['data'][$key]['createdBy'] = $row['createdBy'] ?? "-";
                $rows['data'][$key]['name'] = $row['name'] ?? "-";
                $rows['data'][$key]['sDateFormate'] = $row['sDateFormate'] . ' ' . $row['sTimeFormate'];

                $rows['data'][$key]['createdAt'] = $row['createdAt'] ?? "-";
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Daily Update Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
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
        $scheduleInfo = Schedule::scheduleDetails()->selectRaw('users.fullName as createdBy')
            ->join('users', 'users.id', 'schedules.userId')->where('schedules.id', $id)->first();

        $brandName = [];
        if (!empty($scheduleInfo->otherBrandName)) {

            $brand = json_decode($scheduleInfo->otherBrandName);

            foreach ($brand as $key => $value) {
                $name = ExpenseType::select('eName')->where('id', $value)->first();
                $brandName[] =  $name->eName;
            }
            $brandName =  implode(', ', $brandName);
        }


        if (!empty($scheduleInfo)) {
            return view('admin.dailyUpdate.view', compact('scheduleInfo', 'brandName'));
        }

        return redirect(route('dailyUpdates.index'))->with('error', trans('messages.daily update.not_found', ['module' => 'daily update']));
    }


     /**
     * Change status of the DVR.
     *
     * @param string $id
     *
     * @return json
     */

    public function changeStatus($id)
    {
        $schedule = Schedule::find($id);

        if (empty($schedule)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'daily update']), 0);
        }

        $schedule->isActive = !$schedule->isActive;
        $status = '';
        if ($schedule->save()) {
            $status = $schedule->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'daily update', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'daily update', 'type' => $status]), 0);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */
    public function destroy($id)
    {
        $schedule = Schedule::where('id', $id)->first();
        if ($schedule) {

            $imageName = config('constant.schedule_image') .'/'.$schedule->uploadPhoto;
            User::bucketRemoveImage($imageName);

            $imageName1 = config('constant.schedule_image') .'/'.$schedule->watermarkImage;
            User::bucketRemoveImage($imageName1);

            $imageName2 = config('constant.schedule_image') .'/'.$schedule->voiceRecording;
            User::bucketRemoveImage($imageName2);

            $imageName3 = config('constant.schedule_image') .'/'.$schedule->competitorActivitiesImage;
            User::bucketRemoveImage($imageName3);


            $schedule->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'daily update']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'daily update']), 0);
    }


     /**
     * Get dealer.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getDealerDetail(Request $request)
    {
        $data = Dealer::where('id', $request->dealerIdVal)->first("dealerId");

        return response()->json($data);
    }

    /**
     * Get dealer by type.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getDealerByType(Request $request)
    {
        $data['dName'] = Dealer::where('fType', $request->userType)->join('users', 'users.id', 'dealers.userId')
            ->distinct('dealers.id')
            // ->whereIn('roleId', User::whichUserLogin())
            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
            })
            ->get(['dealers.id', 'dealers.name']);

        return response()->json($data);
    }

     /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        $userDetail = User::where('isActive', 1)->where(['userStatus' => 'Approved'])
            // ->whereIn('roleId', User::whichUserLogin())
            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('id',  User::getMarketingAdminEmployee());
            })
            ->get();
        return view('admin.dailyUpdate.create', compact('userDetail'));
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
            $message = trans('messages.msg.updated.success', ['module' => 'daily update']);
            $action = 'Edit';

            $schedule = Schedule::find($request->id);
        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'daily update']);

            $schedule = new Schedule();
        }
        $schedule->sTime = Carbon::createFromFormat('h:i A', $request->sTime)->format('h:i:s');

        $schedule->fill($request->except('sTime'));


        if ($schedule->save()) {

            return redirect()->route('dailyUpdates.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('dailyUpdates.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'daily update']), 0);
    }

   /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $scheduleDetail = Schedule::scheduleDetails()->where('schedules.id', $id)->first();
        $dealerDetail = Dealer::getSelectQuery()->get();
        $userDetail = User::where('isActive', 1)->where(['userStatus' => 'Approved'])
            ->whereIn('roleId', User::whichUserLogin())
            ->get();
        return view('admin.dailyUpdate.create', compact('scheduleDetail', 'dealerDetail', 'userDetail'));
    }

}
