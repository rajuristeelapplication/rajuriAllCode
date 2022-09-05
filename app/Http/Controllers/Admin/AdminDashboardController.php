<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ReportHelper;
use App\Helpers\CurlCallHelper;
use App\Models\User;
use App\Models\Lead;
use App\Models\Dealer;
use App\Models\RequestPayslips;
use App\Models\Schedule;
use App\Models\Brochure;
use App\Models\FollowUp;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\Knowledge;
use App\Models\LeaveRequest;
use App\Models\Reimbursement;
use App\Models\Merchandise;
use App\Models\InOut;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class AdminDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles  admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Show admin dashboard
     *
     * All count Api
     *
     * @return view
     */
    public function index(Request $request)
    {

        // $mobileNo = '8849457554';
        // $smsKey =  'Zj5Aj6cX';
        // $otp = 3333;

        // $url = "https://api.onex-aura.com/api/sms?key=$smsKey&to=$mobileNo&from=RAJURI&body=Your%20Birthday%20For%20Very%20Rajuri%20Steels%20CRM%20application%20is%20$otp%20Rajuri";

        // // $url = "https://api.onex-aura.com/api/sms?key=$smsKey&to=$mobileNo&from=RAJURI&body=Your%20OTP%20For%20Login%20Rajuri%20Steels%20CRM%20application%20is%20$otp%20Rajuri";

        // // $url = "https://api.onex-aura.com/api/sms?key=$smsKey&to=$mobileNo&from=RAJURI&body=Your%20OTP%20For%20Login%20Rajuri%20Steels%20CRM%20application%20is%20%7b%23$otp%23%7d%5cr%5cnRajuri";

        // $response = CurlCallHelper::commonCurlCall('GET',$url,null,null);

        // echo $response['status'];

        // dd($response);

         $date = $request->date;

         $startDate = '';
         $endDate = '';

         $selectedDate = '';

         if(!empty($date))
         {
            $date =  str_replace("$","/",$date);

            $daterange = explode('-', $date);


            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');

            $selectedDate =  str_replace("$","/",$date);
            $selectedDate =  str_replace("-"," - ",$selectedDate);


         }else{

            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');

            $selectedDate = Carbon::now()->startOfMonth()->format('d/m/Y') .' - ' .Carbon::now()->format('d/m/Y');

         }


         $from_date = Carbon::parse(date('Y-m-d', strtotime($startDate)));
         $through_date = Carbon::parse(date('Y-m-d', strtotime($endDate)));

        // get total number of minutes between from and throung date

         $data['startDate'] = $startDate;
         $data['endDate'] = $endDate;

         $data['selectedDate'] = $selectedDate;

        // $data['totalUsers']          = User::whereNotIn('roleId', [config('constant.admin_id'), config('constant.hr_id'), config('constant.ma_id')])
        //                                 ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
        //                                     $query->whereDate('users.createdAt', '>=', $startDate)
        //                                             ->whereDate('users.createdAt', '<=', $endDate);
        //                                 })->count();

        // $data['totalSalesUsers']    = User::where('roleId', config('constant.sales_executive_id'))
        //                             ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
        //                                 $query->whereDate('users.createdAt', '>=', $startDate)
        //                                         ->whereDate('users.createdAt', '<=', $endDate);
        //                             })->count();

        // $data['totalMarketingUsers'] = User::where('roleId', config('constant.marketing_executive_id'))
        //                                 ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
        //                                     $query->whereDate('users.createdAt', '>=', $startDate)
        //                                             ->whereDate('users.createdAt', '<=', $endDate);
        //                                 })->count();

        //     $data['totalDepartments'] = Department::when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
        //                                             $query->whereDate('createdAt', '>=', $startDate)
        //                                                     ->whereDate('createdAt', '<=', $endDate);
        //                                         })->count();

        // $data['totalBrochures'] = Brochure::when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
        //                                         $query->whereDate('createdAt', '>=', $startDate)
        //                                                 ->whereDate('createdAt', '<=', $endDate);
        //                                     })->count();

        $data['totalLeaveRequest'] = LeaveRequest::join('users','users.id','leave_requests.userId')
                                                 ->whereIn('roleId',User::whichUserLogin())
                                                 ->where('lRStatus', '!=', 'Credit')
                                                    ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                        $query->whereDate('leave_requests.createdAt', '>=', $startDate)
                                                                ->whereDate('leave_requests.createdAt', '<=', $endDate);
                                                    })
                                                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                        return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
                                                    })

                                                    ->count();

        $data['totalPendingLeaveRequest'] = LeaveRequest::join('users','users.id','leave_requests.userId')
                                                        ->whereIn('roleId',User::whichUserLogin())->where('lRStatus', 'Pending')
                                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                            $query->whereDate('leave_requests.createdAt', '>=', $startDate)
                                                                    ->whereDate('leave_requests.createdAt', '<=', $endDate);
                                                        })
                                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                            return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
                                                        })
                                                        ->count();

        $data['totalApprovedLeaveRequest'] = LeaveRequest::join('users','users.id','leave_requests.userId')
                                                            ->whereIn('roleId',User::whichUserLogin())
                                                            ->where('lRStatus', 'Approved')
                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                $query->whereDate('leave_requests.createdAt', '>=', $startDate)
                                                        ->whereDate('leave_requests.createdAt', '<=', $endDate);
                                            })
                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
                                            })

                                            ->count();

        $data['totalRejectedLeaveRequest'] = LeaveRequest::join('users','users.id','leave_requests.userId')
                                            ->whereIn('roleId',User::whichUserLogin())
                                            ->where('lRStatus', 'Rejected')
                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                $query->whereDate('leave_requests.createdAt', '>=', $startDate)
                                                        ->whereDate('leave_requests.createdAt', '<=', $endDate);
                                            })
                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
                                            })
                                            ->count();


        $differentDay  = $from_date->diffInDays($through_date) + 1;

        $attendanceTotalUser = User::whereIn('roleId',User::whichUserLogin())
                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                    return $query->whereIn('id',  User::getMarketingAdminEmployee());
                                })
                                ->count() * $differentDay;

        $attendancePresent = InOut::join('users','users.id','in_outs.userId')
                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                $query->whereDate('in_outs.createdAt', '>=', $startDate)
                                        ->whereDate('in_outs.createdAt', '<=', $endDate);
                            })
                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                return $query->whereIn('in_outs.userId', User::getMarketingAdminEmployee());
                            })
                            ->count();


        $attendanceLeave = $data['totalApprovedLeaveRequest'] * $differentDay;

        $attendanceAbsent = ($attendanceTotalUser - ($attendancePresent + $attendanceLeave)) ;


        $data['attendanceTotalUser'] = $attendanceTotalUser;
        $data['attendancePresent'] = $attendancePresent;
        $data['attendanceLeave'] = $attendanceLeave;
        $data['attendanceAbsent'] = $attendanceAbsent;



        $data['totalReimbursements'] =   Reimbursement::when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                        $query->whereDate('createdAt', '>=', $startDate)
                                                                ->whereDate('createdAt', '<=', $endDate);
                                                    })
                                                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                        return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
                                                    })
                                                    ->count();
        $data['totalApprovedReimbursements'] = Reimbursement::where('rStatus', 'Approved')
                                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                                    $query->whereDate('createdAt', '>=', $startDate)
                                                                            ->whereDate('createdAt', '<=', $endDate);
                                                                })
                                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                                    return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
                                                                })
                                                                ->count();
        $data['totalPendingReimbursements'] = Reimbursement::where('rStatus', 'Pending')
                                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                                $query->whereDate('createdAt', '>=', $startDate)
                                                                        ->whereDate('createdAt', '<=', $endDate);
                                                            })
                                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                                return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
                                                            })
                                                            ->count();
        $data['totalRejectedReimbursements'] = Reimbursement::where('rStatus', 'Rejected')
                                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                                $query->whereDate('createdAt', '>=', $startDate)
                                                                        ->whereDate('createdAt', '<=', $endDate);
                                                            })
                                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                                return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
                                                            })
                                                            ->count();

        $data['totalDealers'] = Dealer::when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            // $query->whereDate('createdAt', '>=', $startDate)
                                            //         ->whereDate('createdAt', '<=', $endDate);
                                        })
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                        })

                                        ->count();
        $data['totalDealerFilter'] = Dealer::where('fType', 'Dealer')
                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                    // $query->whereDate('createdAt', '>=', $startDate)
                                                    //         ->whereDate('createdAt', '<=', $endDate);
                                                })
                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                    return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                                })
                                                ->count();
        $data['totalEngineerFilter'] = Dealer::where('fType', 'Engineer')
                                                ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                    // $query->whereDate('createdAt', '>=', $startDate)
                                                    //         ->whereDate('createdAt', '<=', $endDate);
                                                })
                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                    return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                                })
                                                ->count();
        $data['totalArchitectFilter'] = Dealer::where('fType', 'Architect')
                                                ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                    // $query->whereDate('createdAt', '>=', $startDate)
                                                    //         ->whereDate('createdAt', '<=', $endDate);
                                                })
                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                    return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                                })
                                                ->count();
        $data['totalMasonFilter'] = Dealer::where('fType', 'Mason')
                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                // $query->whereDate('createdAt', '>=', $startDate)
                                                //         ->whereDate('createdAt', '<=', $endDate);
                                            })
                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                            })
                                            ->count();
        $data['totalContractorFilter'] = Dealer::where('fType', 'Contractor')
                                                ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                    // $query->whereDate('createdAt', '>=', $startDate)
                                                    //         ->whereDate('createdAt', '<=', $endDate);
                                                })
                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                    return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                                })
                                                ->count();
        $data['totalConstructionFilter'] = Dealer::where('fType', 'Construction Firm')
                                                    ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                        // $query->whereDate('createdAt', '>=', $startDate)
                                                        //         ->whereDate('createdAt', '<=', $endDate);
                                                    })
                                                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                        return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                                                    })
                                                    ->count();

                                                    // ->whereIn('roleId',User::whichUserLogin())
        $totalLead = Lead::selectRaw('lType,moveStatus')->join('users','users.id','leads.userId')
                                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                            $query->whereDate('leads.createdAt', '>=', $startDate)
                                                                    ->whereDate('leads.createdAt', '<=', $endDate);
                                                        })
                                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                            return $query->whereIn('leads.userId',  User::getMarketingAdminEmployee());
                                                        })

                                                        ->get();

        $data['totalLead'] = count($totalLead);
        $data['materialLead'] = count($totalLead->where('lType', 'Material Lead'));
        $data['dealershipLead'] = count($totalLead->where('lType', 'Dealership Lead'));

        $data['materialLeadPending'] = count($totalLead->where('lType','Material Lead')->where('moveStatus','Pending'));
        $data['materialLeadConverted'] = count($totalLead->where('lType','Material Lead')->where('moveStatus','!=','Pending'));


        $data['dealershipLeadPending'] = count($totalLead->where('lType','Dealership Lead')->where('moveStatus','Pending'));
        $data['dealershipLeadConverted'] = count($totalLead->where('lType','Dealership Lead')->where('moveStatus','!=','Pending'));


        $totalComplain = Complaint::selectRaw('cType,cStatus')->join('users','users.id','complaints.userId')
                                    ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                        $query->whereDate('complaints.createdAt', '>=', $startDate)
                                                ->whereDate('complaints.createdAt', '<=', $endDate);
                                    })
                                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                        return $query->whereIn('complaints.userId',  User::getMarketingAdminEmployee());
                                    })
                                    ->get();

        $data['totalComplain'] =  count($totalComplain);
        $data['generalComplain'] = count($totalComplain->where('cType', 'General Complaint'));
        $data['qualityComplain'] = count($totalComplain->where('cType', 'Quality Complaint'));

        $data['generalComplainPending'] = count($totalComplain->where('cType', 'General Complaint')->where('cStatus', 'Pending'));
        $data['generalComplainSolved'] = count($totalComplain->where('cType', 'General Complaint')->where('cStatus', 'Solved'));

        $data['qualityComplainPending'] = count($totalComplain->where('cType', 'Quality Complaint')->where('cStatus', 'Pending'));
        $data['qualityComplainSolved'] = count($totalComplain->where('cType', 'Quality Complaint')->where('cStatus', 'Solved'));


        $data['totalFollowUP'] = FollowUp::join('users','users.id','follow_ups.userId')
                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                $query->whereDate('follow_ups.createdAt', '>=', $startDate)
                                                        ->whereDate('follow_ups.createdAt', '<=', $endDate);
                                            })
                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                return $query->whereIn('follow_ups.userId',  User::getMarketingAdminEmployee());
                                            })
                                            ->count();
        $data['FollowUpIsDone'] = FollowUp::join('users','users.id','follow_ups.userId')->where('fIsDone',1)
                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                $query->whereDate('follow_ups.createdAt', '>=', $startDate)
                                                        ->whereDate('follow_ups.createdAt', '<=', $endDate);
                                            })
                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                return $query->whereIn('follow_ups.userId',  User::getMarketingAdminEmployee());
                                            })

                                            ->count();
        $data['FollowUpIsNotDone'] = FollowUp::join('users','users.id','follow_ups.userId')->where('fIsDone',0)
                                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                $query->whereDate('follow_ups.createdAt', '>=', $startDate)
                                                        ->whereDate('follow_ups.createdAt', '<=', $endDate);
                                            })
                                            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                return $query->whereIn('follow_ups.userId',  User::getMarketingAdminEmployee());
                                            })
                                            ->count();


        $data['totalVisit']  = Schedule::join('users','users.id','schedules.userId')
                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('schedules.createdAt', '>=', $startDate)
                                                    ->whereDate('schedules.createdAt', '<=', $endDate);
                                        })
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                                        })
                                        ->count();

        $totalDealerVisit  = Schedule::selectRaw('schedulesStatus')->join('users','users.id','schedules.userId')->where('sType','Dealer Visit')
                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('schedules.createdAt', '>=', $startDate)
                                                    ->whereDate('schedules.createdAt', '<=', $endDate);
                                        })
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                                        })
                                        ->get();

        $data['totalDealerVisit'] = count($totalDealerVisit);

        $data['totalDealerVisitCompleted'] = count($totalDealerVisit->where('schedulesStatus', 'End'));
        $data['totalDealerVisitInProgress'] = count($totalDealerVisit->where('schedulesStatus', 'Start'));
        $data['totalDealerVisitPending'] = count($totalDealerVisit->where('schedulesStatus', 'Create'));
        $data['totalDealerVisitCancel'] = count($totalDealerVisit->where('schedulesStatus', 'Cancel'));


        $totalSiteVisit  = Schedule::selectRaw('schedulesStatus')->join('users','users.id','schedules.userId')
                                    ->where('sType','Site Visit')
                                    ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('schedules.createdAt', '>=', $startDate)
                                                    ->whereDate('schedules.createdAt', '<=', $endDate);
                                        })
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                                        })
                                        ->get();


        $data['totalSiteVisit'] = count($totalSiteVisit);

        $data['totalSiteVisitCompleted'] = count($totalSiteVisit->where('schedulesStatus', 'End'));
        $data['totalSiteVisitInProgress'] = count($totalSiteVisit->where('schedulesStatus', 'Start'));
        $data['totalSiteVisitPending'] = count($totalSiteVisit->where('schedulesStatus', 'Create'));
        $data['totalSiteVisitCancel'] = count($totalSiteVisit->where('schedulesStatus', 'Cancel'));


        $totalInfluencerSiteVisit  = Schedule::selectRaw('schedulesStatus')->join('users','users.id','schedules.userId')
                                                ->where('sType','Influencer Visit')
                                                ->whereIn('roleId',User::whichUserLogin())
                                                ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                    $query->whereDate('schedules.createdAt', '>=', $startDate)
                                                            ->whereDate('schedules.createdAt', '<=', $endDate);
                                                })
                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                    return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
                                                })

                                                ->get();

        $data['totalInfluencerSiteVisit'] = count($totalInfluencerSiteVisit);

        $data['totalInfluencerSiteVisitCompleted'] = count($totalInfluencerSiteVisit->where('schedulesStatus', 'End'));
        $data['totalInfluencerSiteVisitInProgress'] = count($totalInfluencerSiteVisit->where('schedulesStatus', 'Start'));
        $data['totalInfluencerSiteVisitPending'] = count($totalInfluencerSiteVisit->where('schedulesStatus', 'Create'));
        $data['totalInfluencerSiteVisitCancel'] = count($totalInfluencerSiteVisit->where('schedulesStatus', 'Cancel'));


        $data['totalMerchaindiesOrder'] = Merchandise::join('users','users.id','merchandises.userId')->where('mType','Order')
                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('merchandises.createdAt', '>=', $startDate)
                                                    ->whereDate('merchandises.createdAt', '<=', $endDate);
                                        })
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                        })
                                        ->count();
        $data['totalPendingMerchaindiesOrder'] = Merchandise::join('users','users.id','merchandises.userId')->where('mType','Order')->where('mStatus','Pending')
                                                ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                    $query->whereDate('merchandises.createdAt', '>=', $startDate)
                                                            ->whereDate('merchandises.createdAt', '<=', $endDate);
                                                })
                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                    return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                                })
                                                ->count();
        $data['totalApproveMerchaindiesOrder'] = Merchandise::join('users','users.id','merchandises.userId')->where('mType','Order')->where('mStatus','Approved')
                                                    ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                        $query->whereDate('merchandises.createdAt', '>=', $startDate)
                                                                ->whereDate('merchandises.createdAt', '<=', $endDate);
                                                    })
                                                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                        return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                                    })
                                                    ->count();
        $data['totalRejectMerchaindiesOrder'] = Merchandise::join('users','users.id','merchandises.userId')->where('mType','Order')->where('mStatus','Rejected')
                                                ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                    $query->whereDate('merchandises.createdAt', '>=', $startDate)
                                                            ->whereDate('merchandises.createdAt', '<=', $endDate);
                                                })
                                                ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                    return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                                })
                                                ->count();
        $data['totalDeliveredMerchaindiesOrder'] = Merchandise::join('users','users.id','merchandises.userId')
                                                    ->where('mType','Order')->where('mStatus','Delivered')
                                                    ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                                        $query->whereDate('merchandises.createdAt', '>=', $startDate)
                                                                ->whereDate('merchandises.createdAt', '<=', $endDate);
                                                    })
                                                    ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                                        return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                                    })
                                                    ->count();

        // $data['totalMerchaindiesGift'] = Merchandise::join('users','users.id','merchandises.userId')
        //                                     ->where('mType','Gift')
        //                                     ->whereIn('roleId',User::whichUserLogin())
        //                                     ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
        //                                         $query->whereDate('merchandises.createdAt', '>=', $startDate)
        //                                                 ->whereDate('merchandises.createdAt', '<=', $endDate);
        //                                     })->count();


        $totalPaySlip = RequestPayslips::selectRaw('rPStatus')->join('users','users.id','request_payslips.userId')
                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('request_payslips.createdAt', '>=', $startDate)
                                                    ->whereDate('request_payslips.createdAt', '<=', $endDate);
                                        })
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('request_payslips.userId',  User::getMarketingAdminEmployee());
                                        })
                                        ->get();

        $data['totalPaySlip'] = count($totalPaySlip);
        $data['totalPaySlipPending'] = count($totalPaySlip->where('rPStatus', 'Pending'));
        $data['totalPaySlipApproved'] = count($totalPaySlip->where('rPStatus', 'Approved'));
        $data['totalPaySlipRejected'] = count($totalPaySlip->where('rPStatus', 'Rejected'));


        $totalVanRequest = Knowledge::selectRaw('kStatus')->join('users','users.id','knowledges.userId')
                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('knowledges.createdAt', '>=', $startDate)
                                                    ->whereDate('knowledges.createdAt', '<=', $endDate);
                                        })
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
                                        })
                                        ->get();

        $data['totalVanRequest'] = count($totalVanRequest);
        $data['totalVanRequestPending'] = count($totalVanRequest->where('kStatus', 'Pending'));
        $data['totalVanRequestNotAvailable'] = count($totalVanRequest->where('kStatus', 'Not Available'));
        $data['totalVanRequestApproved'] = count($totalVanRequest->where('kStatus', 'Approved'));



        $expenceChat = Reimbursement::selectRaw('sum(totalAmount) as totalAmount,eName')
                                          ->join('expense_types','expense_types.id','reimbursements.expenseId')
                                    ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('reimbursements.createdAt', '>=', $startDate)
                                                    ->whereDate('reimbursements.createdAt', '<=', $endDate);
                                        })
                                        ->orderBy('totalAmount','desc')
                                        ->groupBy('reimbursements.expenseId')
                                        ->limit(10)
                                        ->get();

        $userBarChartData =  [['Name', 'Total Amount']];

        foreach ($expenceChat as $data1) {
            $userBarChartData[] =  [$data1->eName,$data1->totalAmount];
        }


        $data['userBarChartData'] = $userBarChartData;

        $visitPieChartData =  [['Visit', 'Type']];

        $visitPieChartData[] = ['Dealer Visit',count($totalDealerVisit)];
        $visitPieChartData[] = ['Site Visit',count($totalSiteVisit)];
        $visitPieChartData[] = ['Influencer Visit',count($totalInfluencerSiteVisit)];


        $data['visitPieChartData'] = $visitPieChartData;




        return view('admin.dashboard',$data);


        // return view('admin.dashboard', compact(
        //     'totalUsers', 'totalSalesUsers', 'totalMarketingUsers',
        //     'totalDepartments','totalBrochures','totalLeaveRequest',
        //     'totalPendingLeaveRequest', 'totalApprovedLeaveRequest', 'totalRejectedLeaveRequest',
        //     'totalReimbursements', 'totalApprovedReimbursements', 'totalPendingReimbursements',
        //     'totalRejectedReimbursements','totalDealers', 'totalDealerFilter', 'totalEngineerFilter',
        //     'totalArchitectFilter', 'totalMasonFilter', 'totalContractorFilter', 'totalConstructionFilter',
        //     'totalLead','materialLead','dealershipLead','totalComplain','generalComplain','qualityComplain',
        //     'totalFollowUP','FollowUpIsDone','FollowUpIsNotDone','totalVisit','totalDealerVisit','totalSiteVisit','totalInfluencerSiteVisit',
        //     'totalMerchaindiesOrder','totalPendingMerchaindiesOrder','totalApproveMerchaindiesOrder','totalRejectMerchaindiesOrder',
        //     'totalDeliveredMerchaindiesOrder','totalMerchaindiesGift'
        // ));
    }

     /**
     * Search Brand.
     *
     * @param Request $request
     *
     * @return json
     */
    public function dealerBirthDateDashboard(Request $request)
    {

        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $query = Dealer::selectRaw('dealers.name,users.fullName,DATE_FORMAT(dealers.dob, "' . config('constant.schedule_date_format') . '") as dobFormate,dealers.createdAt')
                            ->join('users','users.id','dealers.userId')

                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                $query->whereDate('dealers.dob', '>=', $startDate)
                                        ->whereDate('dealers.dob', '<=', $endDate);
                        })
                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
                        }) ;

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%')
                        ->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {
                $rows['data'][$key]['sr_no'] = $startNo + $key;
            }

            return response()->json($rows);
        }
    }


      /**
     * Search merchaindies .
     *
     * @param Request $request
     *
     * @return json
     */
    public function merchaindiesTypeDashboard(Request $request)
    {

        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $mType = $request->mType;
            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $query = Merchandise::selectRaw('merchandises.id,users.fullName,dealers.name,fType,itemQty,merchandises.createdAt,
                                            DATE_FORMAT(merchandises.createdAt, "' . config('constant.schedule_date_format') . '") as mDateFormate')
                                        ->join('users','users.id','merchandises.userId')
                                        ->join('dealers','dealers.id','merchandises.rjDealerId')
                                        ->where('mType',$mType)
                                        ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                                            return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
                                        })
                                        ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                            $query->whereDate('merchandises.createdAt', '>=', $startDate)
                                                    ->whereDate('merchandises.createdAt', '<=', $endDate);
                                        });

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                ->orWhere('dealers.name', 'like', '%' . $request->search['value'] . '%')
                ->orWhere('fType', 'like', '%' . $request->search['value'] . '%')
                ->orWhere('itemQty', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params1 = [
                    'merchandise' => $row['id'],
                    'type' =>  $mType
                ];

                $viewRoute   = route('merchandise.show', $params1);

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" target="_black" class="btn btn-primary" title="Merchandise Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
            }

            return response()->json($rows);
        }
    }

     /**
     * Search user performance .
     *
     * @param Request $request
     *
     * @return json
     */
    public function userPerformanceDashboard(Request $request)
    {

        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $query = InOut::selectRaw("users.id,users.fullName,MAX(in_outs.createdAt) as createdAt,count(in_outs.createdAt) as day,
                                        SUM(travelKm) as travelKm,
                                        (select count(id) from schedules where schedules.userId = users.id AND createdAt >= '$startDate' AND createdAt <= '$endDate') as visitCount,
                                        (select IF(AVG(totalAmount)>0,AVG(totalAmount),0) from reimbursements where reimbursements.userId = users.id AND createdAt >= '$startDate' AND createdAt <= '$endDate') as expense
                                    ")
                            ->join('users','users.id','in_outs.userId')
                            // ->where('users.roleId',config('constant.sales_executive_id'))
                            ->whereIn('users.roleId',User::whichUserLogin())
                            ->when(!empty($startDate) && !empty($endDate) , function ($query) use ($startDate,$endDate) {
                                $query->whereDate('in_outs.createdAt', '>=', $startDate)
                                        ->whereDate('in_outs.createdAt', '<=', $endDate);
                            })
                            ->groupBy('in_outs.userId');


            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {
                $rows['data'][$key]['sr_no'] = $startNo + $key;
                $rows['data'][$key]['travelKm'] = round($row['travelKm'],2);
                $rows['data'][$key]['expense'] = round($row['expense'],2);
            }

            return response()->json($rows);
        }
    }


    /**
     * chat
     *
     * @param  string $otherUserId
     *
     * @return void
     */
    public function chat(Request $request)
    {
        $otherUserId =$request->otherUserId ?? 0;
        return view('admin.chat',compact('otherUserId'));
    }
}
