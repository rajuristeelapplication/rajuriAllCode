<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminLeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userNameList = Lead::selectRaw('users.id,users.fullName,users.roleId,pStateId,pSName,pCityId,pCName,pTalukaId,pTName')
                        ->join('users','users.id','leads.userId')
                        ->distinct('users.fullName')
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('leads.userId',  User::getMarketingAdminEmployee());
                        })
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->orderByRaw('fullName ASC')
                        ->get();


        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('pCityId')->where('pStateId',$request->s_name)->whereNotNull('pCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('pTalukaId')->where('pCityId',$request->cityId)->whereNotNull('pTName');
            }

            return response()->json($data);
        }
        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('pSName')->whereNotNull('pSName');
        $districtNameLists = $userNameList->unique('pCName')->whereNotNull('pCName');
        $talukaNameLists = $userNameList->unique('pTName')->whereNotNull('pTName');


        return view('admin.manageLead.list', compact('userNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }


    /**
     * Search OR Sorting Lead (DataTable).
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

            $query = Lead::getSelectQuery()
                    ->selectRaw('users.fullName as createdBy, leads.createdAt, leads.isActive, leads.lCompanyName, leads.lShopName, leads.lMobileNumber')
                    ->join('users', 'users.id', 'leads.userId')
                    ->when(!empty($request->cityId), function ($query) use ($request) {
                        return $query->where('leads.pCityId',  $request->cityId);
                    })
                    ->when(!empty($request->stateId), function ($query) use ($request) {
                        return $query->where('leads.pStateId',  $request->stateId);
                    })
                    ->when(!empty($request->talukaId), function ($query) use ($request) {
                        return $query->where('leads.pTalukaId',  $request->talukaId);
                    })
                    ->when(!empty($request->moveStatus), function ($query) use ($request) {
                        if($request->moveStatus == "Pending")
                        {
                            $query->where('leads.moveStatus',  'Pending');
                        }else{
                            $query->whereIn('leads.moveStatus', ['Sales','Dealer']);
                        }

                    });

            if(!empty($request->leadType)){
                $query = $query->where('lType','=',$request->leadType);
            }

            if (!empty($request->employeeType)) {
                $query = $query->where('leads.userId', '=', $request->employeeType);
            }

             // Role Base Condition

             if(\Auth::user()->roleId == config('constant.ma_id'))
             {

                $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('leads.userId',  User::getMarketingAdminEmployee());
                });


                //  $query = $query->where('roleId','=',config('constant.marketing_executive_id'));
             }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.lType', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.lFullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.lCompanyName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.lShopName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.lMobileNumber', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.pSName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.pCName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('leads.pTName', 'like', '%' . $request->search['value'] . '%');
            });

            $leads = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $leads['recordsFiltered'] = $leads['recordsTotal'] = $leads['total'];

            foreach ($leads['data'] as $key => $lead) {

                $params = [
                    'leads' => $lead['id'],
                ];

                $leads['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('leads.show', $params);
                $statusRoute = route('leads.status', $params);
                $deleteRoute = route('leads.destroy', $params);

                $isActiveStatus = ($lead['isActive'] == 1) ? 'checked' : '';

                $leads['data'][$key]['createdBy'] = $lead['createdBy'] ? ucfirst($lead['createdBy']): '-';
                $leads['data'][$key]['lType'] = $lead['lType'] ?? '-';
                $leads['data'][$key]['lFullName'] = $lead['lFullName'] ?? '-';
                $leads['data'][$key]['lCompanyName'] = $lead['lCompanyName'] ?? '-';
                $leads['data'][$key]['lMobileNumber'] = $lead['lMobileNumber'] ?? '-';
                $leads['data'][$key]['createdAt'] = $lead['createdAt'];
                $leads['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $leads['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Lead Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $leads['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
            }
            return response()->json($leads);
        }
    }

    /**
     * Change status of the Lead.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $lead = Lead::find($id);

        if (empty($lead)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'lead']), 0);
        }

        $lead->isActive = !$lead->isActive;
        $status = '';
        if ($lead->save()) {
            $status = $lead->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'lead', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'lead', 'type' => $status]), 0);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $leadDetail = Lead::leadDetails()->where('leads.id',$id)
                    ->selectRaw('users.fullName as createdBy')
                    ->join('users', 'users.id', 'leads.userId')
                    ->first();


        $materialTypeData  = !empty($leadDetail->materialType) ? json_decode($leadDetail->materialType): [];


        if (!empty($leadDetail)) {
          return view('admin.manageLead.view',compact('leadDetail', 'materialTypeData'));
        }

        return redirect(route('leads.index'))->with('error', trans('messages.lead.not_found', ['module' => 'lead']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */
    public function destroy($id)
    {
        $lead = Lead::where('id', $id)->first();
        if ($lead) {


            $imageName = config('constant.dealer_image') .'/'.$lead->attachmentImage;
            User::bucketRemoveImage($imageName);


            $imageName1 = config('constant.dealer_image') .'/'.$lead->panImage;
            User::bucketRemoveImage($imageName1);

            $lead->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'lead']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'lead']), 0);
    }
}
