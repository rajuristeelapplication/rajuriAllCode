<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use App\Helpers\ReportHelper;
use App\Exports\UserReportExport;
use App\Exports\ReimbursementReportExport;
use App\Exports\LeaveReportExport;
use App\Exports\DealerReportExport;
use App\Exports\VisitReportExport;
use App\Exports\MerchandiseReportExport;
use App\Exports\MerchandiseOrderReportExport;
use App\Exports\ComplainReportExport;
use App\Exports\KnowledgeReportExport;
use App\Exports\LeadReportExport;
use App\Exports\MaterialReportExport;
use App\Exports\AttendanceReportExport;
use App\Exports\MarketingVanReportExport;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Dealer;
use App\Models\InOut;
use App\Models\Knowledge;
use App\Models\Lead;
use App\Models\LeaveRequest;
use App\Models\Merchandise;
use App\Models\Reimbursement;
use App\Models\Schedule;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;

class AdminReportController extends Controller
{
    /**
     * User Report view
     *
     * @return void
     */

    public function userReportView()
    {
        $userNameList = User::selectRaw('users.id,users.fullName,users.roleId,users.cityId,cName')
                        ->leftjoin('cities','cities.id','users.cityId')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('users.id',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        $districtNameLists = $userNameList->unique('cName')->whereNotNull('cName');

        return view('admin.reports.report_user',compact('userNameList','districtNameLists'));
    }

    /**
     * User Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function userReport(Request $request)
    {
        $this->validate($request, [
            // 'daterange' => 'required',
        ]);

        $reports = ReportHelper::reportUserExport($request);



        if ($reports->isEmpty()) {
            return redirect()->route('admin.userReport')->with('error', trans('messages.msg.not_found', ['module' => 'User Report']));
        }

        // $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_user_export', [
                'reports' => $reports,
                // 'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('User_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {
            return view('admin.exports.report_user_export', [
                'reports' => $reports,
                // 'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new UserReportExport($request))->download('User_Report_' . date('d-m-Y') . '.xlsx');
        }
    }

    /**
     * Complaint Report view
     *
     * @return void
     */
    public function complaintReportView(Request $request)
    {
        // dd($request->all());
        $userNameList = Complaint::selectRaw('users.id,users.fullName,users.roleId,cStateId,cSName,cCityId,cCName,cTalukaId,cTName')
                        ->join('users','users.id','complaints.userId')
                        ->distinct('users.id')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
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

        return view('admin.reports.report_complaint',compact('userNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }

    /**
     * Complaint Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function complaintReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::reportComplaintExport($request);

        // echo "<pre>";
        // print_r($reports->toArray());
        // exit;

        if ($reports->isEmpty()) {
            return redirect()->route('admin.complaintReport')->with('error', trans('messages.msg.not_found', ['module' => 'Complaint Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_complaint_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Complaint_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {


            return view('admin.exports.report_complaint_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
			{
				return (new ComplainReportExport($request))->download('Complaint_Report_' . date('d-m-Y') . '.xlsx');
			}
    }

    /**
     * Knowledge Report view
     *
     * @return void
     */
    public function knowledgeReportView(Request $request)
    {

        $userNameList = Knowledge::selectRaw('users.id,users.fullName,users.roleId,ksStateId,ksSName,ksCityId,ksCName,ksTalukaId,ksTName')
                        ->join('users','users.id','knowledges.userId')
                        ->distinct('users.id')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();


        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('ksCName')->where('ksStateId',$request->s_name)->whereNotNull('ksCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('ksTName')->where('ksCityId',$request->cityId)->whereNotNull('ksTName');
            }

            return response()->json($data);
        }

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('ksSName')->whereNotNull('ksSName');
        $districtNameLists = $userNameList->unique('ksCName')->whereNotNull('ksCName');
        $talukaNameLists = $userNameList->unique('ksTName')->whereNotNull('ksTName');


        return view('admin.reports.report_knowledge',compact('userNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }

    /**
     * Knowledge Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function knowledgeReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::reportKnowledgeExport($request);


        if ($reports->isEmpty()) {
            return redirect()->route('admin.knowledgeReport')->with('error', trans('messages.msg.not_found', ['module' => 'Knowledge Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_knowledge_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Knowledge_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_knowledge_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }

        elseif($request->export_type == 'Excel')
        {
            return (new KnowledgeReportExport($request))->download('Knowledge_Report_' . date('d-m-Y') . '.xlsx');
        }
    }


    /**
     * Dealer Report view
     *
     * @return void
     */
    public function dealerReportView(Request $request)
    {
        $userNameList = Dealer::selectRaw('dealers.fType,users.id,users.fullName,users.roleId,dealers.stateId,dealers.sName,dealers.cityId,dealers.cName,dealers.talukaId,dealers.tName')
                        ->join('users','users.id','dealers.userId')
                        ->distinct('users.id')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                        })
                        ->where('dealers.fType','Dealer')
                        ->orderByRaw('fullName ASC')
                        ->get();


        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('cName')->where('stateId',$request->s_name)->whereNotNull('cName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('tName')->where('cityId',$request->cityId)->whereNotNull('tName');
            }

            return response()->json($data);
        }


        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('sName')->whereNotNull('sName');
        $districtNameLists = $userNameList->unique('cName')->whereNotNull('cName');
        $talukaNameLists = $userNameList->unique('tName')->whereNotNull('tName');


        $regionNameList = Dealer::selectRaw('regions.id,regions.rName')
                        ->join('regions','regions.id','dealers.regionId')
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                        })
                        ->where('dealers.fType','Dealer')
                        ->distinct('dealers.regionId')
                        ->orderByRaw('rName ASC')
                        ->get();

        $dealerType = 'dealer';


        return view('admin.reports.report_dealer',compact('userNameList','regionNameList','stateNameLists','districtNameLists','talukaNameLists','dealerType'));
    }

    /**
     * Dealer Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function dealerReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::reportDealerExport($request);

        if ($reports->isEmpty()) {

            if($request->actorType == "dealer")
            {
                return redirect()->route('admin.dealerReport')->with('error', trans('messages.msg.not_found', ['module' => 'Actor Report']));
            }else{
                return redirect()->route('admin.otherActorReport')->with('error', trans('messages.msg.not_found', ['module' => 'Actor Report']));
            }
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_dealer_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Dealer_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_dealer_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new DealerReportExport($request))->download('Dealer_Report_' . date('d-m-Y') . '.xlsx');
        }
    }


      /**
     * Other Dealer Report view
     *
     * @return void
     */
    public function otherActorReportView(Request $request)
    {

        $userNameList = Dealer::selectRaw('users.id,users.fullName,users.roleId,dealers.stateId,dealers.sName,dealers.cityId,dealers.cName,dealers.talukaId,dealers.tName')
                        ->join('users','users.id','dealers.userId')
                        ->distinct('users.id')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                        })
                        ->where('dealers.fType','!=','Dealer')
                        ->orderByRaw('fullName ASC')
                        ->get();

        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('cName')->where('stateId',$request->s_name)->whereNotNull('cName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('tName')->where('cityId',$request->cityId)->whereNotNull('tName');
            }

