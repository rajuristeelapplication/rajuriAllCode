<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\RequestPayslips;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\PaySlip;
use Carbon\Carbon;

class AdminLeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $userNameList = LeaveRequest::selectRaw('users.id,users.fullName')
                                ->join('users','users.id','leave_requests.userId')
                                ->distinct('users.id')
                                // ->whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
                                })
                                ->orderByRaw('fullName ASC')
                                ->get();

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        return view('admin.leaves.list',compact('userNameList'));
    }

   /**
     * Search OR Sorting Leave (DataTable).
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

            $query = LeaveRequest::getSelectQuery()
                     ->selectRaw('users.fullName, leave_requests.id, DATE_FORMAT(leave_requests.createdAt, "' . config('constant.in_out_date_time_format') . '") as createdAtFormate')
                     ->join('users', 'users.id', 'leave_requests.userId')
                     ->where('leave_requests.lRStatus', '!=', 'Credit');


            $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
                    });

            if(!empty($request->employeeType)){
                  $query = $query->where('leave_requests.userId','=',$request->employeeType);
             }

            if(!empty($request->leave_type)){
                $query = $query->where('leave_requests.lType','=',$request->leave_type);
            }

            if(!empty($request->leave_status)){
                $query = $query->where('leave_requests.lRStatus','=',$request->leave_status);
            }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leave_requests.lType', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leave_requests.lRStatus', 'like', '%' . $request->search['value'] . '%');
            });

            $leaves = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $leaves['recordsFiltered'] = $leaves['recordsTotal'] = $leaves['total'];

            foreach ($leaves['data'] as $key => $leave) {

                $params = [
                    'leaves' => $leave['id'],
                ];

                $leaves['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('leaves.show', $params);
                $userStatusRoute = route('leaves.leaveStatus', $params);
                $deleteRoute = route('leaves.destroy', $params);


                $status = $leave['lRStatus'];
                $type = '';
                if($status == 'Pending')
                {
                    $type = 'info';
                }
                else if($status == 'Approved')
                {
                    $type = 'success';
                }else{
                    $type = 'danger';
                }

                $userStatus = '<span class="badge badge-'.$type.'" style="cursor: pointer !important;">'.$leave['lRStatus'].'</span>';

                $leaves['data'][$key]['fullName'] = $leave['fullName'] ? ucfirst($leave['fullName']): '-';
                $leaves['data'][$key]['lType'] = $leave['lType'] ?? '-';
                $leaves['data'][$key]['fromDate'] = $leave['fromDateFormate'] ?? '-';
                $leaves['data'][$key]['toDate'] = $leave['toDateFormate'] ?? '-';
                $leaves['data'][$key]['createdAtFormate'] = $leave['createdAtFormate'];
                $leaves['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Leave Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $leaves['data'][$key]['lRStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="'.$status.'" data-id="'.$leave['id'].'" data-url="'.$userStatusRoute.'" class="btnChangeUserStatus">'.$userStatus.'</a>';
            }
            return response()->json($leaves);
        }
    }

    /**
     * Display a request pay slip.
     *
     * @return \Illuminate\Http\Response
     */

    public function requestPaySlip(Request $request)
    {
        $userNameList = RequestPayslips::selectRaw('users.id,users.fullName')
                        ->join('users','users.id','request_payslips.userId')
                        ->distinct('users.id')
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('request_payslips.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        return view('admin.leaves.request-pay-slip',compact('userNameList'));
    }

   /**
     * Search OR Sorting Request Pay Sleep (DataTable).
     *
     * @param Request $request
     *
     * @return json
     */

    public function requestPaySlipSearch(Request $request)
    {
        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = RequestPayslips::selectRaw('*')
                     ->selectRaw('users.fullName, request_payslips.id, DATE_FORMAT(request_payslips.createdAt, "' . config('constant.in_out_date_time_format') . '") as createdAtFormate,
                     DATE_FORMAT(request_payslips.rPDate, "' . config('constant.request_payslip_month_year') . '") as rPDate')
                     ->join('users', 'users.id', 'request_payslips.userId');

            // if(!empty($request->leave_type)){
            //     $query = $query->where('leave_requests.lType','=',$request->leave_type);
            // }

            $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('request_payslips.userId',  User::getMarketingAdminEmployee());
            });

            if(!empty($request->employeeType)){
                $query = $query->where('request_payslips.userId','=',$request->employeeType);
              }

            if(!empty($request->leave_status)){
                $query = $query->where('request_payslips.rPStatus','=',$request->leave_status);
            }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                ->orWhereRaw("DATE_FORMAT(request_payslips.rPDate, '" . config('constant.request_payslip_month_year') . "') like '%{$request->search['value']}%' ");
            });

            $leaves = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $leaves['recordsFiltered'] = $leaves['recordsTotal'] = $leaves['total'];

            foreach ($leaves['data'] as $key => $leave) {


                $leaves['data'][$key]['sr_no'] = $startNo + $key;


                $status = $leave['rPStatus'];
                $type = '';
                if($status == 'Pending')
                {
                    $type = 'info';
                }
                else if($status == 'Approved')
                {
                    $type = 'success';
                }else{
                    $type = 'danger';
                }

                $userStatus = '<span class="badge badge-'.$type.'" style="cursor: pointer !important;">'.$leave['rPStatus'].'</span>';

                $leaves['data'][$key]['fullName'] = $leave['fullName'] ? ucfirst($leave['fullName']): '-';
                $leaves['data'][$key]['rPStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="'.$status.'" data-id="'.$leave['id'].'"  class="btnChangeUserStatus">'.$userStatus.'</a>';
            }
            return response()->json($leaves);
        }
    }

    public function changeSlipStatus(Request $request)
    {
        $getRequestPaySlip = RequestPayslips::where('id',$request->id)->first();

        $paySlipPdf = PaySlip::where(['userId' => $request->id])
                ->whereYear('month', '=', Carbon::parse($getRequestPaySlip->rPDate)->format('Y'))
                ->whereMonth('month', '=', Carbon::parse($getRequestPaySlip->rPDate)->format('m'))
                ->first();

            if ($request->hasFile('payPdf')) {

                    if(empty($paySlipPdf))
                    {
                        $paySlipPdf = new PaySlip();
                    }

                    $paySlipPdf->userId = $getRequestPaySlip->userId;
                    $paySlipPdf->month = $getRequestPaySlip->rPDate;
                    $paySlipPdf->payPdf = ImageHelper::uploadImage($request->file('payPdf'), 'pay_slip');
                    $paySlipPdf->save();
            }

        $status = RequestPayslips::where('id',$request->id)->update(['rPStatus' => $request->requestStatus]);
        return redirect(route('requestPaySlip'))->with('success', 'Status updated successfully');
    }

    /**
     * Update  Status of the leave.
     * @param string $id
     *
     * @return Redirect
     */
    public function changeLeaveStatus($id, Request $request)
    {

        $leave = LeaveRequest::where('id',$request->id)->first();
        $leave->lRStatus = $request->requestStatus;
        $leave->adminRejectOtherText = $request->adminRejectOtherText ?? NULL;
        $leave->IRStatusUpdateUserBy = Auth::user()->id;

        if ($leave->save()) {

            if($leave->lRStatus != "Pending")
            {
                NotificationHelper::leaveNotification($leave);
            }

            return redirect()->back()->with('success', 'Status updated successfully');
            // return redirect(route('leaves.index'))->with('success', 'Status updated successfully');
        }
        return redirect(route('leaves.index'))->with('error', 'Request not found');
    }

   /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $leaveDetail = LeaveRequest::getSelectQuery()
        ->selectRaw('users.fullName, leave_requests.id, DATE_FORMAT(leave_requests.createdAt, "' . config('constant.in_out_date_time_format') . '") as createdAtFormate')
        ->join('users', 'users.id', 'leave_requests.userId')
        ->where('leave_requests.id',$id)
        ->first();

        if (!empty($leaveDetail)) {
          return view('admin.leaves.view',compact('leaveDetail'));
        }

        return redirect(route('leaves.index'))->with('error', trans('messages.leaves.not_found', ['module' => 'leave']));
    }

      /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $leave = LeaveRequest::where('id', $id)->first();
        if ($leave) {

            $leave->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'leave']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'leave']), 0);
    }
}
