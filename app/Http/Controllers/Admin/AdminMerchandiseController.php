<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use App\Models\Dealer;
use App\Models\State;
use App\Models\Cities;
use App\Models\Talukas;
use App\Models\Product;
use App\Models\Merchandise;
use App\Models\ProductAttribute;
use App\Models\MerchandisesOrder;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use App\Helpers\UtilityHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;


class AdminMerchandiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$type)
    {
        $userNameList = Merchandise::selectRaw('users.id,users.fullName,users.roleId,mStateId,mSName,mCityId,mCName,mTalukaId,mTName')
                                ->join('users','users.id','merchandises.userId')
                                ->distinct('users.fullName')
                                ->whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                })
                                ->where('mType',$type)
                                ->orderByRaw('fullName ASC')
                                ->get();

        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('mCityId')->where('mStateId',$request->s_name)->whereNotNull('mCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('mTalukaId')->where('mCityId',$request->cityId)->whereNotNull('mTName');
            }

            return response()->json($data);
        }
        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('mStateId')->whereNotNull('mSName');
        $districtNameLists = $userNameList->unique('mCName')->whereNotNull('mCName');
        $talukaNameLists = $userNameList->unique('mTName')->whereNotNull('mTName');


        $dealerNameList = Merchandise::selectRaw('dealers.id,dealers.name,users.roleId,merchandises.rjDealerId')
                                ->join('dealers','dealers.id','merchandises.rjDealerId')
                                ->join('users','users.id','merchandises.userId')
                                ->distinct('merchandises.rjDealerId')
                                ->whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                })
                                ->where('mType',$type)
                                ->orderByRaw('name ASC')
                                ->get();
        $dealerNameList = $dealerNameList->unique('name')->whereNotNull('name');

        return view('admin.merchandise.list',compact('type', 'userNameList', 'dealerNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }

     /**
     * Search OR Sorting Merchandise (DataTable).
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

            $query = Merchandise::getSelectQuery()->selectRaw('DATE_FORMAT(merchandises.mDate, "' . config('constant.schedule_date_format') . '") as mDateFormate,
            DATE_FORMAT(merchandises.mTime, "' . config('constant.schedule_time_format') . '") as mTimeFormate')
            ->when(!empty($request->cityId), function ($query) use ($request) {
                return $query->where('merchandises.mCityId',  $request->cityId);
            })
            ->when(!empty($request->stateId), function ($query) use ($request) {
                return $query->where('merchandises.mStateId',  $request->stateId);
            })
            ->when(!empty($request->talukaId), function ($query) use ($request) {
                return $query->where('merchandises.mTalukaId',  $request->talukaId);
            });


            if(!empty($request->userType)){
                $query = $query->where('dealers.fType','=',$request->userType);
            }

            if(!empty($request->mStatus)){
                $query = $query->where('merchandises.mStatus','=',$request->mStatus);
            }

            if(!empty($request->mType)){
                    $query = $query->where('merchandises.mType','=',$request->mType);
                }

                if (!empty($request->employeeType)) {
                    $query = $query->where('merchandises.userId', '=', $request->employeeType);
                }

                if (!empty($request->dealerType)) {
                    $query = $query->where('merchandises.rjDealerId', '=', $request->dealerType);
                }
                    // Role Base Condition

              if(\Auth::user()->roleId == config('constant.ma_id'))
              {
                $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                });

                  $query = $query->where('roleId','=',config('constant.marketing_executive_id'));
              }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('dealers.dealerId', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('merchandises.mStatus', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('merchandises.mDate', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('merchandises.mType', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {



                $params = [
                    'merchandise' => $row['id'],
                ];

                $params1 = [
                    'merchandise' => $row['id'],
                    'type' =>  ($row['mType'] == 'Order') ? 'order' :'gift'
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('merchandise.show', $params1);
                $editRoute   = route('merchandise.edit', $params);
                $statusRoute = route('merchandise.status', $params);
                $userStatusRoute = route('merchandise.userStatus', $params);
                $deleteRoute = route('merchandise.destroy', $params);

                $status = $row['mStatus'];
                $type = '';
                if ($status == 'Pending') {
                    $type = 'info';
                } else if ($status == 'Approved') {
                    $type = 'success';
                }else if ($status == 'Delivered'){
                    $type = 'warning';
                }

                $userStatus = '<span class="badge badge-' . $type . '" style="cursor: pointer !important;">' . $row['mStatus'] . '</span>';


                $rows['data'][$key]['fullName'] = $row['fullName'] ? ucfirst($row['fullName']) : '-';
                $rows['data'][$key]['mType'] = $row['mType'] ?? '-';
                $rows['data'][$key]['name'] = $row['name'] ?? '-';
                $rows['data'][$key]['dealerId'] = $row['dealerId'] ?? '-';
                $rows['data'][$key]['mDateFormate'] = $row['mDateFormate'].' '.$row['mTimeFormate'] ?? '-';
                $rows['data'][$key]['createdAt'] = $row['createdAt'] ?? '-';
                $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Merchandise Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                if(!empty($request->mType) &&  $request->mType == "order" &&  $row['mStatus'] == "Pending"){
                    $rows['data'][$key]['action'] .= '<a a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                    $rows['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';
                 }
                $rows['data'][$key]['mStatus'] = '<a id="statusVal" href="javascript:void(0);" data-st="' . $status . '" data-id="' . $row['id'] . '" data-url="' . $userStatusRoute . '" class="btnChangeUserStatus">' . $userStatus . '</a>';
            }
            return response()->json($rows);
        }
    }

    /**
     * Change status of the Merchandise Order Or Gift.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $merchandise = Merchandise::find($id);

        if (empty($merchandise)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'merchandise']), 0);
        }

        $merchandise->isActive = !$merchandise->isActive;
        $status = '';
        if ($merchandise->save()) {
            $status = $merchandise->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'merchandise', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'merchandise', 'type' => $status]), 0);
    }


    /**
     * Update Status of the Merchandise Order Or Gift.
     *
     * @param string $id
     *
     * @return Redirect
     */
    public function changeUserStatus($id, Request $request)
    {

        $merchandise = Merchandise::where('id', $request->id)->first();
        $merchandise->mStatus = $request->requestStatus;
        $merchandise->mStatusUpdateUserBy = Auth::user()->id;

        if ($merchandise->save()) {

            if($merchandise->mStatus == "Rejected")
            {
                $orderProduct = MerchandisesOrder::where(['merchandisesOrderId' => $merchandise->id])->get();

                foreach ($orderProduct as $orderPro) {
                     ProductAttribute::where(['id' => $orderPro->productOptionsId])->increment('totalQty',$orderPro->orderQty);
                    }
            }

            NotificationHelper::orderPlace($merchandise);

            return redirect()->back()->with('success', 'Status updated successfully');
            // return redirect(route('merchandise.index',['type' => strtolower($merchandise->mType)]))->with('success', 'Status updated successfully');
        }
        return redirect(route('merchandise.index',['type' => strtolower($merchandise->mType)]))->with('error', 'Request not found');
    }

   /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $type =  $request->type;

        $userId = $request->userId ?? NULL;


        $data['merchandiseDetail'] = '';
        $data['userDetail'] = User::selectRaw('id,fullName')
                            ->where(['userStatus' => 'Approved'])
                            ->whereIn('roleId',User::whichUserLogin())
                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                            })

                            ->orderBy('fullName','asc')
                            ->get();
        $data['states'] = State::where('isActive',1)->orderBy('sName','asc')->get();
        $data['type'] = $type;

        $products =  Product::getSelectQuery($type,$userId)->where('pType',$type);

                    if($type == "Gift" || $type == "gift")
                    {
                        $products = $products->having('isGift','>',0);
                    }

                    if($type == "Order" || $type == "order")
                    {
                        $products = $products->having('isShowRecord',1);
                    }

                 $products = $products->orderBy('products.createdAt', 'desc');

       $data['getProducts']  = $products->get();
       $data['userId'] = $userId;

        return view('admin.merchandise.create',$data);
    }

   /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        $id = $request->id;
        $orderProducts =  $request->productOptions;

        $opArray = [];


        if(!empty($orderProducts))
        {
            foreach($orderProducts as $key => $orderProduct)
            {
                if(!empty($orderProduct))
                {
                    $orderProductRecord = ProductAttribute::where(['id' => $key])->first();

                    if(!empty($orderProduct))
                    {
                        $opArray[]  = [
                            'id' => $orderProductRecord->id,
                            'productId' => $orderProductRecord->productId,
                            'orderQty' => $orderProduct,
                            'orderDesc' => $request->desc[$orderProductRecord->id] ?? NULL
                        ];
                    }

                }
            }
        }

        \DB::beginTransaction();


        $merchandisesRecord = new Merchandise();
        $insertUpdate = "insert";
        if(!empty($id))
        {
            $insertUpdate = "update";
            $merchandisesRecord = Merchandise::where('id',$id)->first();
        }

        $merchandisesRecord->fill($request->all());

        $merchandisesRecord->mTime = date('H:i:s', strtotime($request->mTime));


        if ($request->hasFile('mPhoto')) {
            $merchandisesRecord->mPhoto = ImageHelper::uploadImage($request->file('mPhoto'), 'merchandises');
        }

        // $merchandisesRecord->mSName = State::where(['id' => $request->mStateId])->first()->sName ?? NULL;
        // $merchandisesRecord->mCName = Cities::where(['id' => $request->mCityId])->first()->cName ?? NULL;
        // $merchandisesRecord->mTName = Talukas::where(['id' => $request->mTalukaId])->first()->tName ?? NULL;
        $merchandisesRecord->orderNumber = UtilityHelper::generateUniqueCode('merchandises', 'orderNumber');

        $merchandisesRecord->save();


        if(!empty($opArray))
        {
            $result =  Merchandise::orderProductCheck($opArray,$merchandisesRecord,$insertUpdate);

            if($result['status'] == 0)
            {
                return redirect()->back()->with('error', $result['msg']);
            }
        }

        return redirect(route('merchandise.index',['type' => $request->mType]))->with('success', trans('messages.msg.created.success', ['module' => 'Merchandise' . $request->mType]));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {

        $merchandisesDetails = Merchandise::merchandisesDetails()
            ->where('merchandises.id', $id)
            ->first();

        $orderProducts = Merchandise::getOrderProductMerchandise($merchandisesDetails->orderProducts);


        if(!empty($orderProducts))
        {
            unset($merchandisesDetails->orderProducts);
            $merchandisesDetails->getProducts = $orderProducts;
        }

        if (!empty($merchandisesDetails)) {
            return view('admin.merchandise.view', ['merchandiseDetail'=> $merchandisesDetails]);
        }

        return redirect(route('merchandise.index'))->with('error', trans('messages.merchandise.not_found', ['module' => 'merchandise']));
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['merchandiseDetail'] = Merchandise::merchandisesDetails()
                            ->where('merchandises.id', $id)
                            ->first();


        if (empty($data['merchandiseDetail'])) {
            return redirect(route('merchandise.index'))->with('error', trans('messages.merchandise.not_found', ['module' => 'merchandise']));

        }


        $data['dealerDetail'] = Dealer::getSelectQuery()->where('fType',$data['merchandiseDetail']->fType)->get();

        $type = 'order';

        $data['type'] =  $type;

        $userId = $data['merchandiseDetail']->userId ?? NULL;

        $data['userDetail'] = User::selectRaw('id,fullName')
                            ->where(['userStatus' => 'Approved'])
                            ->whereIn('roleId',User::whichUserLogin())
                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                return $query->whereIn('id',  User::getMarketingAdminEmployee());
                            })
                            ->get();

        $data['states'] = State::where('isActive',1)->get();
        $data['city'] = Cities::where(['isActive' => 1,'stateId' => $data['merchandiseDetail']->mStateId])->get();
        $data['talukas'] = Talukas::where(['isActive' => 1,'cityId' => $data['merchandiseDetail']->mCityId])->get();
        $orderProduct = $data['merchandiseDetail']->orderProducts;
        $data['orderProduct'] = $orderProduct->keyBy('productOptionsId');


        $products =  Product::getSelectQuery($type,$userId)->where('pType',$type);

                    if($type == "Gift" || $type == "gift")
                    {
                        $products = $products->having('isGift','>',0);
                    }

                 $products = $products->orderBy('products.createdAt', 'desc');

       $data['getProducts']  = $products->get();


       $data['userId'] = $userId;

        return view('admin.merchandise.create',$data);

    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $merchandise = Merchandise::where('id', $id)->first();
        if ($merchandise) {

            $imageName = config('constant.merchandises_image') .'/'.$merchandise->mPhoto;
            User::bucketRemoveImage($imageName);

            $merchandise->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'merchandise']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'merchandise']), 0);
    }


}
