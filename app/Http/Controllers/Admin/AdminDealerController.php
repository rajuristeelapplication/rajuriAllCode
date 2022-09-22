<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Dealer;
use App\Models\State;
use App\Models\Cities;
use App\Models\Talukas;
use App\Models\Regions;
use App\Helpers\ImageHelper;
use App\Helpers\UtilityHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;


class AdminDealerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request,$type = 0)
    {
         $userNameList = Dealer::selectRaw('users.id,users.fullName,users.roleId')
                        ->join('users','users.id','dealers.userId')
                        ->distinct('users.fullName')
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        $dealerNameList = Dealer::selectRaw('dealers.id,dealers.name,dealers.stateId,dealers.sName,dealers.cityId,dealers.cName,dealers.talukaId,dealers.tName')
                        ->join('users','users.id','dealers.userId')
                        ->distinct('dealers.id')
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                        })
                        // ->whereIn('roleId',User::whichUserLogin())
                        ->orderByRaw('name ASC')
                        ->get();

        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $dealerNameList->unique('cityId')->where('stateId',$request->s_name)->whereNotNull('cName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $dealerNameList->unique('talukaId')->where('cityId',$request->cityId)->whereNotNull('tName');
            }

            return response()->json($data);
        }
        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $dealerNameList->unique('sName')->whereNotNull('sName');
        $talukaNameLists = $dealerNameList->unique('tName')->whereNotNull('tName');
        $districtNameLists = $dealerNameList->unique('cName')->whereNotNull('cName');

        return view('admin.manageDealer.list', compact('userNameList', 'dealerNameList','stateNameLists','talukaNameLists','districtNameLists','type'));
    }


     /**
     * Search OR Sorting Dealer (DataTable).
     *
     * @param Request $request
     *
     * @return json
     */

    public function search(Request $request)
    {
        if ($request->ajax()) {

            //  type 1 for dealer and 2 for other

            $type = $request->type;


            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = Dealer::dealerAndUsersDetails();

            if(!empty($request->formType) && $type == 2){
                $query = $query->where('fType','=',$request->formType);
            }

            if($type == 1)
            {
                $query = $query->where('fType','=', 'Dealer');
            }

            if($type == 2)
            {
                $query = $query->where('fType','!=', 'Dealer');
            }

            if(!empty($request->dealerType)){
                $query = $query->where('dType','=',$request->dealerType);
            }

            if(!empty($request->employeeType)){
                $query = $query->where('userId','=',$request->employeeType);
            }

            if(!empty($request->dealerNameType)){
                $query = $query->where('dealers.id','=',$request->dealerNameType);
            }

            if(!empty($request->cityId)){
                $query = $query->where('dealers.cityId','=',$request->cityId);
            }

            if(!empty($request->talukaId)){
                $query = $query->where('dealers.talukaId','=',$request->talukaId);
            }
            if(!empty($request->stateId)){
                $query = $query->where('dealers.stateId','=',$request->stateId);
            }
            if(!empty($request->dob) && !empty($request->dob1)){

                $dayMonth  = explode('-', $request->dob);

                $dobMonth = $dayMonth[0];
                $dobDay =  $dayMonth[1];

                $dayMonth1  = explode('-', $request->dob1);

                $dobMonth1 = $dayMonth1[0];
                $dobDay1 =  $dayMonth1[1];


                $query =  $query->whereMonth('dealers.dob', '>=', $dobMonth)
                                ->whereDay('dealers.dob', '>=', $dobDay)
                                ->whereMonth('dealers.dob', '<=', $dobMonth1)
                                ->whereDay('dealers.dob', '<=', $dobDay1);


            //  $query =  $query->whereDate('dealers.dob', '>=', $request->dob)
            //                ->whereDate('dealers.dob', '<=', $request->dob1);


                // $query =  $query->whereDate('dealers.dob', '>=', $request->dob)
                // ->whereDate('dealers.dob', '<=', $request->dob1);

                // $query = $query->where('dealers.dob','=',$request->dob);
            }

            if(!empty($request->dealerStatus)){
                $query = $query->where('dealers.statusDealers','=',$request->dealerStatus);
            }

            // Role Base Condition

            if(\Auth::user()->roleId == config('constant.ma_id'))
            {
                $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                });
                // $query = $query->where('roleId','=',config('constant.marketing_executive_id'));
            }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('name', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('fType', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dType', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.email', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.wpMobileNumber', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.sName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.cName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.tName', 'like', '%' . $request->search['value'] . '%');
            });

            $dealers = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $dealers['recordsFiltered'] = $dealers['recordsTotal'] = $dealers['total'];

            foreach ($dealers['data'] as $key => $dealer) {

                $params = [
                    'dealers' => $dealer['id'],
                ];

                $dealers['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('dealers.show', $params);
                 $editRoute   = route('dealers.edit', $params);
                $statusRoute = route('dealers.status', $params);
                $userStatusRoute = route('dealers.userStatus', $params);
                $deleteRoute = route('dealers.destroy', $params);

                $isActiveStatus = ($dealer['isActive'] == 1) ? 'checked' : '';

                $status = $dealer['statusDealers'];
                $type = '';
                if($status == 'Pending')
                {
                    $type = 'info';
                }
                else if($status == 'Approved')
                {
                    $type = 'success';
                }
                else if($status == 'InActive')
                {
                    $type = 'warning';
                }
                else{
                    $type = 'danger';
                }

                $statusDealers = '<span class="badge badge-'.$type.'" style="cursor: pointer !important;">'.$dealer['statusDealers'].'</span>';

                $dealers['data'][$key]['createdBy'] = $dealer['createdBy'] ? ucfirst($dealer['createdBy']): '-';
                $dealers['data'][$key]['name'] = $dealer['name'] ?? '-';
                $dealers['data'][$key]['fType'] = $dealer['fType'] ?? '-';
                $dealers['data'][$key]['dType'] = $dealer['dType'] ?? '-';
                $dealers['data'][$key]['email'] = $dealer['email'] ?? '-';
                $dealers['data'][$key]['mobileNumber'] = $dealer['mobileNumber'] ?? '-';
                $dealers['data'][$key]['createdAt'] = $dealer['createdAt'];
                $dealers['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $dealers['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Dealer Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $dealers['data'][$key]['action'] .= '<a a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $dealers['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
                $dealers['data'][$key]['statusDealers'] = '<a id="statusVal" href="javascript:void(0);" data-st="'.$status.'" data-id="'.$dealer['id'].'" data-url="'.$userStatusRoute.'" class="btnChangeUserStatus">'.$statusDealers.'</a>';
            }
            return response()->json($dealers);
        }
    }

    /**
     * Change status of the dealer.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $dealer = Dealer::find($id);

        if (empty($dealer)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'dealer']), 0);
        }

        $dealer->isActive = !$dealer->isActive;
        $status = '';
        if ($dealer->save()) {
            $status = $dealer->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'dealer', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'dealer', 'type' => $status]), 0);
    }

    /**
     * Update Dealer Status.
     *
     * @param string $id
     *
     * @return Redirect
     */
    public function changeUserStatus($id, Request $request)
    {
        $dealer = Dealer::where('id',$request->id)->first();
        $dealer->statusDealers = $request->requestStatus;

        if ($dealer->save()) {

            return redirect()->back()->with('success', 'Status updated successfully');
            // return redirect(route('dealers.index'))->with('success', 'Status updated successfully');
        }
        return redirect(route('dealers.index'))->with('error', 'Request not found');
    }


    /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create($type = 0)
    {
        $data['dealerDetail'] = "";
        $data['users'] = User::selectRaw('id,fullName')->where(['userStatus' => 'Approved'])
                            //  ->whereIn('roleId',User::whichUserLogin())
                             ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                return $query->whereIn('id',  User::getMarketingAdminEmployee());
                            })
                            ->get();
        $data['states'] = State::selectRaw('states.*')->join('cities','cities.stateId','states.id')->groupBy('states.id')->where('states.isActive',1)->get();
        $data['regions'] = Regions::where('isActive',1)->get();

        $data['randomNumber'] = UtilityHelper::generateUniqueCode('dealers', 'dealerId');

        $data['type'] = $type;

        return view('admin.manageDealer.create',$data);
    }

   /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        $type = $request->type;

        $dealerRecord = new Dealer();

        $isInsert = 1;
        if(!empty($request->id))
        {
            $dealerRecord =  Dealer::where(['id' => $request->id,'userId' => $request->userId])->first();
            if(empty($dealerRecord))
            {
                return redirect(route('dealers.index'))->with('success', trans('messages.msg.not_found', ['module' => 'dealer']));
            }
            $isInsert = 0;
        }

        $dealerRecord->fill($request->all());

        $dealerRecord->time = date('H:i:s', strtotime($request->time));

        if ($request->hasFile('photo')) {
            $dealerRecord->photo = ImageHelper::uploadImage($request->file('photo'), 'dealer');
        }

        if ($request->hasFile('shopPhoto')) {
            $dealerRecord->shopPhoto = ImageHelper::uploadImage($request->file('shopPhoto'), 'dealer');
        }

        $dealerRecord->sName = State::where(['id' => $request->stateId])->first()->sName ?? NULL;
        $dealerRecord->cName = Cities::where(['id' => $request->cityId])->first()->cName ?? NULL;
        $dealerRecord->tName = Talukas::where(['id' => $request->talukaId])->first()->tName ?? NULL;
        $dealerRecord->rName = Regions::where(['id' => $request->regionId])->first()->rName ?? NULL;
        $dealerRecord->statusDealers = 'Approved';
        $dealerRecord->save();

        if($isInsert == 1)
        {
            return redirect(route('dealers.index',['type' => $type ]))->with('success', trans('messages.msg.created.success', ['module' => 'actor']));
        }

        if($isInsert == 0)
        {
            return redirect(route('dealers.index',['type' => $type ]))->with('success', trans('messages.msg.updated.success', ['module' => 'actor']));
        }
        return redirect(route('dealers.index',['type' => $type ]))->with('success', trans('messages.msg.not_found', ['module' => 'actor']));
    }


     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */


    public function show($id)
    {
        $dealerDetail = Dealer::dealerAndUsersDetails()->where('dealers.id',$id)->first();

        $type = $dealerDetail->fType == 'Dealer' ? 1 : 2;


        if (!empty($dealerDetail)) {
          return view('admin.manageDealer.view',compact('dealerDetail','type'));
        }

        return redirect(route('dealers.index'))->with('error', trans('messages.dealer.not_found', ['module' => 'actor']));
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $data['dealerDetail'] = Dealer::dealerAndUsersDetails()->where('dealers.id',$id)->first();

        if(empty($data['dealerDetail']))
        {
            return redirect(route('dealers.index'))->with('error', trans('messages.msg.not_found', ['module' => 'dealer']));
        }

        $data['users'] = User::selectRaw('id,fullName')->where(['userStatus' => 'Approved'])->whereIn('roleId',User::whichUserLogin())->get();
        // $data['states'] = State::where('isActive',1)->get();
        $data['states'] = State::selectRaw('states.*')->join('cities','cities.stateId','states.id')->groupBy('states.id')->where('states.isActive',1)->get();
        $data['city'] = Cities::where(['isActive' => 1,'stateId' => $data['dealerDetail']->stateId])->get();
        $data['talukas'] = Talukas::where(['isActive' => 1,'cityId' => $data['dealerDetail']->cityId])->get();
        $data['regions'] = Regions::where('isActive',1)->get();

        $data['type'] = $data['dealerDetail']->fType == 'Dealer' ? 1 : 2;

        return view('admin.manageDealer.create',$data);
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $dealer = Dealer::where('id', $id)->first();
        if ($dealer) {

            $imageName = config('constant.dealer_image') .'/'.$dealer->photo;
            User::bucketRemoveImage($imageName);

            $imageName1 = config('constant.dealer_image') .'/'.$dealer->shopPhoto;
            User::bucketRemoveImage($imageName1);


            $dealer->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'dealer']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'dealer']), 0);
    }
}
