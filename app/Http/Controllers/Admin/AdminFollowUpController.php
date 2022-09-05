<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminFollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userNameList = FollowUp::selectRaw('users.id,users.fullName,users.roleId')
                        ->join('users','users.id','follow_ups.userId')
                        ->distinct('users.id')
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('follow_ups.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        $dealerNameList = FollowUp::selectRaw('dealers.id,dealers.name')
                        ->join('dealers','dealers.id','follow_ups.rjDealerId')
                        ->join('users','users.id','follow_ups.userId')
                        ->distinct('dealers.id')
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('follow_ups.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('name ASC')
                        ->get();


        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $dealerNameList = $dealerNameList->unique('name')->whereNotNull('name');

        return view('admin.follow_up.list',compact('userNameList','dealerNameList'));
    }

     /**
     * Search OR Sorting Follow Up (DataTable).
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

            $query = FollowUp::getSelectQuery()->selectRaw('users.fullName')
                    ->join('users', 'users.id', 'follow_ups.userId');

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('follow_ups.userId',  User::getMarketingAdminEmployee());
            });

            if (!empty($request->userType)) {
                $query = $query->where('dealers.fType', '=', $request->userType);
            }

            if (!empty($request->employeeType)) {
                $query = $query->where('follow_ups.userId', '=', $request->employeeType);
            }

            if (!empty($request->dealerType)) {
                $query = $query->where('dealers.id', '=', $request->dealerType);
            }


            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%')
                    ->orWhereRaw("DATE_FORMAT(follow_ups.fDate, '" . config('constant.schedule_date_format') . "') like '%{$request->search['value']}%' ")
                    ->orWhereRaw("DATE_FORMAT(follow_ups.fTime, '" . config('constant.schedule_time_format') . "') like '%{$request->search['value']}%' ")
                    ->orWhereRaw("CONCAT(DATE_FORMAT(follow_ups.fReminder, '" . config('constant.schedule_date_format') . "') ,' | ',DATE_FORMAT(follow_ups.fReminder, '" . config('constant.schedule_time_format') . "')) like '%{$request->search['value']}%' ");
            });

            $complaints = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $complaints['recordsFiltered'] = $complaints['recordsTotal'] = $complaints['total'];


            foreach ($complaints['data'] as $key => $complaint) {

                $params = [
                    'followup' => $complaint['id'],
                ];

                $complaints['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('followups.show', $params);
                $deleteRoute = route('followups.destroy', $params);

                $complaints['data'][$key]['fullName'] = $complaint['fullName'] ? ucfirst($complaint['fullName']) : '-';
                $complaints['data'][$key]['name'] = $complaint['name'] ?? '-';

                $complaints['data'][$key]['fDateFormate'] = $complaint['fDateFormate']  ?? '-';
                $complaints['data'][$key]['fTimeFormate'] = $complaint['fTimeFormate']  ?? '-';
                $complaints['data'][$key]['rDateTimeFormate'] = $complaint['rDateTimeFormate']  ?? '-';


                $complaints['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Complaint Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $complaints['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
            }
            return response()->json($complaints);
        }
    }


     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */


    public function show($id)
    {
        $followUpDetails =  FollowUp::getSelectQuery()->selectRaw('users.fullName,users.mobileNumber,users.email')
                            ->join('users', 'users.id', 'follow_ups.userId')
                            ->where(['follow_ups.id' => $id])->first();

        if(empty($followUpDetails))
        {
            return redirect(route('followups.index'))->with('error', trans('messages.msg.not_found', ['module' => 'Follow Up']));
        }

        return view('admin.follow_up.view',compact('followUpDetails'));

    }
      /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $complaint = FollowUp::where('id', $id)->first();
        if ($complaint) {

            $complaint->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'Follow Up']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'Follow Up']), 0);
    }
}
