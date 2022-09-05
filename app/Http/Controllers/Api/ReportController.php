<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ReportHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;


class ReportController extends Controller
{
    /**
     *  Merchandises Report
     *
     * @param string $mType
     * @param string $startDate
     * @param string $endDate
     * @param string $type
     *
     * @return json
     */

    public function merchandisesReport(Request $request)
    {
        $this->validate($request, [
            'mType' =>'required|in:All,Order,Gift',
            'startDate' => 'required',
            'endDate' => 'required',
            'type' =>'required|in:pdf,view,excel',
        ]);

        $user = \Auth::guard('api')->user();

        ini_set('max_execution_time', 300);

        $merchandises = ReportHelper::merchandisesReport($request, "api");

            if($request->mType != "All")
               {
                $merchandises =   $merchandises->where(['merchandises.mType' => $request->mType]);
               }


        $merchandises = $merchandises->where(['merchandises.userId' => $user->id])->first();

        if (!empty($merchandises)) {
                return $this->toJson(
                    ['url' => url('/merchandises-pdf/'.Crypt::encryptString($user->id).'/'.
                                                        Crypt::encryptString($request->startDate).'/'.
                                                        Crypt::encryptString($request->endDate).'/'.
                                                        Crypt::encryptString($request->type).'/'.
                                                        Crypt::encryptString($request->mType).'/'.
                                                        Crypt::encryptString($request->stateId) .'/'.
                                                        Crypt::encryptString($request->cityId) .'/'.
                                                        Crypt::encryptString($request->talukaId) .'/'.
                                                        Crypt::encryptString($request->search)
                                 )], trans('api.reprot.success', ['module' => 'Merchandises']), 1);
        }

        return $this->toJson([], trans('api.reprot.not_found', ['module' => 'Merchandises']), 0);
    }

     /**
     *  Lead Report
     *
     * @param string $lType
     * @param string $startDate
     * @param string $endDate
     * @param string $type
     *
     * @return json
     */

    public function leadReport(Request $request)
    {

        $this->validate($request, [
            'lType' =>'required|in:All,Material Lead,Dealership Lead',
            'startDate' => 'required',
            'endDate' => 'required',
            'type' =>'required|in:pdf,view,excel',
        ]);

        $user = \Auth::guard('api')->user();

        ini_set('max_execution_time', 300);

        $lead = ReportHelper::leadReport($request, "api");

        if($request->lType != "All")
        {
         $lead =   $lead->where(['leads.lType' => $request->lType]);
        }

        $lead = $lead->where(['leads.userId' => $user->id])->first();


        if (!empty($lead)) {
                return $this->toJson(
                    ['url' => url('/leads-pdf/'.Crypt::encryptString($user->id).'/'.
                                                Crypt::encryptString($request->startDate).'/'.
                                                Crypt::encryptString($request->endDate).'/'.
                                                Crypt::encryptString($request->type).'/'.
                                                Crypt::encryptString($request->lType) .'/'.
                                                Crypt::encryptString($request->stateId) .'/'.
                                                Crypt::encryptString($request->cityId) .'/'.
                                                Crypt::encryptString($request->talukaId) .'/'.
                                                Crypt::encryptString($request->search)
                                )], trans('api.reprot.success', ['module' => 'Lead Report']), 1);
        }

        return $this->toJson([], trans('api.reprot.not_found', ['module' => 'Lead Report']), 0);
    }

     /**
     *  Daily Update Report
     *
     * @param string $sType
     * @param string $startDate
     * @param string $endDate
     * @param string $type
     *
     * @return json
     */

    public function dailyUpdateReport(Request $request)
    {
        $this->validate($request, [
            'sType' =>'required|in:All,Dealer Visit,Site Visit,Influencer Visit',
            'startDate' => 'required',
            'endDate' => 'required',
            'type' =>'required|in:pdf,view,excel',
        ]);

        $user = \Auth::guard('api')->user();

        ini_set('max_execution_time', 300);

        $lead = ReportHelper::dailyReport($request, "api");

        if($request->sType != "All")
        {
         $lead =   $lead->where(['schedules.sType' => $request->sType]);
        }

        $lead = $lead->where(['schedules.userId' => $user->id,'schedules.schedulesStatus' => 'End'])->first();

        if (!empty($lead)) {
                return $this->toJson(
                    ['url' => url('/daily-pdf/'.Crypt::encryptString($user->id).'/'.
                                                Crypt::encryptString($request->startDate).'/'.
                                                Crypt::encryptString($request->endDate).'/'.
                                                Crypt::encryptString($request->type).'/'.
                                                Crypt::encryptString($request->sType).'/'.
                                                Crypt::encryptString($request->stateId) .'/'.
                                                Crypt::encryptString($request->cityId) .'/'.
                                                Crypt::encryptString($request->talukaId) .'/'.
                                                Crypt::encryptString($request->search)


                                                )], trans('api.reprot.success', ['module' => 'Daily Update Report']), 1);
        }

        return $this->toJson([], trans('api.reprot.not_found', ['module' => 'Daily Update Report']), 0);
    }

}
