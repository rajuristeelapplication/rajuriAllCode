<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\InOut;
use App\Models\TrackLocation;
use App\Models\TrackLocationAddress;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminInOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // dd(\Auth::user()->roleId);

        $userNameList = User::selectRaw('id,fullName')
                        ->where(['userStatus' => 'Approved'])
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('id',  User::getMarketingAdminEmployee());
                        })

        ->orderByRaw('fullName ASC')->get();

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');

        return view('admin.inOut.list', compact('userNameList'));
    }


   /**
     * Search OR Sorting In Out (DataTable).
     *
     * @param Request $request
     *
     * @return json
     */

    public function search(Request $request)
    {
        if ($request->ajax()) {

            $daterange = explode(' - ', $request->daterange);
            $startDate = "";
            $endDate = "";
            if(!empty($request->daterange))
            {
                $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');

            }

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            // in_outs.travelMinutes,

            $query = InOut::getSelectQuery()
                    ->selectRaw('users.fullName as createdBy,users.roleId, DATE_FORMAT(in_outs.inDateTime, "' . config('constant.in_out_date_time_format') . '") as inDateTime, DATE_FORMAT(in_outs.outDateTime, "' . config('constant.in_out_date_time_format') . '") as outDateTime,
                    in_outs.travelKm,
                    CONCAT(FLOOR(in_outs.travelMinutes/60)," hours ",MOD(in_outs.travelMinutes,60)," min") as travelMinutes
                    ')
                    ->join('users', 'users.id', 'in_outs.userId')
                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('in_outs.userId',  User::getMarketingAdminEmployee());
                    });



            if (!empty($request->employeeType)) {
                $query = $query->where('in_outs.userId', '=', $request->employeeType);
            }

            if (!empty($request->userType)) {
                $query = $query->where('roleId', '=', $request->userType);
            }

            if(!empty($startDate) && !empty($endDate))
            {
                $query =  $query->whereDate('in_outs.date', '>=', $startDate)
                ->whereDate('in_outs.date', '<=', $endDate);
            }

            if(\Auth::user()->roleId == config('constant.ma_id'))
            {
                $query = $query->where('roleId','=',config('constant.marketing_executive_id'));
            }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('in_outs.inAddress', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('in_outs.outAddress', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('in_outs.travelKm', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('in_outs.travelMinutes', 'like', '%' . $request->search['value'] . '%')
                    ->orWhereRaw('DATE_FORMAT(in_outs.inDateTime, "' . config('constant.in_out_date_time_format') . '")  like "%' . $request->search['value'] . '%"')
                    ->orWhereRaw('CONCAT(FLOOR(in_outs.travelMinutes/60),":",MOD(in_outs.travelMinutes,60)," min")   like "%' . $request->search['value'] . '%"');
            });

            $inOuts = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $inOuts['recordsFiltered'] = $inOuts['recordsTotal'] = $inOuts['total'];

            foreach ($inOuts['data'] as $key => $inOut) {

                $params = [
                    'inOuts' => $inOut['id'],
                ];

                $inOuts['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('inOuts.show', $params);
                $deleteRoute = route('inOuts.destroy', $params);
                $mapRoutes = route('inOutMap',['userId' => $inOut['userId'],'date' => $inOut['date']]);

                $inOuts['data'][$key]['createdBy'] = $inOut['createdBy'] ? ucfirst($inOut['createdBy']): '-';
                $inOuts['data'][$key]['inAddress'] = !empty($inOut['inAddress']) ? '<span title="'.$inOut['inAddress'].'">'. Str::limit(strip_tags($inOut['inAddress']), 21, $end = '...').'</span>' : '-';
                $inOuts['data'][$key]['inDateTime'] = $inOut['inDateTime'] ?? '-';
                $inOuts['data'][$key]['outAddress'] = !empty($inOut['outAddress']) ? '<span title="'.$inOut['outAddress'].'">'.Str::limit(strip_tags($inOut['outAddress']), 21, $end = '...').'</span>' : '-';
                $inOuts['data'][$key]['outDateTime'] = $inOut['outDateTime'] ?? '-';
                $inOuts['data'][$key]['createdAt'] = $inOut['createdAt'];
                $inOuts['data'][$key]['travelKm'] = round($inOut['travelKm'],2);
                $inOuts['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="In Out Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $inOuts['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
                $inOuts['data'][$key]['action']   .= '<a href="' . $mapRoutes . '" class="btn btn-primary" title="Map">Map</a>&nbsp&nbsp';
            }
            return response()->json($inOuts);
        }
    }

     /**
     * Change status of the In Out.
     *
     * @param string $id
     *
     * @return json
     */

    public function changeStatus($id)
    {
        $inOut = InOut::find($id);

        if (empty($inOut)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'in/out']), 0);
        }

        $inOut->isActive = !$inOut->isActive;
        $status = '';
        if ($inOut->save()) {
            $status = $inOut->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'in/out', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'in/out', 'type' => $status]), 0);
    }

     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $inOutDetail = InOut::getSelectQuery()
        ->selectRaw('users.fullName as createdBy, DATE_FORMAT(in_outs.inDateTime, "' . config('constant.in_out_date_time_format') . '") as inDateTime, DATE_FORMAT(in_outs.outDateTime, "' . config('constant.in_out_date_time_format') . '") as outDateTime')
        ->join('users', 'users.id', 'in_outs.userId')
        ->where('in_outs.id',$id)
        ->first();

        if (!empty($inOutDetail)) {
          return view('admin.inOut.view',compact('inOutDetail'));
        }

        return redirect(route('inOuts.index'))->with('error', trans('messages.in/out.not_found', ['module' => 'in/out']));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     *
     * @return json
     */

    public function destroy($id)
    {
        $inOut = InOut::where('id', $id)->first();
        if ($inOut) {

            $date = $inOut->createdAt;

            $date = date('Y-m-d', strtotime($date));


            TrackLocation::where('userId',$inOut->userId)->whereDate('createdAt',$date)->delete();
            TrackLocationAddress::where('userId',$inOut->userId)->whereDate('createdAt',$date)->delete();

            $imageName = config('constant.in_out_image') .'/'.$inOut->inReadingPhoto;
            User::bucketRemoveImage($imageName);


            $imageName1 = config('constant.in_out_image') .'/'.$inOut->outReadingPhoto;
            User::bucketRemoveImage($imageName1);

            $inOut->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'in/out']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'in/out']), 0);
    }

      /**
         * In Out Map Show lat long
         *
         * @param  string  $date
         * @param  string  $userId
         *
         * @return json
     */

    public function inOutMap(Request $request)
    {
       $date = $request->date;
       $userId = $request->userId;

       $results = TrackLocation::where('userId',$userId)
       ->where('lessthen100Min',0)
       ->whereRaw('date(createdAt) = ?', [$date] )->where('latitude','!=','GPS_OFF')->orderBy('createdAt','asc')->get();

    //    echo "<pre>";
    //    print_r($results->toArray());
    //    exit;

        $resultsCount = count($results);

        if($resultsCount == 0)
        {
            return redirect(route('inOuts.index'))->with('error', trans('messages.in-out.not_found'));
        }

       $array = [];
       $append = '|';

       $diff_in_minutes = 0;
       $travelKm = 0;

       foreach($results as $key=>$val)
       {
            if($val->latitude != "GPS_OFF")
            {
                $timeFormat = '@ '. date('H:i A', strtotime($val->createdAt));
                $append = ($key != $resultsCount - 1) ? $append:'';
                $array[] ="$val->latitude,$val->longitude,$timeFormat".$append;
            }
       }


       $string = implode("",$array);

       $userDetails = User::where('id',$userId)->first();

       $tlEvents = TrackLocationAddress::where('userId',$userId)->whereRaw('date(createdAt) = ?', [$date] )->orderBy('createdAt','asc')->get();

       $totalRecordtlEvent = count($tlEvents) - 1;



       foreach($tlEvents as $tlEvent)
       {
            if($tlEvent->event == "TRAVEL" || $tlEvent->event == "GPS_OFF")
            {
                if(!empty($tlEvent->startDateTime) && !empty($tlEvent->endDateTime))
                {

                    $to = \Carbon\Carbon::parse($tlEvent->startDateTime);
                    $from = \Carbon\Carbon::parse($tlEvent->endDateTime);

                    $diff_in_minutes += $to->diffInMinutes($from);
                    $travelKm += $tlEvent->distanceKm;
                }
            }
       }

       $dynamicHoursAndMin = intdiv($diff_in_minutes, 60).' hr '. ($diff_in_minutes % 60) . ' min';


    //    echo "<pre>";
    //    print_r($tlEvent->toArray());
    //    exit;

       return view('admin.schedules.map',compact('string','userDetails','results','resultsCount','tlEvents','dynamicHoursAndMin','travelKm','totalRecordtlEvent'));

    }

}
