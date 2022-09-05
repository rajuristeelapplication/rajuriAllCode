<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class AdminComplaintController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $userNameList = Complaint::selectRaw('users.id,users.fullName,cStateId,cSName,cCityId,cCName,cTalukaId,cTName')
                        ->join('users','users.id','complaints.userId')
                        ->distinct('users.fullName')
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('complaints.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();


        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('cCName')->where('cStateId',$request->s_name)->whereNotNull('cCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('cTName')->where('cCityId',$request->cityId)->whereNotNull('cTName');
            }

            return response()->json($data);
        }
        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('cSName')->whereNotNull('cSName');
        $districtNameLists = $userNameList->unique('cCName')->whereNotNull('cCName');
        $talukaNameLists = $userNameList->unique('cTName')->whereNotNull('cTName');

        $dealerNameList = Complaint::selectRaw('dealers.id,dealers.name')
                        ->join('dealers','dealers.id','complaints.rjDealerId')
                        ->join('users','users.id','complaints.userId')
                        ->distinct('schedules.rjDealerId')
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('complaints.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('name ASC')
                        ->get();


        return view('admin.complaint.list',compact('userNameList','dealerNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }

     /**
     * Search OR Sorting Complaint (DataTable).
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

            $query = Complaint::getSelectQuery()->selectRaw('users.fullName, dealers.name, complaints.createdAt')
                ->join('users', 'users.id', 'complaints.userId')
                ->join('dealers', 'dealers.id', 'complaints.rjDealerId');


                if (!empty($request->userType)) {
                    $query = $query->where('dealers.fType', '=', $request->userType);
                }

                if (!empty($request->employeeType)) {
                    $query = $query->where('complaints.userId', '=', $request->employeeType);
                }

                if (!empty($request->dealerType)) {
                    $query = $query->where('dealers.id', '=', $request->dealerType);
                }


                if (!empty($request->complaintType)) {
                    $query = $query->where('complaints.cType','=',$request->complaintType);
                }

                if (!empty($request->selectStatus)) {
                    $query = $query->where('complaints.cStatus','=',$request->selectStatus);
                }


                if(!empty($request->cityId)){
                    $query = $query->where('complaints.cCityId','=',$request->cityId);
                }

                if(!empty($request->talukaId)){
                    $query = $query->where('complaints.cTalukaId','=',$request->talukaId);
                }
                if(!empty($request->stateId)){
                    $query = $query->where('complaints.cStateId','=',$request->stateId);
                }


                  // Role Base Condition

              if(\Auth::user()->roleId == config('constant.ma_id'))
              {
                //   $query = $query->where('roleId','=',config('constant.marketing_executive_id'));

                $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('complaints.userId',  User::getMarketingAdminEmployee());
                });
              }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('complaints.cType', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('complaints.cSName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('complaints.cCName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('complaints.cTName', 'like', '%' . $request->search['value'] . '%');
            });

            $complaints = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $complaints['recordsFiltered'] = $complaints['recordsTotal'] = $complaints['total'];

            foreach ($complaints['data'] as $key => $complaint) {

                $params = [
                    'complaints' => $complaint['id'],
                ];

                $complaints['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('complaints.show', $params);
                $statusRoute = route('complaints.status', $params);
                $complaintStatusRoute = route('complaints.userStatus', $params);
                $deleteRoute = route('complaints.destroy', $params);

                $status = $complaint['cStatus'];
                $type = '';
                if ($status == 'Pending') {
                    $type = 'info';
                } else if ($status == 'Solved') {
                    $type = 'success';
                }

                $userStatus = '<span class="badge badge-' . $type . '" style="cursor: pointer !important;">' . $complaint['cStatus'] . '</span>';
                $complaints['data'][$key]['fullName'] = $complaint['fullName'] ? ucfirst($complaint['fullName']) : '-';
                $complaints['data'][$key]['cType'] = $complaint['cType'] ?? '-';
                $complaints['data'][$key]['name'] = $complaint['name'] ?? '-';
                $complaints['data'][$key]['cDateFormate'] = $complaint['cDateFormate'].' '.$complaint['cTimeFormate'] ?? '-';
                $complaints['data'][$key]['createdAt'] = $complaint['createdAt']?? '-';
                $complaints['data'][$key]['cDateFormate'] = (!empty($complaint['cDateFormate']) ? $complaint['cDateFormate'].' '.$complaint['cTimeFormate'] : '-');
                $complaints['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Complaint Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';

                if($complaint['cStatus'] != "Solved")
                {
                    $complaints['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
                }



                $complaints['data'][$key]['userStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="' . $status . '" data-id="' . $complaint['id'] . '" data-url="' . $complaintStatusRoute . '" class="btnChangeUserStatus">' . $userStatus . '</a>';
            }
            return response()->json($complaints);
        }
    }

    /**
     * Change status of the complaint.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $complaint = Complaint::find($id);

        if (empty($complaint)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'complaint']), 0);
        }

        $complaint->isActive = !$complaint->isActive;
        $status = '';
        if ($complaint->save()) {
            $status = $complaint->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'complaint', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'complaint', 'type' => $status]), 0);
    }

    /**
     *  Status Update Of Complaint
     *
     * @param string $id
     *
     * @return Redirect
     */
    public function changeUserStatus($id, Request $request)
    {
        $complaint = Complaint::where('id', $request->id)->first();

        $complaint->cStatus = $request->requestStatus;
        $complaint->cStatusUpdateUserBy = Auth::user()->id;
        $complaint->cDesc = $request->cDesc ?? NULL;

        if ($complaint->save()) {

            NotificationHelper::complainNotification($complaint);

            return redirect()->back()->with('success', 'Status updated successfully');
            // return redirect(route('complaints.index'))->with('success', 'Status updated successfully');
        }
        return redirect(route('complaints.index'))->with('error', 'Request not found');
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $complaintDetail = Complaint::complaintDetails()
            ->selectRaw('users.fullName')
            ->join('users', 'users.id', 'complaints.userId')
            ->where('complaints.id', $id)
            ->first();

            $materialTypeData  = !empty($complaintDetail->materialType) ? json_decode($complaintDetail->materialType): [];

        if (!empty($complaintDetail)) {
            return view('admin.complaint.view', compact('complaintDetail', 'materialTypeData'));
        }

        return redirect(route('complaints.index'))->with('error', trans('messages.complaint.not_found', ['module' => 'complaint']));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $complaint = Complaint::where('id', $id)->first();
        if ($complaint) {

            $imageName = config('constant.complaint_image') .'/'.$complaint->cPhotoVideo;
            User::bucketRemoveImage($imageName);

            $complaint->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'complaint']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'complaint']), 0);
    }
}
