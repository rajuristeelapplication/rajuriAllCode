<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScheduleValidation;


class DRMController extends Controller
{
    /**
     * Create or Update Report Visit
     *
     * @param  ScheduleValidation $request
     *
     * @return json
     */

    public function reportDailyUpdate(ScheduleValidation $request)
    {
        $user = \Auth::user();

        $schedule = new Schedule();

        if(!empty($request->id))
        {
            $schedule =  Schedule::where(['id' => $request->id, 'userId' => $user->id])->first();

            if (empty($schedule)) {
                return $this->toJson([], trans('api.schedule.not_found'), 0);
            }
        }

        $schedule->userId = $user->id;
        $schedule->sDateTime = $request->sDate . ' ' . $request->sTime;
        $schedule->fill($request->all());
        $schedule->otherBrandName = json_encode($request->otherBrandName);
        $schedule->schedulesStatus = 'End';
        $schedule->isReport = 1;
        $schedule->dvrDate = date('Y-m-d');
        $schedule->save();

        return $this->toJson([
            'reportDailyUpdate' => $schedule,
        ], trans('api.daily_visit_report.success', ['type' => 'Update']));
    }

    /**
     * Get Daily Report Update List
     *
     * @param  Request $request
     *
     * (optional parameters)
     *
     * @param  string dType
     * @param  string fType
     * @param  string search
     *
     * @return json
     */

    public function getDailyUpdate(Request $request)
    {
        $schedulePagination = config('constant.schedulePagination');
        $user = \Auth::user();

        $this->validate($request, [
            'dType' => 'required|in:Dealer Visit,Site Visit,Influencer Visit',
        ]);

        $dType = $request->dType ?? '';
        $fType = $request->fType ?? '';
        $search = $request->search ?? '';


        $getDailyNotReport = Schedule::getSelectQuery($user->id)

        ->when(!empty($dType), function ($query) use ($request) {
            return $query->where('schedules.sType',  $request->dType);
        })
        ->when(!empty($fType), function ($query) use ($request) {
            return $query->whereIn('dealers.fType',  $request->fType);
        })
        ->when(!empty($fType), function ($query) use ($request) {
            return $query->whereIn('dealers.fType',  $request->fType);
        })

        ->when(!empty($search), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->where('dealers.name', "like", "%" . $request->search . "%");
            });
        })

        ->where(['schedules.userId' => $user->id,'schedules.schedulesStatus' => 'End','isReport' => 0])
        ->orderBy('schedules.createdAt','desc')
        ->get();

        $getDailyReport = Schedule::getSelectQuery($user->id)

            ->when(!empty($dType), function ($query) use ($request) {
                return $query->where('schedules.sType',  $request->dType);
            })
            ->when(!empty($fType), function ($query) use ($request) {
                return $query->whereIn('dealers.fType',  $request->fType);
            })
            ->when(!empty($fType), function ($query) use ($request) {
                return $query->whereIn('dealers.fType',  $request->fType);
            })

            ->when(!empty($search), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->where('dealers.name', "like", "%" . $request->search . "%");
                });
            })

            ->where(['schedules.userId' => $user->id,'schedules.schedulesStatus' => 'End','isReport' => 1])
            ->orderBy('schedules.createdAt','desc')
            ->paginate($schedulePagination);

        return $this->toJson([
            'getDailyNotReport' => $getDailyNotReport,
            'hasMore' => $getDailyReport->hasMorePages(),
            'totalCount' => $getDailyReport->total(),
            'getDailyUpdate' => $getDailyReport->items(),
        ]);
    }


}