            return response()->json($data);
        }


        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('sName')->whereNotNull('sName');
        $districtNameLists = $userNameList->unique('cName')->whereNotNull('cName');
        $talukaNameLists = $userNameList->unique('tName')->whereNotNull('tName');


        $dealerType = 'other';


        $regionNameList = Dealer::selectRaw('regions.id,regions.rName')
                        ->join('regions','regions.id','dealers.regionId')
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                        })

                        ->distinct('dealers.regionId')
                        ->orderByRaw('rName ASC')
                        ->get();

        return view('admin.reports.report_dealer',compact('userNameList','regionNameList','stateNameLists','districtNameLists','talukaNameLists','dealerType'));
    }



    /**
     * Visit Report view
     *
     * @return void
     */
    public function visitReportView(Request $request)
    {

        $userNameList = Schedule::selectRaw('users.id,users.fullName,users.roleId,dStateId,dSName,dCityId,dCName,dTalukaId,dTName')
                        ->join('users','users.id','schedules.userId')
                        ->distinct('users.fullName')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('dCName')->where('dStateId',$request->s_name)->whereNotNull('dCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('dTName')->where('dCityId',$request->cityId)->whereNotNull('dTName');
            }

            return response()->json($data);
        }


        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('dSName')->whereNotNull('dSName');
        $districtNameLists = $userNameList->unique('dCName')->whereNotNull('dCName');
        $talukaNameLists = $userNameList->unique('dTName')->whereNotNull('dTName');


        $dealerNameList = Schedule::selectRaw('dealers.id,dealers.name,users.roleId,schedules.rjDealerId')
                            ->join('dealers','dealers.id','schedules.rjDealerId')
                            ->join('users','users.id','schedules.userId')
                            ->distinct('schedules.rjDealerId')
                            ->whereIn('roleId',User::whichUserLogin())
                            ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                            })
                            ->orderByRaw('name ASC')
                            ->get();

       $getExpense = ExpenseType::where('type','Brand')->get(['id','eName']);

        return view('admin.reports.report_visit',compact('userNameList','dealerNameList','stateNameLists','districtNameLists','talukaNameLists','getExpense'));
    }

    /**
     * Visit Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function visitReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::dailyReport($request, $type = NULL)->get();



        if ($reports->isEmpty()) {
            return redirect()->route('admin.visitReport')->with('error', trans('messages.msg.not_found', ['module' => 'Visit Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_daily_update', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Visit_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_daily_update', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new VisitReportExport($request))->download('Visit_Report_' . date('d-m-Y') . '.xlsx');
        }
    }


    /**
     * Merchandise Gift Report view
     *
     * @return void
     */
    public function merchandiseReportView(Request $request)
    {

        $userNameList = Merchandise::selectRaw('users.id,users.fullName,users.roleId,mStateId,mSName,mCityId,mCName,mTalukaId,mTName')
                    ->join('users','users.id','merchandises.userId')
                    ->distinct('users.fullName')
                    ->whereIn('roleId',User::whichUserLogin())
                    ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                    })
                    ->where('mType','Gift')
                    ->orderByRaw('fullName ASC')
                    ->get();

        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('mCName')->where('mStateId',$request->s_name)->whereNotNull('mCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('mTName')->where('mCityId',$request->cityId)->whereNotNull('mTName');
            }

            return response()->json($data);
        }

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('mSName')->whereNotNull('mSName');
        $districtNameLists = $userNameList->unique('mCName')->whereNotNull('mCName');
        $talukaNameLists = $userNameList->unique('mTName')->whereNotNull('mTName');


        $productGift = Merchandise::selectRaw('products.pName')
                    ->join('merchandises_orders','merchandises_orders.merchandisesOrderId','merchandises.id')
                    ->join('product_attributes','product_attributes.id','merchandises_orders.productOptionsId')
                    ->join('products','products.id','product_attributes.productId')
                    ->join('users','users.id','merchandises.userId')
                    ->distinct('products.pName')
                    ->whereIn('roleId',User::whichUserLogin())
                    ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                    })
                    ->where('mType','Gift')
                    ->orderByRaw('pName ASC')
                    ->get();



                    $dealerNameList = Merchandise::selectRaw('dealers.id,dealers.name')
                    ->join('dealers','dealers.id','merchandises.rjDealerId')
                    ->join('users','users.id','merchandises.userId')
                    ->distinct('dealers.id')
                    ->whereIn('roleId',User::whichUserLogin())
                    ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                        return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                    })
                    ->where('mType','Gift')
                    ->orderByRaw('name ASC')
                    ->get();

        return view('admin.reports.report_merchandise',compact('userNameList','productGift','dealerNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }

    /**
     * Merchandise Gift Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function merchandiseReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $pathPanImage = config('constant.baseUrlS3') . config('constant.merchandises_image');

        $reports = ReportHelper::merchandisesReport($request, $type = NULL)
                    ->with([
                        'orderProducts' => function ($query) use($pathPanImage) {

                            $query->selectRaw('merchandises_orders.*,
                            IF(ISNULL(orderAttachment) or orderAttachment = "", "", CONCAT("'.$pathPanImage.'","/",orderAttachment)) as orderAttachment,
                            product_attributes.productOptionName,product_attributes.productId,products.pName,product_attributes.isSubProduct
                            ')
                            ->leftJoin('product_attributes','product_attributes.id','merchandises_orders.productOptionsId')
                            ->leftJoin('products','products.id','product_attributes.productId');
                        }
                    ])->get();

        // echo "<pre>";
        // print_r($reports->toArray());
        // exit;


        if ($reports->isEmpty()) {
            return redirect()->route('admin.merchandiseReport')->with('error', trans('messages.msg.not_found', ['module' => 'Merchandise Report']));
        }



        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_merchandise', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Merchandise_Gift_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {
            return view('admin.exports.report_merchandise', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new  MerchandiseReportExport($request))->download('Merchandise_Gift_Report_' . date('d-m-Y') . '.xlsx');
        }
    }


    /**
     * Merchandise Order Report view
     *
     * @return void
     */
    public function merchandiseOrderReportView(Request $request)
    {
        $userNameList = Merchandise::selectRaw('users.id,users.fullName,users.roleId,mStateId,mSName,mCityId,mCName,mTalukaId,mTName')
        ->join('users','users.id','merchandises.userId')
        ->distinct('users.fullName')
        ->whereIn('roleId',User::whichUserLogin())
        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
            return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
        })
        ->where('mType','Order')
        ->orderByRaw('fullName ASC')
        ->get();


        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('mCName')->where('mStateId',$request->s_name)->whereNotNull('mCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('mTName')->where('mCityId',$request->cityId)->whereNotNull('mTName');
            }

            return response()->json($data);
        }

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('mSName')->whereNotNull('mSName');
        $districtNameLists = $userNameList->unique('mCName')->whereNotNull('mCName');
        $talukaNameLists = $userNameList->unique('mTName')->whereNotNull('mTName');

        $dealerNameList = Merchandise::selectRaw('dealers.id,dealers.name')
                                ->join('dealers','dealers.id','merchandises.rjDealerId')
                                ->join('users','users.id','merchandises.userId')
                                ->distinct('dealers.id')
                                ->whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                })
                                ->where('mType','Order')
                                ->orderByRaw('name ASC')
                                ->get();

        $productGift = Merchandise::selectRaw('products.pName')
                ->join('merchandises_orders','merchandises_orders.merchandisesOrderId','merchandises.id')
                ->join('product_attributes','product_attributes.id','merchandises_orders.productOptionsId')
                ->join('products','products.id','product_attributes.productId')
                ->join('users','users.id','merchandises.userId')
                ->distinct('products.pName')
                ->whereIn('roleId',User::whichUserLogin())
                ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                })
                ->where('mType','Order')
                ->orderByRaw('pName ASC')
                ->get();

        return view('admin.reports.report_merchandise_order',compact('userNameList','productGift','dealerNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }

    /**
     * Merchandise Order Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function merchandiseOrderReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);


        $pathPanImage = config('constant.baseUrlS3') . config('constant.merchandises_image');


        $reports = ReportHelper::merchandisesOrderReport($request, $type = NULL)
                ->with([
                    'orderProducts' => function ($query) use($pathPanImage) {

                        $query->selectRaw('merchandises_orders.*,
                        IF(ISNULL(orderAttachment) or orderAttachment = "", "", CONCAT("'.$pathPanImage.'","/",orderAttachment)) as orderAttachment,
                        product_attributes.productOptionName,product_attributes.productId,products.pName,product_attributes.isSubProduct
                        ')
                        ->leftJoin('product_attributes','product_attributes.id','merchandises_orders.productOptionsId')
                        ->leftJoin('products','products.id','product_attributes.productId');
                    }
                ])->get();


        // echo "<pre>";
        // print_r($reports->toArray());
        // exit;

        if ($reports->isEmpty()) {
            return redirect()->route('admin.merchandiseOrderReport')->with('error', trans('messages.msg.not_found', ['module' => 'Merchandise Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_merchandise_order', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Merchandise_Order_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {
            return view('admin.exports.report_merchandise_order', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new MerchandiseOrderReportExport($request))->download('Merchandise_Order_Report_' . date('d-m-Y') . '.xlsx');
        }
    }

    /**
     * Reimbursements Report view
     *
     * @return void
     */
    public function reimbursementReportView($type = 'other')
    {
        $getExpense = ExpenseType::where('type','Reimbursement')->whereNotIn('id',['0608b614-9564-11ec-879c-000c293f1073','e7897fb6-9563-11ec-879c-000c293f1073'])->get(['id','eName']);

        $userNameList = Reimbursement::selectRaw('users.id,users.fullName')
                        ->join('users','users.id','reimbursements.userId')
                        ->distinct('reimbursements.userId')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');

        $dealerNameList = Reimbursement::selectRaw('dealers.id,dealers.name,dealers.fType,dealers.dType')
                        ->join('dealers','dealers.id','reimbursements.rjDealerId')
                        ->join('users','users.id','reimbursements.userId')
                        ->distinct('dealers.id')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('name ASC')
                        ->get();

        return view('admin.reports.report_reimbursement', compact('getExpense','userNameList','type','dealerNameList'));
    }

    /**
     * Reimbursements Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reimbursementReport(Request $request)
    {

        // dd($request->all());

        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reimbursementType = $request->reimbursementType ?? 'other';


        $reports = ReportHelper::reimbursementReport($request, $type = NULL)
                                ->selectRaw('users.fullName, reimbursements.id, reimbursements.rName,users.roleId,roles.roleName')
                                ->join('users', 'users.id', 'reimbursements.userId')
                                ->join('roles', 'roles.id', 'users.roleId');

            if(!empty($request->userType))
                {
                    $reports =  $reports->where('users.roleId',$request->userType);
                }

                if(!empty($request->formType))
                {
                    $reports =  $reports->where('dealers.fType',$request->formType);
                }

                if(!empty($request->dealerName))
                {
                    $reports =  $reports->where('dealers.id',$request->dealerName);
                }

            if(!empty($request->userId))
            {
                $reports =  $reports->where('users.id',$request->userId);
            }


        $reports = $reports->orderBy('reimbursements.createdAt', 'desc')
                                ->get();


        if ($reports->isEmpty()) {

            if($reimbursementType == "other")
            {
                return redirect()->route('admin.reimbursementReport')->with('error', trans('messages.msg.not_found', ['module' => 'Reimbursement Report']));
            }

            if($reimbursementType == "birthday")
            {
                return redirect()->route('admin.reimbursementReport',['type' => 'birthday'])->with('error', trans('messages.msg.not_found', ['module' => 'Reimbursement Report']));
            }

            if($reimbursementType == "incentive")
            {
                return redirect()->route('admin.reimbursementReport',['type' => 'incentive'])->with('error', trans('messages.msg.not_found', ['module' => 'Reimbursement Report']));
            }

        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_reimbursement', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
                'reimbursementType' => $reimbursementType
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Reimbursement_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_reimbursement', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
                'reimbursementType' => $reimbursementType
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new ReimbursementReportExport($request))->download('Reimbursement_Report_' . date('d-m-Y') . '.xlsx');
        }
    }

    /**
     * Leave Report view
     *
     * @return void
     */
    public function leaveReportView()
    {
        $userNameList = LeaveRequest::selectRaw('users.id,users.fullName')
                        ->join('users','users.id','leave_requests.userId')
                        ->distinct('leave_requests.userId')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');

        return view('admin.reports.report_leave',compact('userNameList'));
    }

    /**
     * Leave Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::leaveReport($request, $type = NULL);
                                // ->selectRaw('users.fullName, leave_requests.id, DATE_FORMAT(leave_requests.createdAt, "' . config('constant.in_out_date_time_format') . '") as createdAtFormate,users.roleId,roles.roleName')
                                // ->join('users', 'users.id', 'leave_requests.userId')
                                // ->join('roles', 'roles.id', 'users.roleId')
                                // ->where('leave_requests.lRStatus', '!=', 'Credit')
                                // ->orderBy('leave_requests.createdAt', 'desc')
                                // ->get();

        if ($reports->isEmpty()) {
            return redirect()->route('admin.leaveReport')->with('error', trans('messages.msg.not_found', ['module' => 'Leave Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_leave', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Leave_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_leave', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new LeaveReportExport($request))->download('Leave_Report_' . date('d-m-Y') . '.xlsx');
        }
    }



    /**
     * Material Report view
     *
     * @return void
     */
    public function materialReportView()
    {
        return view('admin.reports.report_material');
    }

    /**
     * Material Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function materialReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::materialReport($request, $type = NULL)
                                ->orderBy('material_reports.createdAt', 'desc')
                                ->where('material_reports.isParent',0)
                                ->get();

        if ($reports->isEmpty()) {
            return redirect()->route('admin.materialReport')->with('error', trans('messages.msg.not_found', ['module' => 'Material Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_material', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Material_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_material', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new MaterialReportExport($request))->download('Material_Report_' . date('d-m-Y') . '.xlsx');
        }
    }
    /**
     * Marketing Van Request Report view
     *
     * @return void
     */
    public function marketingVanReportView()
    {
        $userNameList = Knowledge::selectRaw('users.id,users.fullName')
                ->join('users','users.id','knowledges.userId')
                ->distinct('knowledges.userId')
                ->whereIn('roleId',User::whichUserLogin())
                ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
                })
                ->orderByRaw('fullName ASC')
                ->get();

        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');

        $dealerNameList = Knowledge::selectRaw('dealers.id,dealers.name,dealers.fType,dealers.dType')
                ->join('dealers','dealers.id','knowledges.rjDealerId')
                ->join('users','users.id','knowledges.userId')
                ->distinct('dealers.id')
                ->whereIn('roleId',User::whichUserLogin())
                ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
                })
                ->orderByRaw('name ASC')
                ->get();


        return view('admin.reports.report_marketing_van_request',compact('userNameList','dealerNameList'));
    }

    /**
     * Marketing Van Request Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function marketingVanReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::marketingVanRequestReport($request, $type = NULL)
                                ->selectRaw('users.fullName, del.fType,del.dealerId,del.firmName,del.name,
                                del.dealerId, DATE_FORMAT(knowledges.kdate, "' . config('constant.schedule_date_format') . '") as kdateFormate,
                                DATE_FORMAT(knowledges.ktime, "' . config('constant.schedule_time_format') . '") as ktimeFormate')
                                ->join('users', 'users.id', 'knowledges.userId')
                                ->join('dealers as del', 'del.id', 'knowledges.rjDealerId')
                                ->orderBy('knowledges.kDate', 'desc');

        if(!empty($request->formType))
        {
            $reports =  $reports->where('dealers.fType',$request->formType);
        }

        if(!empty($request->dealerName))
        {
            $reports =  $reports->where('dealers.id',$request->dealerName);
        }

        if(!empty($request->userId))
        {
            $reports =  $reports->where('users.id',$request->userId);
        }

        if(!empty($request->vehicleNumber1)){
            $reports = $reports->where('knowledges.vehicleNumber','=',$request->vehicleNumber1);
        }

        $reports =  $reports->get();

        if ($reports->isEmpty()) {
            return redirect()->route('admin.marketingVanReport')->with('error', trans('messages.msg.not_found', ['module' => 'Marketing Van Request Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_marketing_van_request', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Marketing_Van_Request_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_marketing_van_request', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }
        elseif($request->export_type == 'Excel')
        {
            return (new MarketingVanReportExport($request))->download('Marketing_Van_Request_Report_' . date('d-m-Y') . '.xlsx');
        }

    }

     /**
     * Lead Report view
     *
     * @return void
     */

    public function leadReportView(Request $request)
    {
        $userNameList = Lead::selectRaw('users.id,users.fullName,users.roleId,pStateId,pSName,pCityId,pCName,pTalukaId,pTName')
                        ->join('users','users.id','leads.userId')
                        ->distinct('users.id')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('leads.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();


        if($request->ajax()){

            if(!empty($request->s_name))
            {
                $data['city'] = $userNameList->unique('pCName')->where('pStateId',$request->s_name)->whereNotNull('pCName');
            }

            if(!empty($request->cityId))
            {
                $data['taluka'] = $userNameList->unique('pTName')->where('pCityId',$request->cityId)->whereNotNull('pTName');
            }

            return response()->json($data);
        }


        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        $stateNameLists = $userNameList->unique('pSName')->whereNotNull('pSName');
        $districtNameLists = $userNameList->unique('pCName')->whereNotNull('pCName');
        $talukaNameLists = $userNameList->unique('pTName')->whereNotNull('pTName');



        return view('admin.reports.report_lead',compact('userNameList','stateNameLists','districtNameLists','talukaNameLists'));
    }


      /**
     * Lead Request Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */


    public function leadReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::leadReport($request, $type = NULL)->get();

        if ($reports->isEmpty()) {
            return redirect()->route('admin.leadReport')->with('error', trans('messages.msg.not_found', ['module' => 'Lead Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_lead_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Lead_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_lead_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }

        elseif($request->export_type == 'Excel')
        {
            return (new LeadReportExport($request))->download('Lead_Report_' . date('d-m-Y') . '.xlsx');
        }
    }

      /**
         * Attendance Report view
         *
         * @return view
     */

    public function attendanceReportView(Request $request)
    {
        $userNameList = InOut::selectRaw('users.id,users.fullName,users.roleId')
                        ->join('users','users.id','in_outs.userId')
                        ->distinct('users.id')
                        ->whereIn('roleId',User::whichUserLogin())
                        ->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('in_outs.userId',  User::getMarketingAdminEmployee());
                        })
                        ->orderByRaw('fullName ASC')
                        ->get();
        $userNameList = $userNameList->unique('fullName')->whereNotNull('fullName');
        return view('admin.reports.report_attendance',compact('userNameList'));
    }

        /**
     * Attendance Request Report details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\ResponsecreateOrUpdate
     */


    public function attendanceReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $reports = ReportHelper::attendanceReport($request, $type = NULL)->get();


        if ($reports->isEmpty()) {
            return redirect()->route('admin.attendanceReport')->with('error', trans('messages.msg.not_found', ['module' => 'Attendance Report']));
        }

        $reportInfo = ReportHelper::reportInfo($request);

        if ($request->export_type == 'Pdf') {
            ini_set('max_execution_time', 180);
            $pdf = PDF::loadView('admin.exports.report_attendance_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Attendance_Report_' . date('d-m-Y') . '.pdf');
        } elseif ($request->export_type == 'ViewReport') {

            return view('admin.exports.report_attendance_export', [
                'reports' => $reports,
                'reportInfo' => $reportInfo,
            ]);
        }

        elseif($request->export_type == 'Excel')
        {
            return (new AttendanceReportExport($request))->download('Attendance_Report_' . date('d-m-Y') . '.xlsx');
        }
    }

}
