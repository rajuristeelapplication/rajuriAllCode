<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ExpenseType;
use App\Models\Reimbursement;
use App\Models\ReimbursementImage;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class AdminReimbursementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getExpense = ExpenseType::where('type','Reimbursement')->get(['id','eName']);

        $userNameList = Reimbursement::selectRaw('users.id,users.fullName')
                        ->join('users','users.id','reimbursements.userId')
                        ->distinct('reimbursements.userId')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        return view('admin.reimbursements.list', compact('getExpense','userNameList'));
    }

     /**
     * Search OR Sorting Reimbursement (DataTable).
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

            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = Reimbursement::getSelectQuery()
                     ->selectRaw('users.fullName, reimbursements.id, reimbursements.rName,users.roleId,roles.roleName')
                     ->join('users', 'users.id', 'reimbursements.userId')
                     ->join('roles', 'roles.id', 'users.roleId');


            if(!empty($request->expense_type)){
                $query = $query->where('reimbursements.expenseId','=',$request->expense_type);
            }

            if(!empty($request->reimbursement_status)){
                $query = $query->where('reimbursements.rStatus','=',$request->reimbursement_status);
            }

            if(!empty($request->userType))
            {
                $query =  $query->where('users.roleId',$request->userType);
            }

            if(!empty($request->userId))
            {
                $query =  $query->where('users.id',$request->userId);
            }

            if(!empty($startDate) && !empty($endDate)){

                $query =  $query->whereDate('reimbursements.createdAt', '>=', $startDate)
                ->whereDate('reimbursements.createdAt', '<=', $endDate);

                // $query = $query->where('dealers.dob','=',$request->dob);
            }
            $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
            });


            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('reimbursements.rName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('reimbursements.rStatus', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('expense_types.eName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.mobileNumber', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('reimbursements.description', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('reimbursements.totalAmount', 'like', '%' . $request->search['value'] . '%')
                    ->orWhereRaw('DATE_FORMAT(reimbursements.dateOfTravelling, "' . config('constant.schedule_date_time_format') . '") like "%' . $request->search['value'] . '%"')
                    ->orWhereRaw('DATE_FORMAT(reimbursements.createdAt, "' . config('constant.schedule_date_format') . '") like "%' . $request->search['value'] . '%"');
            });

            $reimbursements = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $reimbursements['recordsFiltered'] = $reimbursements['recordsTotal'] = $reimbursements['total'];

            foreach ($reimbursements['data'] as $key => $reimbursement) {

                $params = [
                    'reimbursements' => $reimbursement['id'],
                ];

                $reimbursements['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('reimbursements.show', $params);
                $userStatusRoute = route('reimbursements.reimbursementsStatus', $params);
                $deleteRoute = route('reimbursements.destroy', $params);


                $status = $reimbursement['rStatus'];
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

                $userStatus = '<span class="badge badge-'.$type.'" style="cursor: pointer !important;">'.$reimbursement['rStatus'].'</span>';

                $reimbursements['data'][$key]['description'] = !empty($reimbursement['description']) ? '<span title="'.$reimbursement['description'].'">'. \Str::limit(strip_tags($reimbursement['description']), 21, $end = '...').'</span>' : '-';

                $reimbursements['data'][$key]['fullName'] = $reimbursement['fullName'] ? ucfirst($reimbursement['fullName']): '-';
                $reimbursements['data'][$key]['expenseId'] = $reimbursement['eName'] ?? '-';
                $reimbursements['data'][$key]['rName'] = $reimbursement['rName'] ?? '-';
                $reimbursements['data'][$key]['dateOfTravelling'] = $reimbursement['dateOfTravellingFormate'] ?? '-';
                $reimbursements['data'][$key]['dateOfCreateAtFormate'] = $reimbursement['dateOfCreateAtFormate'];
                $reimbursements['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Reimbursement Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $reimbursements['data'][$key]['rStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="'.$status.'" data-id="'.$reimbursement['id'].'" data-url="'.$userStatusRoute.'" class="btnChangeUserStatus">'.$userStatus.'</a>';
            }
            return response()->json($reimbursements);
        }
    }

    /**
     * Update  Status of the reimbursements.
     *
     * @param string $id
     *
     * @return Redirect
     */
    public function changeReimbursementsStatus($id, Request $request)
    {
        $reimbursement = Reimbursement::where('id',$request->id)->first();
        $reimbursement->rStatus = $request->requestStatus;
        $reimbursement->rStatusUpdateUserBy = Auth::user()->id;

        if ($reimbursement->save()) {

            NotificationHelper::reimbursementNotification($reimbursement);

            return redirect()->back()->with('success', 'Status updated successfully');

            // return redirect(route('reimbursements.index'))->with('success', 'Status updated successfully');
        }
        return redirect(route('reimbursements.index'))->with('error', 'Request not found');
    }


     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $reimbursementDetail = Reimbursement::getSelectQuery()
        ->selectRaw('users.fullName, reimbursements.id, reimbursements.rName, reimbursements.description')
        ->join('users', 'users.id', 'reimbursements.userId')
        ->where('reimbursements.id',$id)
        ->first();

        if (!empty($reimbursementDetail)) {
          return view('admin.reimbursements.view',compact('reimbursementDetail'));
        }

        return redirect(route('reimbursements.index'))->with('error', trans('messages.reimbursement.not_found', ['module' => 'reimbursement']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $reimbursement = Reimbursement::where('id', $id)->first();
        if ($reimbursement) {

            $reimbursementImage  = ReimbursementImage::where('reimbursementsId',$id)->get();

            if(count($reimbursementImage) > 0)
            {
                foreach($reimbursementImage as $img)
                {
                    $imageName1 = config('constant.reimbursement_image') .'/'.$img->rAttachment;
                    User::bucketRemoveImage($imageName1);
                }
            }

            $reimbursement->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'reimbursement']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'reimbursement']), 0);
    }
}
