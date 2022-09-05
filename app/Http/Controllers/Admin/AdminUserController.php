<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cities;
use App\Models\PaySlip;
use App\Models\LeaveRequest;
use App\Models\MarketingAdminEmployees;
use App\Helpers\ImageHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FirestoreHelper;
use App\Helpers\UtilityHelper;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Jobs\SendRegisterUserPasswordJob;
use App\Jobs\SendRegisterAdminPasswordJob;
use App\Models\Complaint;
use App\Models\Dealer;
use App\Models\InOut;
use App\Models\Knowledge;
use App\Models\Lead;
use App\Models\Merchandise;
use App\Models\ReimbursementImage;
use App\Models\Schedule;

class AdminUserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     *  $parameter
     *   all => All User,
     *   hr => All Hr,
     *   ma => All Marketing Admin
     *
     * @return \Illuminate\Http\Response
     */
    public function index($parameter = "all")
    {
        return view('admin.users.user_list',['parameter' => $parameter]);
    }

    /**
     * Search OR Sorting User (DataTable).
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


            $parameter = $request->parameter;


            $query = User::getUserCommonQuery();

            if($parameter == "all")
            {
                $query = $query->where('roleId','!=',config('constant.admin_id'));
                $query = $query->where('roleId','!=',config('constant.hr_id'));
                $query = $query->where('roleId','!=',config('constant.ma_id'));
            }

            if($parameter == "hr")
            {
                $query = $query->where('roleId',config('constant.hr_id'));
            }

            if($parameter == "ma")
            {
                $query = $query->where('roleId',config('constant.ma_id'));
                // $query = $query->where('roleId',config('constant.ma_id'));
            }

            if(!empty($request->userType)){
                $query = $query->where('roleId','=',$request->userType);
            }

            if(!empty($request->userStatus)){
                $query = $query->where('userStatus','=',$request->userStatus);
            }

            if(Auth::user()->roleId == config('constant.ma_id'))
            {
                $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('users.id',  User::getMarketingAdminEmployee());
                });

                // $query = $query->where('roleId','=',config('constant.marketing_executive_id'));
            }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('mobileNumber', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('email', 'like', '%' . $request->search['value'] . '%');
            });

            $users = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $users['recordsFiltered'] = $users['recordsTotal'] = $users['total'];

            foreach ($users['data'] as $key => $user) {

                $params = [
                    'users' => $user['id'],
                ];

                $users['data'][$key]['sr_no'] = $startNo + $key;


                $viewRoute    = route('users.show',  ['users' => $user['id'], 'parameter' => $parameter]);
                $editRoute    = route('users.edit', ['users' => $user['id'], 'editType' => 'credit']);
                $editPdfRoute = route('users.edit', ['users' => $user['id'], 'editType' => 'pdf']);
                $editUserRoute = route('users.editSalesMarketingUser', ['users' => $user['id']]);
                $statusRoute  = route('users.status', $params);
                $userStatusRoute = route('users.userStatus', $params);
                $deleteRoute  = route('users.destroy', $params);

                $editRoute1    = route('users.edit.param', ['parameter' => $parameter,'id' => $user['id']]);

                $chatRoute = route('chat', ['otherUserId' => $user['id']]);

                if($user['roleId'] == config('constant.sales_executive_id')){
                    $roleName = "Sales Executive";
                }

                if($user['roleId'] == config('constant.marketing_executive_id')){
                    $roleName = "Marketing Executive";
                }

                $status = $user['userStatus'];
                $type = '';
                if($status == 'Pending')
                {
                    $type = 'info';
                }
                else if($status == 'Approved')
                {
                    $type = 'success';
                }

                $userStatus = '<span class="badge badge-'.$type.'" style="cursor: pointer !important;">'.$user['userStatus'].'</span>';

                $isActiveStatus = ($user['isActive'] == 1) ? 'checked' : '';

                $users['data'][$key]['fullName'] = $user['fullName'] ? ucfirst($user['fullName']): '-';
                $users['data'][$key]['email'] = $user['email'] ?? '-';
                $users['data'][$key]['mobileNumber'] = $user['mobileNumber'] ? $user['mobileNumber'] : '-';
                $users['data'][$key]['roleId'] = $roleName ?? '-';
                $users['data'][$key]['createdAt'] = (!empty($user['createdAt']) ? Carbon::parse($user['createdAt'])->format('d-M-Y') : '-');

                $users['data'][$key]['action']   = '';

                if($parameter == "all")
                {

                $users['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="User Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';

                }
                if($parameter == "all")
                {
                    if (Auth::user()->roleId != config('constant.hr_id')) {

                        $users['data'][$key]['action'] .= '<a href="' . $editUserRoute . '" class="btn btn-secondary" title="Edit User"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                    }
                    $users['data'][$key]['action'] .= '<a href="' . $editPdfRoute . '" class="btn btn-dark" title="Pay Slip PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>&nbsp&nbsp';
                    $users['data'][$key]['action'] .= '<a href="' . $editRoute . '" class="btn btn-success" title="Credit Leave">Credit Leave</a>&nbsp&nbsp';
                }
                else{
                    $users['data'][$key]['action'] .= '<a href="' . $editRoute1 . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                }

                if (Auth::user()->roleId != config('constant.hr_id')) {
                    $users['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
                }

                if($parameter == "all")
                {

                $users['data'][$key]['action'] .= '<a target="_blank" href="' . $chatRoute . '" class="btn btn-success" title="Chat"><i class="feather feather-message-circle block mx-auto"></i></a>&nbsp&nbsp';

                }
                $users['data'][$key]['userStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="'.$status.'" data-id="'.$user['id'].'" data-url="'.$userStatusRoute.'" class="btnChangeUserStatus">'.$userStatus.'</a>';
                $users['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
            }
            return response()->json($users);
        }
    }

      /**
     * Show the form for creating a User (Sales & Marketing).
     *
     * @return View
     */
    public function create()
    {
        $city = Cities::where('isActive', 1)->get();

        return view('admin.users.create_users_sales_marketing', compact('city'));
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */


    public function editSalesMarketingUser(Request $request)
    {

        $userId =   $request->users;

        $where[] = ['users.id', $userId];
        $userDetail = User::getUser($where);

        // echo "<pre>";
        // print_r($userDetail);
        // exit;
        $city = Cities::where('isActive', 1)->get();

        if(empty($userDetail))
        {
            return redirect()->back()->with('error', 'Request not found');
        }

        return view('admin.users.create_users_sales_marketing', compact('userDetail', 'city'));
    }


     /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function insertUpdateSalesMarketingUser(Request $request)
    {
        $id = $request->id;
        $countryCode = "+91";

        $user = new User();

        if(!empty($id))
        {
            $user = User::where('id',$id)->first();
        }else{
            $user->userRandomId = UtilityHelper::generateUniqueCode('users', 'userRandomId');
        }

        $user->fill($request->all());
        $user->roleId = $request->roleId;
        $user->fullName = $request->firstName . ' ' . $request->lastName;
        $user->countryCodeMobileNumber = $countryCode.$request->mobileNumber;
        $user->userStatus = "Approved";

        if ($request->hasFile('profileImage')) {

            $user->profileImage = ImageHelper::uploadImage($request->file('profileImage'), 'profile');
        }

        if ($request->hasFile('photoIdProof')) {

            $user->photoIdProof = ImageHelper::uploadImage($request->file('photoIdProof'), 'photoid');
        }


        if($user->save())
        {
            if (empty($id)) {
                dispatch(new SendRegisterUserPasswordJob($user->id));
            }

            try {
                $where[] = ['users.id', $user->id];
                $userDetail = User::getUser($where);
                FirestoreHelper::firestoreUserCreate($userDetail);
               } catch (\Exception $e) {
                   return $this->toJson([], $e->getMessage(), 0);
               }


            return redirect()->route('users.index','all')->with('success', trans('messages.msg.updated.success', ['module' => 'user', 'type' => '']), 1);
        }

            return $this->toJson([], trans('messages.msg.user.error', ['module' => 'user', 'type' => '']), 0);
    }

    /**
     * Change status of the user.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'user']), 0);
        }

        $user->isActive = !$user->isActive;
        $status = '';
        if ($user->save()) {
            $status = $user->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'user', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'user', 'type' => $status]), 0);
    }

    /**
     * User Status update.
     *
     * @param string $id
     *
     * @return Redirect
     */
    public function changeUserStatus($id, Request $request)
    {

        $user = User::where('id',$request->id)->first();
        $user->userStatus = $request->requestStatus;
        $user->uStatusUpdateUserBy = Auth::user()->id;


        if($request->requestStatus == "Approved"){

            $randomPassword = CommonHelper::randomPassword();
            // $user->password  = bcrypt($randomPassword);

            dispatch(new SendRegisterUserPasswordJob($user->id));
        }
        if ($user->save()) {

            return redirect()->back()->with('success', 'Status updated successfully');
            // return redirect(route('users.index'))->with('success', 'Status updated successfully');
        }
        return redirect(route('users.index'))->with('error', 'Request not found');
    }

    /**
     * Show the form for creating a new resource. (User)
     *
     * @return \Illuminate\Http\Response
     */
    public function createDiffUser(Request $request,$parameter)
    {
        $meEmployees = User::selectRaw('id,fullName')->whereIn('roleId',[config('constant.marketing_executive_id'),config('constant.sales_executive_id')])->get();

        return view('admin.users.create_users',['admin' => '','parameter' => $parameter,'meEmployees' => $meEmployees,'selectedMeEmployees' => []]);
    }
      /**
     * Show the form for editing the specified resource. (User)
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function editDiffUser(Request $request)
    {
        $userId =   $request->id;

        $where[] = ['users.id', $userId];
        $admin = User::getUser($where);

        if(empty($admin))
        {
            return redirect()->back()->with('error', 'Request not found');
        }

        $roleName =  $admin->roleId == config('constant.hr_id') ? 'hr':'ma';

        $meEmployees = User::selectRaw('id,fullName')->whereIn('roleId',[config('constant.marketing_executive_id'),config('constant.sales_executive_id')])->get();

        $selectedMeEmployees = MarketingAdminEmployees::where('mAdminId',$admin->id)->pluck('employeeId')->toArray();


        return view('admin.users.create_users',['admin' => $admin,'parameter' => $roleName,'meEmployees' => $meEmployees,'selectedMeEmployees' => $selectedMeEmployees]);
    }

     /**
     * Store or Update a newly created resource in storage. (User)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function diffUserInsertUpdate(Request $request)
    {

        $maEmployessAssign = $request->maEmployessAssign;


        $parameters = $request->parameter;
        $countryCode = "+91";

        $id = $request->id;

        $user = new User();

        if(!empty($id))
        {
            $user = User::where('id',$id)->first();
        }else{
            $user->userRandomId = UtilityHelper::generateUniqueCode('users', 'userRandomId');
        }



        $user->fill($request->all());
        $user->roleId = $request->parameter == "hr" ? config('constant.hr_id') : config('constant.ma_id');
        $user->fullName = $request->firstName . ' ' . $request->lastName;
        $user->countryCodeMobileNumber = $countryCode.$request->mobileNumber;

        if ($request->hasFile('profileImage')) {

            $user->profileImage = ImageHelper::uploadImage($request->file('profileImage'), 'profile');
        }

        if (empty($request->id)) {

            $user->password = bcrypt($request->password);

            $siteUrl = "";
            if ($request->parameter == "hr") {
                $siteUrl = "hr/login";
            }
            if ($request->parameter == "ma") {
                $siteUrl = "marketing-admin/login";
            }
        }

        if($user->save())
        {
            if (empty($request->id)) {
                //Send Mail To Sub Admin
                dispatch(new SendRegisterAdminPasswordJob($user->id, $request->password, $siteUrl));
            }

            if($request->parameter == "ma")
            {
                if(!empty($request->parameter))
                {
                    MarketingAdminEmployees::where('mAdminId',$user->id)->delete();

                    if(!empty($maEmployessAssign))
                    {
                        foreach($maEmployessAssign as $key=>$value)
                        {
                            $create = new MarketingAdminEmployees();
                            $create->mAdminId = $user->id;
                            $create->employeeId = $value;
                            $create->save();
                        }
                    }


                }
            }

            try {
                $where[] = ['users.id', $user->id];
                $userDetail = User::getUser($where);
                FirestoreHelper::firestoreUserCreate($userDetail);
               } catch (\Exception $e) {
                   return $this->toJson([], $e->getMessage(), 0);
               }

            return redirect()->route('users.index',['parameter' => $parameters])->with('success', trans('messages.msg.updated.success', ['module' => $request->parameter]));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($roleName,$id)
    {
        $userDetail = User::getUserCommonQuery()->where('users.id',$id)->with('cities')->first();


        $paySlipPdf = PaySlip::getSelectQuery()->selectRaw('DATE_FORMAT(payslips.month, "' . config('constant.payslip_month_year_admin') . '") as month')->where(['userId' => $id])->get();

        if (!empty($userDetail)) {
          return view('admin.users.user_view',compact('userDetail', 'paySlipPdf','roleName'));
        }
        return redirect(route('users.index'))->with('error', trans('messages.users.not_found', ['module' => 'user']));
    }


    /**
     * Search leaves.
     *
     * @param Request $request
     *
     * @return json
     */
    public function searchLeave(Request $request)
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
                    //  ->where('leave_requests.lRStatus', '!=', 'Credit')
                     ->where('leave_requests.userId',$request->userId);


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
                // $editRoute   = route('leaves.edit', $params);
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
                // $leaves['data'][$key]['action'] .= '<a a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                // $leaves['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
                $leaves['data'][$key]['lRStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="'.$status.'" data-id="'.$leave['id'].'" data-url="'.$userStatusRoute.'" class="btnChangeUserStatus">'.$userStatus.'</a>';
            }
            return response()->json($leaves);
        }
    }


    /**
     * Show the form for editing the specified resource. (User Leave)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $userDetail = User::where('id', $id)->first();


        $totalRemainingLeaveBalance = User::where('id',$id)->first(['totalCasualLeave','totalMedicalLeave']);

        $paySlipPdf = PaySlip::getSelectQuery()->where(['userId' => $id])
            ->whereYear('month', '=', date('Y'))
            ->whereMonth('month', '=', date('m'))
            ->first();

        $editType = $request->editType;
        return view('admin.users.create', compact('userDetail', 'editType', 'paySlipPdf', 'totalRemainingLeaveBalance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        // dd($request->all());
        $action = "";
        if (($request->editType == 'credit') && ($request->casualLeave != 0) && ($request->medicalLeave != 0)) {

            if(isset($request->casualLeave))
            {
                // $message = trans('messages.msg.updated.success', ['module' => 'leave']);
                // $action = 'Edit';
                // $casualLeave = LeaveRequest::where(['userId' => $request->id ,'lType' => 'Casual Leave'])
                // ->whereYear('createdAt', '=', date('Y'))
                // ->whereMonth('createdAt', '=', date('m'))
                // ->first();

                //     if(empty($casualLeave))
                //     {
                        $action = 'Add';
                        $message = trans('messages.msg.created.success', ['module' => 'leave']);

                        $casualLeave = new LeaveRequest();
                //     }

                    $casualLeave->noOfLeave = $request->casualLeave;
                    $casualLeave->userId = $request->id;
                    $casualLeave->lType = 'Casual Leave';
                    $casualLeave->lRStatus = 'Credit';
                    $casualLeave->save();
            }


            if(isset($request->medicalLeave))
            {
                // $message = trans('messages.msg.updated.success', ['module' => 'leave']);
                // $action = 'Edit';

                // $medicalLeave = LeaveRequest::where(['userId' => $request->id ,'lType' => 'Medical Leave'])
                // ->whereYear('createdAt', '=', date('Y'))
                // ->whereMonth('createdAt', '=', date('m'))
                // ->first();

                //     if(empty($medicalLeave))
                //     {
                        $action = 'Add';
                        $message = trans('messages.msg.created.success', ['module' => 'leave']);

                        $medicalLeave = new LeaveRequest();
                    // }

                    $medicalLeave->noOfLeave = $request->medicalLeave;
                    $medicalLeave->userId = $request->id;
                    $medicalLeave->lType = 'Medical Leave';
                    $medicalLeave->lRStatus = 'Credit';
                    $medicalLeave->save();
            }

            if ($casualLeave || $medicalLeave) {

                return redirect()->back()->with('success', $message);
            }

        }

        if ($request->editType == 'pdf') {

            $message = trans('messages.msg.updated.success', ['module' => 'pay slip pdf']);
            $action = 'Edit';

            $paySlipPdf = PaySlip::where(['userId' => $request->id])
            ->whereYear('month', '=', Carbon::parse($request->month)->format('Y'))
            ->whereMonth('month', '=', Carbon::parse($request->month)->format('m'))
            ->first();

                if(empty($paySlipPdf))
                {
                    $action = 'Add';
                    $message = trans('messages.msg.created.success', ['module' => 'pay slip pdf']);

                    $paySlipPdf = new PaySlip();

                }

                $paySlipPdf->userId = $request->id;
                $paySlipPdf->month = Carbon::parse($request->month)->format('Y-m-01');
                if ($request->hasFile('payPdf')) {

                    $paySlipPdf->payPdf = ImageHelper::uploadImage($request->file('payPdf'), 'pay_slip');
                }

                $paySlipPdf->save();

                if ($paySlipPdf) {

                    return redirect()->route('users.index')->with('success', $message);
                }
        }

        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->back()->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'user leave']), 0);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();

        if ($user) {

            $imageName = config('constant.profile_image') .'/'.$user->profileImage;
            User::bucketRemoveImage($imageName);

            $imageName1 = config('constant.photo_id_image') .'/'.$user->photoIdProof;
            User::bucketRemoveImage($imageName1);

            $user->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'user']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'user']), 0);
    }

    /**
     * Search PDF.
     *
     * @param Request $request
     *
     * @return json
     */
    public function pdfSearch(Request $request)
    {
        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = PaySlip::getSelectQuery()->selectRaw('DATE_FORMAT(payslips.month, "' . config('constant.payslip_month_year_admin') . '") as month')->where(['userId' => $request->userId]);

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $users = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $users['recordsFiltered'] = $users['recordsTotal'] = $users['total'];

            foreach ($users['data'] as $key => $user) {

                $params = [
                    'users' => $user['id'],
                ];

                $users['data'][$key]['sr_no'] = $startNo + $key;

                $deleteRoute  = route('users.deletePdf', $params);

                $users['data'][$key]['month'] = $user['month'] ? ucfirst($user['month']): '-';
                $users['data'][$key]['payPdf'] = '<a href="' . $user['payPdf']. '" class="btn" target="__blank" title="View PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';

                // $users['data'][$key]['createdAt'] = (!empty($user['createdAt']) ? Carbon::parse($user['createdAt'])->format('d-M-Y') : '-');

                $users['data'][$key]['action'] = '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
            }
            return response()->json($users);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */
    public function deletePdf($id)
    {
        $pdf = PaySlip::where('id', $id)->first();
        if ($pdf) {

            $pdf->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'pdf']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'pdf']), 0);
    }

    /**
     * Image Delete.
     *
     * @param  string  $id
     * @param  string  $type
     *
     */


    public function deleteImage(Request $request)
    {

        $id = $request->id;
        $type = $request->type;
        $classHide = '';

        switch ($type) {
            case "photoid":

                $user = User::where('id', $id)->first();
                $user->photoIdProof = NULL;
                $user->save();
                $imageName1 = config('constant.photo_id_image') .'/'.$user->photoIdProof;
                User::bucketRemoveImage($imageName1);

                $classHide = 'photoIdClass';

              break;
            case "profileImage":
                $user = User::where('id', $id)->first();
                $user->profileImage = NULL;
                $user->save();
                $imageName1 = config('constant.profile_image') .'/'.$user->profileImage;
                User::bucketRemoveImage($imageName1);

                $classHide = 'profileClass';

              break;
            case "inAttendande":

                $inOut = InOut::where('id', $id)->first();
                $inOut->inReadingPhoto = NULL;
                $inOut->save();

                $imageName = config('constant.in_out_image') .'/'.$inOut->inReadingPhoto;
                User::bucketRemoveImage($imageName);

                $classHide = 'inAttendande';
              break;

            case "outAttendande":
                $inOut = InOut::where('id', $id)->first();
                $inOut->outReadingPhoto = NULL;
                $inOut->save();

                $imageName = config('constant.in_out_image') .'/'.$inOut->outReadingPhoto;
                User::bucketRemoveImage($imageName);

                $classHide = 'outAttendande';
                break;

            case "reimbursementsImage":
                    $reimbursementImage  = ReimbursementImage::where('id',$id)->first();
                    $imageName = config('constant.in_out_image') .'/'.$reimbursementImage->rAttachment;
                    User::bucketRemoveImage($imageName);
                    $reimbursementImage->delete();
                    $classHide = 'reimbursementsImage_'.$id;
                    break;
            default:

            case "dealerShopePhoto":
                $dealer = Dealer::where('id', $id)->first();

                $imageName = config('constant.dealer_image') .'/'.$dealer->shopPhoto;
                User::bucketRemoveImage($imageName);

                $dealer->shopPhoto = NULL;
                $dealer->save();

                $classHide = 'dealerShopePhoto';
                break;

            case "dealerPhoto":
                    $dealer = Dealer::where('id', $id)->first();

                    $imageName = config('constant.dealer_image') .'/'.$dealer->photo;
                    User::bucketRemoveImage($imageName);

                    $dealer->photo = NULL;
                    $dealer->save();

                    $classHide = 'dealerPhoto';
                    break;

            case "panImage":
                                 $lead = Lead::where('id', $id)->first();

                        $imageName = config('constant.dealer_image') .'/'.$lead->panImage;
                        User::bucketRemoveImage($imageName);

                        $lead->panImage = NULL;
                        $lead->save();

                        $classHide = 'panImage';
                        break;

            case "attachmentImage":
                            $lead = Lead::where('id', $id)->first();

                            $imageName = config('constant.dealer_image') .'/'.$lead->attachmentImage;
                            User::bucketRemoveImage($imageName);

                            $lead->attachmentImage = NULL;
                            $lead->save();

                            $classHide = 'attachmentImage';
                            break;

            case "complain":
                             $complaint = Complaint::where('id', $id)->first();

                             $imageName = config('constant.complaint_image') .'/'.$complaint->cPhotoVideo;
                             User::bucketRemoveImage($imageName);

                                $complaint->cPhotoVideo = NULL;
                                $complaint->save();

                                $classHide = 'complain';
                                break;

            case "startMeterReadingPhoto":
                                    $knowledge = Knowledge::where('id', $id)->first();

                                    $imageName = config('constant.knowledge_image') .'/'.$knowledge->startMeterReadingPhoto;
                                    User::bucketRemoveImage($imageName);

                                    $knowledge->startMeterReadingPhoto = NULL;
                                    $knowledge->save();

                                    $classHide = 'startMeterReadingPhoto';
                                    break;

            case "endMeterReadingPhoto":
                                        $knowledge = Knowledge::where('id', $id)->first();

                                        $imageName = config('constant.knowledge_image') .'/'.$knowledge->endMeterReadingPhoto;
                                        User::bucketRemoveImage($imageName);

                                        $knowledge->endMeterReadingPhoto = NULL;
                                        $knowledge->save();

                                        $classHide = 'endMeterReadingPhoto';
                                        break;

            case "mPhoto":
                                            $merchandise = Merchandise::where('id', $id)->first();

                                            $imageName = config('constant.merchandises_image') .'/'.$merchandise->mPhoto;
                                            User::bucketRemoveImage($imageName);

                                            $merchandise->mPhoto = NULL;
                                            $merchandise->save();

                                            $classHide = 'mPhoto';
                                            break;

            case "uploadPhoto":
                                     $schedule = Schedule::where('id', $id)->first();

                                        $imageName = config('constant.schedule_image') .'/'.$schedule->uploadPhoto;
                                        User::bucketRemoveImage($imageName);

                                        $schedule->uploadPhoto = NULL;
                                        $schedule->save();

                                        $classHide = 'uploadPhoto';
                                        break;

            case "watermarkImage":
                                            $schedule = Schedule::where('id', $id)->first();

                                               $imageName = config('constant.schedule_image') .'/'.$schedule->watermarkImage;
                                               User::bucketRemoveImage($imageName);

                                               $schedule->watermarkImage = NULL;
                                               $schedule->save();

                                               $classHide = 'watermarkImage';
                                               break;

            case "voiceRecording":
                                                $schedule = Schedule::where('id', $id)->first();

                                                   $imageName = config('constant.schedule_image') .'/'.$schedule->voiceRecording;
                                                   User::bucketRemoveImage($imageName);

                                                   $schedule->voiceRecording = NULL;
                                                   $schedule->save();

                                                   $classHide = 'voiceRecording';
                                                   break;

            case "competitorActivitiesImage":
                                        $schedule = Schedule::where('id', $id)->first();

                                            $imageName = config('constant.schedule_image') .'/'.$schedule->competitorActivitiesImage;
                                            User::bucketRemoveImage($imageName);

                                            $schedule->competitorActivitiesImage = NULL;
                                            $schedule->save();

                                            $classHide = 'competitorActivitiesImage';
                                            break;

              echo "Your favorite color is neither red, blue, nor green!";
          }

          return $this->toJson(['type' => $type,'classHide' => $classHide], 'Image Delete Successfully', 1);

    }

}
