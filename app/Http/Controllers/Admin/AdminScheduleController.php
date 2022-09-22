<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Dealer;
use App\Models\Schedule;
use App\Models\TrackLocation;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userNameList = Schedule::selectRaw('users.id,users.fullName,users.roleId')
                                ->join('users','users.id','schedules.userId')
                                ->distinct('users.fullName')
                                // ->whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                                })
                                ->orderByRaw('fullName ASC')
                                ->get();
        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');

        $dealerNameList = Schedule::selectRaw('dealers.id,dealers.name,users.roleId,schedules.rjDealerId')
                                ->join('dealers','dealers.id','schedules.rjDealerId')
                                ->join('users','users.id','schedules.userId')
                                ->distinct('schedules.rjDealerId')
                                // ->whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                                })
                                ->orderByRaw('name ASC')
                                ->get();

        return view('admin.schedules.list', compact('userNameList', 'dealerNameList'));
    }

    /**
     * Search OR Sorting Schedule (DataTable).
     *
     * @param Request $request
     *
     * @return json
     */

    public function search(Request $request)
    {
        if ($request->ajax()) {

            $selectHours = $request->hours;
            $selectMinutes = $request->minutes;
            $selectamPM = $request->amPM;

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = Schedule::scheduleDetails()->selectRaw('users.fullName as createdBy,purpose')
                ->join('users', 'users.id', 'schedules.userId');

            if (!empty($request->userType)) {
                $query = $query->where('dealers.fType', '=', $request->userType);
            }

            if (!empty($request->employeeType)) {
                $query = $query->where('schedules.userId', '=', $request->employeeType);
            }

            if(!empty($request->scheduleDate) ){
                $query =  $query->whereDate('schedules.sDate',$request->scheduleDate);
            }

            if(!empty($selectHours) && !empty($selectMinutes) && !empty($selectamPM))
            {

                $input = "$selectHours:$selectMinutes $selectamPM";

                $input = date("H:i", strtotime($input));

               $query =  $query->where('schedules.sTime',$input);

            }

            // if(!empty($request->scheduleTime) ){
            //     $query =  $query->where('schedules.sTime',$request->scheduleTime);
            // }

            if (!empty($request->dealerType)) {
                $query = $query->where('dealers.id', '=', $request->dealerType);
            }

            if (!empty($request->scheduleType)) {
                $query = $query->where('schedules.sType', '=', $request->scheduleType);
            }

            if (!empty($request->purpose)) {
                $query = $query->where('schedules.purpose', '=', $request->purpose);
            }

            if(!empty($request->schedule) && $request->schedule == "Upcoming")
            {
                $query = $query->where('schedules.sDate','>=', Carbon::today())->where('schedules.schedulesStatus','!=','End');
            }

            if(!empty($request->schedule) && $request->schedule == "History")
            {
                $query = $query->where(function ($query) {
                            $query->where('schedules.sDate','<', Carbon::today())
                            ->orWhere('schedules.schedulesStatus','=','End');
                        });
            }

              // Role Base Condition

              if(\Auth::user()->roleId == config('constant.ma_id'))
              {
                //   $query = $query->where('roleId','=',config('constant.marketing_executive_id'));

                  $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                });
              }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('dealers.fType', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('dealers.dealerId', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'schedule' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('schedules.show', $params);
                $editRoute   = route('schedules.edit', $params);
                $deleteRoute = route('schedules.destroy', $params);
                $statusRoute = route('schedules.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';


                $rows['data'][$key]['fType'] = $row['fType'] ?? "-";
                $rows['data'][$key]['sType'] = $row['sType'] ?? "-";
                $rows['data'][$key]['createdBy'] = $row['createdBy'] ?? "-";
                $rows['data'][$key]['name'] = $row['name'] ?? "-";
                $rows['data'][$key]['sDateFormate'] = $row['sDateFormate'].' '.$row['sTimeFormate'];

                $rows['data'][$key]['createdAt'] = $row['createdAt'] ?? "-";
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Schedule Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
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
        $scheduleInfo = Schedule::scheduleDetails()->selectRaw('users.fullName as createdBy')
            ->join('users', 'users.id', 'schedules.userId')->where('schedules.id', $id)->first();


        if (!empty($scheduleInfo)) {
            return view('admin.schedules.view', compact('scheduleInfo'));
        }

        return redirect(route('schedules.index'))->with('error', trans('messages.schedule.not_found', ['module' => 'schedule']));
    }


    /**
     * Change status of the Schedule.
     *
     * @param string $id
     *
     * @return json
     */

    public function changeStatus($id)
    {
        $schedule = Schedule::find($id);

        if (empty($schedule)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'schedule']), 0);
        }

        $schedule->isActive = !$schedule->isActive;
        $status = '';
        if ($schedule->save()) {
            $status = $schedule->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'schedule', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'schedule', 'type' => $status]), 0);
    }

     /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        $userDetail = User::where('isActive', 1)->where(['userStatus' => 'Approved'])
                    // ->whereIn('roleId',User::whichUserLogin())
                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('id',  User::getMarketingAdminEmployee());
                    })
                    ->get();
        return view('admin.schedules.create', compact('userDetail'));
    }

     /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;

        if ($request->id) {
            $message = trans('messages.msg.updated.success', ['module' => 'schedule']);
            $action = 'Edit';

            $schedule = Schedule::find($request->id);
        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'schedule']);

            $schedule = new Schedule();
        }
        $schedule->sTime = Carbon::createFromFormat('h:i A', $request->sTime)->format('h:i:s');

        $schedule->fill($request->except('sTime'));

        if ($schedule->save()) {

            return redirect()->route('schedules.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('schedules.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'schedule']), 0);
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
        $dealerDetail = Dealer::getSelectQuery()
                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                    })
                    ->get();
        $userDetail = User::where('isActive', 1)->where(['userStatus' => 'Approved'])
                    ->whereIn('roleId',User::whichUserLogin())
                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('id',  User::getMarketingAdminEmployee());
                    })
                    ->get();
        return view('admin.schedules.create', compact('scheduleDetail', 'dealerDetail', 'userDetail'));
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

            $schedule->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'schedule']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'schedule']), 0);
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
        $data = Dealer::where('id', $request->dealerIdVal)->first();

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
        $dealer = Dealer::where('fType', $request->userType);

        if($request->userId)
        {
            // $dealer = $dealer->where('userId',$request->userId);
        }

        $dealer = $dealer->join('users','users.id','dealers.userId')
                                ->distinct('dealers.id')
                                // ->whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                })
                                ->get(['dealers.id', 'dealers.name']);

        $data['dName'] = $dealer;

        return response()->json($data);
    }


    public function scheduleMap(Request $request)
    {

        $results = TrackLocation::where('userId','f85c820c-6023-4188-ac8c-aca887e30b75')->get();

        // echo "<pre>";
        // print_r($results->toArray());

        $array = [];
        $append = '|';
        foreach($results as $key=>$val)
        {

            $append = ($key != 2) ? $append:'';

            $array[] ="$val->latitude,$val->longitude".$append;

            // $array[] = "'".$val->latitude.','.$val->longitude;
        }

        // $string = $array;
        $string = implode("",$array);
        // $string = substr($string, 0, -1);


        return view('admin.schedules.map',compact('string'));
    }
}
