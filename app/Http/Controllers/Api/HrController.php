<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\PaySlip;
use App\Models\HolidayList;
use App\Models\ExpenseType;
use App\Models\Reimbursement;
use App\Models\LeaveRequest;
use App\Models\RequestPayslips;
use App\Models\ReimbursementImage;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReimbursementValidation;


class HrController extends Controller
{
    /**
     * Get Current Year Holiday List
     *
     * @return json
     *
     */
    public function getHolidays(Request $request)
    {
        $getHolidays = HolidayList::getSelectQuery()->whereYear('hDate', date('Y'))->isActive()->orderBy('holiday_lists.hdate','asc')->get();

        return $this->toJson(['getHolidays' => $getHolidays]);
    }

    /**
     * Get Pay Slips
     *
     * @return json
     *
     */

    public function getPaySlips(Request $request)
    {

        $paySlipPagination = config('constant.paySlipPagination');

        $user = \Auth::user();

        $getPaySlips = PaySlip::getSelectQuery()

        ->where(['payslips.userId' => $user->id])
                        ->paginate($paySlipPagination);

        return $this->toJson([
            'hasMore' => $getPaySlips->hasMorePages(),
            'totalCount' => $getPaySlips->total(),
            'getPaySlips' => $getPaySlips->items(),]);
    }

    /**
     * Get Expense Types (different type title)
     *
     * @param  string type
     *
     * @return json
     *
     */

    public function getExpenseTypes(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:Reimbursement,Leave,Brand,OtherBrand,Purpose',
        ]);

        if($request->type == "Purpose")
        {
            $getExpenseTypes = config('constant.purposeArray');
        }else{
            $getExpenseTypes = ExpenseType::getSelectQuery()->where(['type' => $request->type ])->isActive()->orderBy('expense_types.createdAt','asc')->get();
        }



        return $this->toJson(['getExpenseTypes' => $getExpenseTypes]);
    }

     /**
     * Create Reimbursement
     *
     * @param  ReimbursementValidation $request
     *
     * @return json
     */

    public function addReimbursement(ReimbursementValidation $request)
    {
        $user       = \Auth::user();
        $userId     =  $user->id;

        $data = $request->validate(
            [
                "rAttachment" => "array|max:5",
            ]
        );

        $reimbursementRecord = Reimbursement::Create($request->merge(['userId' => $userId])->all());

        // Max 5 Image Upload
        if (!empty($request->rAttachment)) {

            foreach ($request->rAttachment as $image) {
                ReimbursementImage::Create(['reimbursementsId'=>$reimbursementRecord->id, 'rAttachment'=> $image]);
            }
        }


        NotificationHelper::reimbursementNotification($reimbursementRecord,1);

        return $this->toJson([
            'reimbursementRecord' => $reimbursementRecord,
        ], trans('api.reimbursement.success',['type' => 'Request Send to Hr']));
    }

    /**
     *  Reimbursement History
     *
     *  @return json
     */
    public function getReimbursements(Request $request)
    {

        $reimbursementHistory = config('constant.reimbursementHistoryPagination');

        $user = \Auth::user();

        $reimbursementHistory = Reimbursement::getSelectQuery()
                        ->where(['reimbursements.userId' => $user->id])
                        ->orderBy('reimbursements.createdAt','desc')
                        ->paginate($reimbursementHistory);

        return $this->toJson([
            'hasMore' => $reimbursementHistory->hasMorePages(),
            'totalCount' => $reimbursementHistory->total(),
            'reimbursementHistory' => $reimbursementHistory->items(),]);

    }
      /**
     *  Reimbursement Details
     *
     * @param  string id
     *
     *  @return json
     */

    public function reimbursementDetails(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $user = \Auth::user();

        $id = $request->id ?? '';
        $reimbursementDetails =  Reimbursement::reimbursementDetails()->where(['reimbursements.id' => $id,'reimbursements.userId' => $user->id])->first();

        if(empty($reimbursementDetails))
        {
            return $this->toJson([],trans('api.reimbursement.not_found'),0);
        }

        return $this->toJson([
            'reimbursementDetails' => $reimbursementDetails,
        ]);
    }

     /**
     *  Apply Leave
     *
     * @param  string fromDate
     * @param  string toDate
     *
     * @return json
     */

    public function applyLeave(Request $request)
    {

        $this->validate($request, [
            'lType' => 'required|in:Casual Leave,Medical Leave',
            'fromDate' => 'required|date_format:Y-m-d',
            'toDate' => 'required|date_format:Y-m-d|after_or_equal:fromDate',
            'leaveReasonId' => 'required',
        ]);

        $fromDate = Carbon::parse($request->fromDate);
        $noOfLeave = $fromDate->diffInDays($request->toDate);

        $user       = \Auth::user();
        $userId     =  $user->id;

        $checkLeaveAvail =  LeaveRequest::where('lRStatus','!=','Rejected')
                            ->whereRaw("userId = '{$userId}' AND '{$request->toDate}' BETWEEN `fromDate` AND `toDate`")
                            ->first();

        if(!empty($checkLeaveAvail))
        {
             return $this->toJson([],trans('api.apply_leave.already'),0);
        }

        $leaveRecord = LeaveRequest::Create($request->merge(['userId' => $userId,'noOfLeave' => $noOfLeave + 1])->all());

        NotificationHelper::leaveNotification($leaveRecord,1);


        return $this->toJson([
            'leaveRecord' => $leaveRecord,
        ], trans('api.apply_leave.success'));

    }
    /**
     * Get Leaves
     *
     * @return json
     *
     */

    public function getLeaves(Request $request)
    {
        $leavePagination = config('constant.leavePagination');

        $user = \Auth::user();

        $getLeaves = LeaveRequest::getSelectQuery()
                        ->where(['leave_requests.userId' => $user->id])
                        ->paginate($leavePagination);

        return $this->toJson([
            'hasMore' => $getLeaves->hasMorePages(),
            'totalCount' => $getLeaves->total(),
            'getLeaves' => $getLeaves->items(),]);
    }


    /**
     * Apply Salary Pay Sleep
     *
     * @return json
     *
     */

    public function applyPaySleep(Request $request)
    {
        $this->validate($request, [
            'rPDate' => 'required|date_format:Y-m-d',
        ]);

        $rPDate = date("Y-m-d", strtotime($request->rPDate));

        $user       = \Auth::user();
        $userId     =  $user->id;


        $check = RequestPayslips::where(['userId' => $userId,'rPDate' => $rPDate])->first();

        if(!empty($check))
        {
            return $this->toJson([],trans('api.already_apply_pay_sleep'),0);
        }

        $requestPaySleep = new RequestPayslips();
        $requestPaySleep->userId = $userId;
        $requestPaySleep->rPDate = $rPDate;

        if($requestPaySleep->save())
        {
            return $this->toJson([
                'requestPaySleep' => $requestPaySleep,
            ], trans('api.apply_pay_sleep.success'));

        }
    }

}
