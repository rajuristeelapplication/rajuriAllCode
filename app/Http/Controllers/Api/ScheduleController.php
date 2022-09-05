<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScheduleValidation;
use App\Http\Requests\Api\ScheduleStartValidation;
use App\Http\Requests\Api\ScheduleEndValidation;
use App\Http\Requests\Api\ScheduleValidationGet;

class ScheduleController extends Controller
{
    /**
     * Create  Schedule
     *
     * @param ScheduleValidation $request
     *
     * @return json
     */

    public function createSchedule(ScheduleValidation $request)
    {
        $user = \Auth::user();

        $schedule = new Schedule();
        $schedule->userId = $user->id;
        $schedule->sDateTime = $request->sDate . ' ' . $request->sTime;
        $schedule->fill($request->all());
        $schedule->save();

        return $this->toJson([
            'schedule' => $schedule,
        ], trans('api.schedule.success', ['type' => 'Create']));
    }

    /**
     * Start Visit
     *
     * @param ScheduleStartValidation $request
     *
     * @return json
     */

    public function startVisit(ScheduleStartValidation $request)
    {
        $user = \Auth::user();

        $schedule =  Schedule::where(['id' => $request->id, 'userId' => $user->id])->first();

        if (empty($schedule)) {
            return $this->toJson([], trans('api.schedule.not_found'), 0);
        }

        if (in_array($schedule->schedulesStatus, ['End','Cancel'])) {
            return $this->toJson([], trans('api.schedule.start.visit'), 0);
         }

        $schedule->fill($request->only(['startLocation','startLatitude','startLongitude','uploadPhoto','watermarkImage','voiceRecording','purpose']));
        $schedule->schedulesStatus = 'Start';
        $schedule->save();

        return $this->toJson([
            'schedule' => $schedule,
        ], trans('api.schedule.success', ['type' => 'Start']));
    }

    /**
     * End Visit
     *
     * @param ScheduleEndValidation $request
     *
     * @return json
     */

    public function endVisit(ScheduleEndValidation $request)
    {
        $user = \Auth::user();

        $schedule =  Schedule::where(['id' => $request->id, 'userId' => $user->id])->first();

        if (empty($schedule)) {
            return $this->toJson([], trans('api.schedule.not_found'), 0);
        }

        if($schedule->schedulesStatus != "Start")
        {
            return $this->toJson([], trans('api.schedule.end.visit'), 0);
        }

        $schedule->fill($request->only(['endLatitude','endLongitude','endLocation','uploadPhoto','watermarkImage','voiceRecording','purpose']));
        $schedule->schedulesStatus = 'End';
        $schedule->save();

        return $this->toJson([
            'schedule' => $schedule,
        ], trans('api.schedule.success', ['type' => 'End']));
    }

    /**
     * Cancel Schedule
     *
     * @param string $id
     *
     * @return json
     */


    public function cancelSchedule(Request $request)
    {
        $user = \Auth::user();

        $schedule =  Schedule::where(['id' => $request->id, 'userId' => $user->id])->first();

        if (empty($schedule)) {
            return $this->toJson([], trans('api.schedule.not_found'), 0);
        }

        if($schedule->schedulesStatus == "End")
        {
            return $this->toJson([], trans('api.schedule.cancel.visit'), 0);
        }

        $schedule->schedulesStatus = 'Cancel';
        $schedule->save();

        return $this->toJson([
            'schedule' => $schedule,
        ], trans('api.schedule.success', ['type' => 'Cancel']));
    }

    /**
     * Get Schedules Information
     *
     * (optional parameters)
     *
     * @param string sType
     * @param string fType
     * @param string search
     *
     * @return json
     */

    public  function getSchedules(ScheduleValidationGet $request)
    {

        $schedulePagination = !empty($request->paginationRecord) ? $request->paginationRecord : config('constant.schedulePagination');

        $user = \Auth::user();

        $sType = $request->sType ?? '';
        $fType = $request->fType ?? '';
        $search = $request->search ?? '';

        $getSchedules = Schedule::getSelectQuery($user->id)
            ->when(!empty($sType), function ($query) use ($sType) {

                if($sType == "upcomming")
                {
                    // return $query->where('sDate','>=', Carbon::today())->where('schedulesStatus','!=','End');
                    return $query->where('sDate','>=', Carbon::today())->whereNotIn('schedulesStatus',['End','Cancel']);
                }else if($sType == "todayVisit"){
                    // return $query->where('sDate',Carbon::today())->where('schedulesStatus','!=','End');
                    return $query->where('sDate',Carbon::today())->whereNotIn('schedulesStatus',['End','Cancel']);
                }
                else{
                    return $query->where(function ($query) {
                        $query->where('sDate','<', Carbon::today())
                             ->orwhereIn('schedulesStatus',['End','Cancel']);
                            //   ->orWhere('schedulesStatus',['End','Cancel']);
                    });
                    // return $query->where('sDate','<', Carbon::today())->orWhere('schedulesStatus','End');
                }
            })
            ->when(!empty($fType), function ($query) use ($request) {
                return $query->whereIn('dealers.fType',  $request->fType);
            })

            ->when(!empty($search), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->where('dealers.name', "like", "%" . $request->search . "%");
                });
            })

            ->where(['schedules.userId' => $user->id])
            ->orderBy('schedules.createdAt','desc')
            ->paginate($schedulePagination);

        return $this->toJson([
            'hasMore' => $getSchedules->hasMorePages(),
            'totalCount' => $getSchedules->total(),
            'getSchedules' => $getSchedules->items(),

        ]);
    }

    /**
     * Get Schedules Details
     *
     * @param string $id (schedule Id)
     *
     * @return json
     */

    public  function schedulesDetails(Request $request)
    {

        $id = $request->id ?? '';
        $dealerDetails =  Schedule::scheduleDetails()->where('schedules.id', $id)->first();

        $dealerDetails->otherBrandName = !empty($dealerDetails->otherBrandName) ? $dealerDetails->otherBrandName :  "[]";

        if (empty($dealerDetails)) {
            return $this->toJson([], trans('api.schedule.not_found'), 0);
        }

        return $this->toJson([
            'dealerDetails' => $dealerDetails,
        ]);
    }
}
