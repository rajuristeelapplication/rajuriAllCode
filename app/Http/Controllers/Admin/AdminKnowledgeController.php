<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Knowledge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;


class AdminKnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $userNameList = Knowledge::selectRaw('users.id,users.fullName,ksStateId,ksSName,ksCityId,ksCName,ksTalukaId,ksTName')
        ->join('users','users.id','knowledges.userId')
        ->distinct('users.fullName')
        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
            return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
        })
        // ->whereIn('roleId',User::whichUserLogin())
        ->orderByRaw('fullName ASC')
        ->get();


        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('ksCityId')->where('ksStateId',$request->s_name)->whereNotNull('ksCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('ksTalukaId')->where('ksCityId',$request->cityId)->whereNotNull('ksTName');
            }

            return response()->json($data);
        }


        $stateNameLists = $userNameList->unique('ksSName')->whereNotNull('ksSName');
        $districtNameLists = $userNameList->unique('ksCName')->whereNotNull('ksCName');
        $talukaNameLists = $userNameList->unique('ksTName')->whereNotNull('ksTName');
        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');

        $dealerNameList = Knowledge::selectRaw('dealers.id,dealers.name')
            ->join('dealers','dealers.id','knowledges.rjDealerId')
            ->join('users','users.id','knowledges.userId')
            ->distinct('knowledges.rjDealerId')
            // ->whereIn('roleId',User::whichUserLogin())
            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
            })
            ->orderByRaw('name ASC')
            ->get();


        return view('admin.knowledge.list',compact('userNameList','dealerNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }


    /**
     * Search OR Sorting Knowledge Center (DataTable).
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

            $query = Knowledge::getSelectQuery()->selectRaw('knowledges.createdAt, knowledges.kCurrentLocation, knowledges.isActive, users.fullName,ksCityId,ksTalukaId,ksStateId')
                ->join('users', 'users.id', 'knowledges.userId');


                $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
                });

                if (!empty($request->userType)) {
                    $query = $query->where('dealers.fType', '=', $request->userType);
                }

                if (!empty($request->employeeType)) {
                    $query = $query->where('knowledges.userId', '=', $request->employeeType);
                }

                if (!empty($request->dealerType)) {
                    $query = $query->where('dealers.id', '=', $request->dealerType);
                }

                if(!empty($request->cityId)){
                    $query = $query->where('knowledges.ksCityId','=',$request->cityId);
                }

                if(!empty($request->talukaId)){
                    $query = $query->where('knowledges.ksTalukaId','=',$request->talukaId);
                }
                if(!empty($request->stateId)){
                    $query = $query->where('knowledges.ksStateId','=',$request->stateId);
                }
                if(!empty($request->vehicleNumber1)){
                    $query = $query->where('knowledges.vehicleNumber','=',$request->vehicleNumber1);
                }



            if (!empty($request->selectStatus)) {
                $query = $query->where('kStatus', '=', $request->selectStatus);
            }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('knowledges.kCurrentLocation', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.dealerId', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('knowledges.kdate', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.fType', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('knowledges.ksSName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('knowledges.ksCName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('knowledges.ksTName', 'like', '%' . $request->search['value'] . '%');
            });

            $knowledgeCenter = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $knowledgeCenter['recordsFiltered'] = $knowledgeCenter['recordsTotal'] = $knowledgeCenter['total'];

            foreach ($knowledgeCenter['data'] as $key => $knowledge) {

                $params = [
                    'knowledge' => $knowledge['id'],
                ];

                $knowledgeCenter['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('knowledge.show', $params);
                $statusRoute = route('knowledge.status', $params);
                $userStatusRoute = route('knowledge.userStatus', $params);
                $deleteRoute = route('knowledge.destroy', $params);

                $status = $knowledge['kStatus'];
                $type = '';
                if ($status == 'Pending') {
                    $type = 'info';
                } else if ($status == 'Approved') {
                    $type = 'success';
                }

                $userStatus = '<span class="badge badge-' . $type . '" style="cursor: pointer !important;">' . $knowledge['kStatus'] . '</span>';

                $isActiveStatus = ($knowledge['isActive'] == 1) ? 'checked' : '';

                $knowledgeCenter['data'][$key]['fullName'] = $knowledge['fullName'] ? ucfirst($knowledge['fullName']) : '-';
                $knowledgeCenter['data'][$key]['fType'] = $knowledge['fType'] ?? '-';
                $knowledgeCenter['data'][$key]['name'] = $knowledge['name'] ?? '-';
                $knowledgeCenter['data'][$key]['dealerId'] = $knowledge['dealerId'] ?? '-';
                $knowledgeCenter['data'][$key]['kdateFormate'] = $knowledge['kdateFormate'].' '.$knowledge['ktimeFormate'] ?? '-';
                $knowledgeCenter['data'][$key]['createdAt'] = (!empty($knowledge['createdAt']) ? Carbon::parse($knowledge['createdAt'])->format('d-M-Y') : '-');
                $knowledgeCenter['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Knowledge Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';

                if($status == "Pending")
                {
                    $knowledgeCenter['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
                }

                $knowledgeCenter['data'][$key]['userStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="' . $status . '" data-id="' . $knowledge['id'] . '" data-url="' . $userStatusRoute . '" class="btnChangeUserStatus">' . $userStatus . '</a>';
                $knowledgeCenter['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
            }
            return response()->json($knowledgeCenter);
        }
    }

    /**
     * Change status of the Knowledge.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $knowledge = Knowledge::find($id);

        if (empty($knowledge)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'knowledge']), 0);
        }

        $knowledge->isActive = !$knowledge->isActive;
        $status = '';
        if ($knowledge->save()) {
            $status = $knowledge->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'knowledge', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'knowledge', 'type' => $status]), 0);
    }

    /**
     * Status Update a  Knowledge Center .
     *
     * @param string $id
     *
     * @return Redirect
     */
    public function changeUserStatus($id, Request $request)
    {
        $knowledge = Knowledge::where('id', $request->id)->first();
        $knowledge->kStatus = $request->requestStatus;
        $knowledge->vehicleNumber = $request->vehicleNumber;
        $knowledge->kStatusUpdateUserBy = Auth::user()->id;

        if ($knowledge->save()) {

            NotificationHelper::knowledgeNotification($knowledge);

            return redirect()->back()->with('success', 'Status updated successfully');

            // return redirect(route('knowledge.index'))->with('success', 'Status updated successfully');
        }
        return redirect(route('knowledge.index'))->with('error', 'Request not found');
    }

     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $knowledgeDetail = Knowledge::knowledgeDetails()->selectRaw('users.fullName, del.fType,del.dealerId,del.firmName,del.name,
        del.dealerId, DATE_FORMAT(knowledges.kdate, "' . config('constant.schedule_date_format') . '") as kdateFormate,
        DATE_FORMAT(knowledges.ktime, "' . config('constant.schedule_time_format') . '") as ktimeFormate')
            ->join('users', 'users.id', 'knowledges.userId')
            ->join('dealers as del', 'del.id', 'knowledges.rjDealerId')
            ->where('knowledges.id', $id)
            ->first();

        if (!empty($knowledgeDetail)) {
            return view('admin.knowledge.view', compact('knowledgeDetail'));
        }

        return redirect(route('knowledge.index'))->with('error', trans('messages.knowledge.not_found', ['module' => 'knowledge']));
    }

  /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $knowledge = Knowledge::where('id', $id)->first();
        if ($knowledge) {

            $imageName = config('constant.knowledge_image') .'/'.$knowledge->startMeterReadingPhoto;
            User::bucketRemoveImage($imageName);

            $imageName1 = config('constant.knowledge_image') .'/'.$knowledge->endMeterReadingPhoto;
            User::bucketRemoveImage($imageName1);

            $knowledge->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'knowledge']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'knowledge']), 0);
    }
}
