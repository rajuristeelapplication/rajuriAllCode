@extends('admin.layout.index')

@section('title') {{ __('labels.dashboard') }} @endsection
@section('css')

<link href="{{ url('admin-assets/css/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <span class="breadcrumb--active">{{ __('labels.dashboard') }} </span>
    </div>
    @endsection
    @include('admin.common.notification')
    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">

                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="daterange"><span class="fa fa-filter mb-2"></span> Calender </label>
                        <input type="text" class="form-control mt-2" id="daterange" name="daterange" value="{{ $selectedDate  ?? ''}}"
                            placeholder="Select Date Range" readonly="readonly">
                    </div>

                    <div class="form-group col-md-4 mt-8">
                            <a  href="{{ route('AdminDashboard') }}" class="btn btn-danger"  title="Filter Reset">
                                <i class="fa fa-filter" aria-hidden="true"></i>Filter Reset
                            </a>
                    </div>


                 </div>

                    <div class="intro-y d-flex align-items-center h-10">
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-4">

                        @if(Auth::user()->roleId == config('constant.admin_id'))

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('schedules.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalVisit ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_visit') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalDealerVisit ?? 0}}</div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_dealer_visit') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$totalDealerVisitPending ?? 0}}</span>
                                                    <span class="text-warning">InProgress:-  {{$totalDealerVisitInProgress ?? 0}}</span>
                                                    <span class="text-success">Complete:- {{$totalDealerVisitCompleted ?? 0}}</span>
                                                    <span class="text-danger">Cancel:- {{$totalDealerVisitCancel ?? 0}}</span>
                                                </div>

                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalSiteVisit ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_site_visit') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$totalSiteVisitPending ?? 0}}</span>
                                                    <span class="text-warning">InProgress:-  {{$totalSiteVisitInProgress ?? 0}}</span>
                                                    <span class="text-success">Complete:- {{$totalSiteVisitCompleted ?? 0}}</span>
                                                    <span class="text-danger">Cancel:- {{$totalSiteVisitCancel ?? 0}}</span>
                                                </div>

                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalInfluencerSiteVisit ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_influencer_site_visit') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$totalInfluencerSiteVisitPending ?? 0}}</span>
                                                    <span class="text-warning">InProgress:-  {{$totalInfluencerSiteVisitInProgress ?? 0}}</span>
                                                    <span class="text-success">Complete:- {{$totalInfluencerSiteVisitCompleted ?? 0}}</span>
                                                    <span class="text-danger">Cancel:- {{$totalInfluencerSiteVisitCancel ?? 0}}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('merchandise.index',['type' => 'order']) }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalMerchaindiesOrder ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium"> Merchandise {{ __('labels.total_orders') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalPendingMerchaindiesOrder ?? 0}}</div>
                                                <div class="font-medium text-warning">{{ __('labels.total_orders_pending') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalApproveMerchaindiesOrder ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.total_orders_success') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalDeliveredMerchaindiesOrder ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.total_orders_delivered') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalRejectMerchaindiesOrder ?? 0}} </div>
                                                <div class="font-medium text-danger">{{ __('labels.total_orders_reject') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-4 xl:col-span-4 xxl:col-span-4 intro-y">
                            <div class="report-box">
                                <a href="{{ route('leads.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalLead ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_lead') }}</div>



                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$materialLead ?? 0}}</div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_material_lead') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$materialLeadPending ?? 0}}</span>
                                                    <span class="text-success">Converted:-  {{$materialLeadConverted ?? 0}}</span>
                                                </div>

                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$dealershipLead ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_dealership_lead') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$dealershipLeadPending ?? 0}}</span>
                                                    <span class="text-success">Converted:-  {{$dealershipLeadConverted ?? 0}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-4 xl:col-span-4 xxl:col-span-4 intro-y">
                            <div class="report-box">
                                <a href="{{ route('complaints.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalComplain ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_complain') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$generalComplain ?? 0}}</div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_general_complain') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$generalComplainPending ?? 0}}</span>
                                                    <span class="text-success">Solved:-  {{$generalComplainSolved ?? 0}}</span>
                                                </div>

                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$qualityComplain ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_quality_complain') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$qualityComplainPending ?? 0}}</span>
                                                    <span class="text-success">Solved:-  {{$qualityComplainSolved ?? 0}}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-4 xl:col-span-4 xxl:col-span-4 intro-y">
                            <div class="report-box">
                                <a href="{{ route('followups.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalFollowUP ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_follow_up') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold leading-8 text-success">{{$FollowUpIsDone ?? 0}}</div>
                                                <div class="font-medium text-gray-600 text-success">{{ __('labels.total_follow_up_is_done') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8 text-danger">{{$FollowUpIsNotDone ?? 0}} </div>
                                                <div class="font-medium text-gray-600 text-danger">{{ __('labels.total_follow_up_is_not_done') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-12 xxl:col-span-12 xxl:col-span-12 intro-y">
                            <div class="report-box">
                                <a href="{{ route('dealers.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalDealers ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">Actor</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalDealerFilter ?? 0}}</div>
                                                <div class="font-medium text-gray-600">{{ __('labels.dealer_filter') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalEngineerFilter ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.engineer_filter') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalArchitectFilter ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.architect_filter') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalMasonFilter ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.mason_filter') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalContractorFilter ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.contractor_filter') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalConstructionFilter ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.construction_filter') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('requestPaySlip') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalPaySlip ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">PaySlip</div>
                                        </div>
                                        <div class="row text-center p-3">

                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalPaySlipPending ?? 0}}</div>
                                                <div class="font-medium text-warning">{{ __('labels.total_orders_pending') }} </div>
                                            </div>

                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalPaySlipApproved ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.total_orders_success') }} </div>
                                            </div>

                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalPaySlipRejected ?? 0}} </div>
                                                <div class="font-medium text-danger">{{ __('labels.total_orders_reject') }} </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('knowledge.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalVanRequest ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">Van Request</div>
                                        </div>
                                        <div class="row text-center p-3">

                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalVanRequestPending ?? 0}}</div>
                                                <div class="font-medium text-warning">Pending </div>
                                            </div>

                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalVanRequestApproved ?? 0}} </div>
                                                <div class="font-medium text-success">Approved</div>
                                            </div>

                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalVanRequestNotAvailable ?? 0}} </div>
                                                <div class="font-medium text-danger">Not Available </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('leaves.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalLeaveRequest ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_leave_request') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalPendingLeaveRequest ?? 0}}</div>
                                                <div class="font-medium text-warning">{{ __('labels.pending') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalApprovedLeaveRequest ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.approved') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalRejectedLeaveRequest ?? 0}} </div>
                                                <div class="font-medium text-danger">{{ __('labels.rejected') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('reimbursements.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalReimbursements ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_reimbursements') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalPendingReimbursements ?? 0}}</div>
                                                <div class="font-medium text-warning">{{ __('labels.pending') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalApprovedReimbursements ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.approved') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalRejectedReimbursements ?? 0}} </div>
                                                <div class="font-medium text-danger">{{ __('labels.rejected') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-6 intro-y" style="pointer-events:none;">
                            <div class="report-box">
                                <a href="javascript:;">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$attendanceTotalUser ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">Total Attendance</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$attendancePresent ?? 0}}</div>
                                                <div class="font-medium text-warning">Present </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$attendanceLeave ?? 0}} </div>
                                                <div class="font-medium text-success">Leave </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$attendanceAbsent ?? 0}} </div>
                                                <div class="font-medium text-danger">Absent </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-12 intro-y">
                            <div class="report-box">

                                <div class="box">

                                    <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                        <div class="text-base font-bold leading-8 mr-2">Birthday</div>
                                    </div>

                                    <div class="p-3">
                                        <table id="birthday" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="7%">{{__('labels.no')}}</th>
                                                    <th>{{ __('labels.create_at') }}</th>
                                                    <th>Name</th>
                                                    <th>Actor Name</th>
                                                    <th width="10px">Date Of Birth</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                {{-- @if(count($dobDealerUser) > 0)
                                                @foreach ($dobDealerUser as $key=>$users )

                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $users->name ?? '' }}</td>
                                                        <td>{{ $users->fullName ?? '' }}</td>
                                                        <td>{{ $users->dobFormate ?? '' }}</td>
                                                    </tr>

                                                @endforeach


                                                @endif --}}

                                            </tbody>

                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-12 intro-y">
                            <div class="report-box">

                                <div class="box">

                                    <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                        <div class="text-base font-bold leading-8 mr-2">Gift Records</div>
                                    </div>

                                    <div class="p-3">

                                        <table id="adminGift" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="7%">{{__('labels.no')}}</th>
                                                    <th>{{ __('labels.create_at') }}</th>
                                                    <th>Executive Name</th>
                                                    <th>Actor Name</th>
                                                    <th>Actor Type</th>
                                                    <th> Item Qty</th>
                                                    <th>Created On</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>

                                </div>

                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-12 intro-y">
                            <div class="report-box">

                                <div class="box">

                                    <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                        <div class="text-base font-bold leading-8 mr-2">User Performance</div>
                                    </div>

                                    <div class="p-3">

                                        <table id="userPerformanace" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="7%">{{__('labels.no')}}</th>
                                                    <th>{{ __('labels.create_at') }}</th>
                                                    <th>Executive Name</th>
                                                    <th>Total Day</th>
                                                     <th>kms (Total)</th>
                                                  {{--  <th>Expense (avg)</th> --}}
                                                    <th>Visits</th>
                                                    {{-- <th>Avg Visit/Day</th>
                                                    <th>Avg Expense/Visit</th> --}}
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>

                                </div>

                            </div>
                        </div>

{{--
                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">

                                <div class="box">

                                    <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                        <div class="text-base font-bold leading-8 mr-2">Visit Pie Chart</div>
                                    </div>

                                    <div class="p-5 box">

                                        <div id="visitPie" style="width: 100%; height: 500px;"></div>

                                    </div>



                                </div>

                            </div>

                        </div> --}}


                        @endif


                        @if(Auth::user()->roleId == config('constant.ma_id'))

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('schedules.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalVisit ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_visit') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalDealerVisit ?? 0}}</div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_dealer_visit') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$totalDealerVisitPending ?? 0}}</span>
                                                    <span class="text-warning">InProgress:-  {{$totalDealerVisitInProgress ?? 0}}</span>
                                                    <span class="text-success">Complete:- {{$totalDealerVisitCompleted ?? 0}}</span>
                                                    <span class="text-danger">Cancel:- {{$totalDealerVisitCancel ?? 0}}</span>
                                                </div>

                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalSiteVisit ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_site_visit') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$totalSiteVisitPending ?? 0}}</span>
                                                    <span class="text-warning">InProgress:-  {{$totalSiteVisitInProgress ?? 0}}</span>
                                                    <span class="text-success">Complete:- {{$totalSiteVisitCompleted ?? 0}}</span>
                                                    <span class="text-danger">Cancel:- {{$totalSiteVisitCancel ?? 0}}</span>
                                                </div>

                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalInfluencerSiteVisit ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_influencer_site_visit') }} </div>

                                                <div class="d-flex flex-wrap w-100 inner-report-box">
                                                    <span class="text-warning">Pending:-  {{$totalInfluencerSiteVisitPending ?? 0}}</span>
                                                    <span class="text-warning">InProgress:-  {{$totalInfluencerSiteVisitInProgress ?? 0}}</span>
                                                    <span class="text-success">Complete:- {{$totalInfluencerSiteVisitCompleted ?? 0}}</span>
                                                    <span class="text-danger">Cancel:- {{$totalInfluencerSiteVisitCancel ?? 0}}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                            <div class="col-span-12 sm:col-span-6 intro-y">
                                <div class="report-box">
                                    <a href="{{ route('merchandise.index',['type' => 'order']) }}">
                                        <div class="box">
                                            <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                                <div class="text-base font-bold leading-8 mr-2">{{$totalMerchaindiesOrder ?? 0}}</div>
                                                <div class="text-base text-gray-600 font-medium">{{ __('labels.total_orders') }}</div>
                                            </div>
                                            <div class="row text-center p-3">
                                                <div class="col report-box__indicator cursor-pointer">
                                                    <div class="text-3xl font-bold text-warning leading-8">{{$totalPendingMerchaindiesOrder ?? 0}}</div>
                                                    <div class="font-medium text-warning">{{ __('labels.total_orders_pending') }} </div>
                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold text-success leading-8">{{$totalApproveMerchaindiesOrder ?? 0}} </div>
                                                    <div class="font-medium text-success">{{ __('labels.total_orders_success') }} </div>
                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold text-success leading-8">{{$totalDeliveredMerchaindiesOrder ?? 0}} </div>
                                                    <div class="font-medium text-success">{{ __('labels.total_orders_delivered') }} </div>
                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold text-danger leading-8">{{$totalRejectMerchaindiesOrder ?? 0}} </div>
                                                    <div class="font-medium text-danger">{{ __('labels.total_orders_reject') }} </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-4 xl:col-span-4 xxl:col-span-4 intro-y">
                                <div class="report-box">
                                    <a href="{{ route('leads.index') }}">
                                        <div class="box">
                                            <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                                <div class="text-base font-bold leading-8 mr-2">{{$totalLead ?? 0}}</div>
                                                <div class="text-base text-gray-600 font-medium">{{ __('labels.total_lead') }}</div>
                                            </div>
                                            <div class="row text-center p-3">
                                                <div class="col report-box__indicator cursor-pointer">
                                                    <div class="text-3xl font-bold leading-8">{{$materialLead ?? 0}}</div>
                                                    <div class="font-medium text-gray-600">{{ __('labels.total_material_lead') }} </div>

                                                    <div class="d-flex flex-wrap w-100 inner-report-box">
                                                        <span class="text-warning">Pending:-  {{$materialLeadPending ?? 0}}</span>
                                                        <span class="text-success"Converted:-  {{$materialLeadConverted ?? 0}}</span>
                                                    </div>

                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold leading-8">{{$dealershipLead ?? 0}} </div>
                                                    <div class="font-medium text-gray-600">{{ __('labels.total_dealership_lead') }} </div>

                                                    <div class="d-flex flex-wrap w-100 inner-report-box">
                                                        <span class="text-warning">Pending:-  {{$dealershipLeadPending ?? 0}}</span>
                                                        <span class="text-success">Converted:-  {{$dealershipLeadConverted ?? 0}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-4 xl:col-span-4 xxl:col-span-4 intro-y">
                                <div class="report-box">
                                    <a href="{{ route('complaints.index') }}">
                                        <div class="box">
                                            <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                                <div class="text-base font-bold leading-8 mr-2">{{$totalComplain ?? 0}}</div>
                                                <div class="text-base text-gray-600 font-medium">{{ __('labels.total_complain') }}</div>
                                            </div>
                                            <div class="row text-center p-3">
                                                <div class="col report-box__indicator cursor-pointer">
                                                    <div class="text-3xl font-bold leading-8">{{$generalComplain ?? 0}}</div>
                                                    <div class="font-medium text-gray-600">{{ __('labels.total_general_complain') }} </div>

                                                    <div class="d-flex flex-wrap w-100 inner-report-box">
                                                        <span class="text-warning">Pending:-  {{$generalComplainPending ?? 0}}</span>
                                                        <span class="text-success">Solved:-  {{$generalComplainSolved ?? 0}}</span>
                                                    </div>

                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold leading-8">{{$qualityComplain ?? 0}} </div>
                                                    <div class="font-medium text-gray-600">{{ __('labels.total_quality_complain') }} </div>

                                                    <div class="d-flex flex-wrap w-100 inner-report-box">
                                                        <span class="text-warning" >Pending:-  {{$qualityComplainPending ?? 0}}</span>
                                                        <span class="text-success">Solved:-  {{$qualityComplainSolved ?? 0}}</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6 xl:col-span-4 xxl:col-span-4 intro-y">
                                <div class="report-box">
                                    <a href="{{ route('followups.index') }}">
                                        <div class="box">
                                            <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                                <div class="text-base font-bold leading-8 mr-2 ">{{$totalFollowUP ?? 0}}</div>
                                                <div class="text-base text-gray-600 font-medium ">{{ __('labels.total_follow_up') }}</div>
                                            </div>
                                            <div class="row text-center p-3">
                                                <div class="col report-box__indicator cursor-pointer">
                                                    <div class="text-3xl font-bold leading-8 text-success">{{$FollowUpIsDone ?? 0}}</div>
                                                    <div class="font-medium text-gray-600 text-success">{{ __('labels.total_follow_up_is_done') }} </div>
                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold leading-8 text-danger">{{$FollowUpIsNotDone ?? 0}} </div>
                                                    <div class="font-medium text-gray-600 text-danger">{{ __('labels.total_follow_up_is_not_done') }} </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6 intro-y">
                                <div class="report-box">
                                    <a href="{{ route('knowledge.index') }}">
                                        <div class="box">
                                            <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                                <div class="text-base font-bold leading-8 mr-2">{{$totalVanRequest ?? 0}}</div>
                                                <div class="text-base text-gray-600 font-medium">Van Request</div>
                                            </div>
                                            <div class="row text-center p-3">

                                                <div class="col report-box__indicator cursor-pointer">
                                                    <div class="text-3xl font-bold text-warning leading-8">{{$totalVanRequestPending ?? 0}}</div>
                                                    <div class="font-medium text-warning">Pending </div>
                                                </div>

                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold text-success leading-8">{{$totalVanRequestApproved ?? 0}} </div>
                                                    <div class="font-medium text-success">Approved </div>
                                                </div>

                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold text-danger leading-8">{{$totalVanRequestNotAvailable ?? 0}} </div>
                                                    <div class="font-medium text-danger">Not Available </div>
                                                </div>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>


                            <div class="col-span-12 sm:col-span-6 intro-y" style="pointer-events:none;">
                                <div class="report-box">
                                    <a href="javascript:;">
                                        <div class="box">
                                            <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                                <div class="text-base font-bold leading-8 mr-2">{{$attendanceTotalUser ?? 0}}</div>
                                                <div class="text-base text-gray-600 font-medium">Total Attendance</div>
                                            </div>
                                            <div class="row text-center p-3">
                                                <div class="col report-box__indicator cursor-pointer">
                                                    <div class="text-3xl font-bold text-warning leading-8">{{$attendancePresent ?? 0}}</div>
                                                    <div class="font-medium text-warning">Present </div>
                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold text-success leading-8">{{$attendanceLeave ?? 0}} </div>
                                                    <div class="font-medium text-success">Leave </div>
                                                </div>
                                                <div class="col report-box__indicator  cursor-pointer">
                                                    <div class="text-3xl font-bold text-danger leading-8">{{$attendanceAbsent ?? 0}} </div>
                                                    <div class="font-medium text-danger">Absent </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-12 intro-y">
                                <div class="report-box">

                                    <div class="box">

                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">Birthday</div>
                                        </div>

                                        <div class="p-3">
                                            <table id="birthday" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="7%">{{__('labels.no')}}</th>
                                                        <th>{{ __('labels.create_at') }}</th>
                                                        <th>Name</th>
                                                        <th>Actor Name</th>
                                                        <th width="10px">Date Of Birth</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    {{-- @if(count($dobDealerUser) > 0)
                                                    @forelse ($dobDealerUser as $key=>$users)

                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $users->name ?? '' }}</td>
                                                        <td>{{ $users->fullName ?? '' }}</td>
                                                        <td>{{ $users->dobFormate ?? '' }}</td>
                                                    </tr>

                                                    @empty


                                                    @endforelse

                                                    @endif --}}

                                                </tbody>

                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="col-span-12 sm:col-span-12 intro-y">
                                <div class="report-box">

                                    <div class="box">

                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">Gift Records</div>
                                        </div>

                                        <div class="p-3">
                                            <table id="adminGift" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied" cellspacing="0"
                                                width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="7%">{{__('labels.no')}}</th>
                                                            <th>{{ __('labels.create_at') }}</th>
                                                            <th>Executive Name</th>
                                                            <th>Actor Name</th>
                                                            <th>Actor Type</th>
                                                            <th> Item Qty</th>
                                                            <th>Created On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                <tbody>
                                                </tbody>

                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-12 intro-y">
                                <div class="report-box">

                                    <div class="box">

                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">Order Records</div>
                                        </div>

                                        <div class="p-3">
                                            <table id="adminOrder" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="7%">{{__('labels.no')}}</th>
                                                        <th>{{ __('labels.create_at') }}</th>
                                                        <th>Executive Name</th>
                                                        <th>Actor Name</th>
                                                        <th>Actor Type</th>
                                                        <th> Item Qty</th>
                                                        <th>Created On</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>



                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-12 intro-y">
                                <div class="report-box">

                                    <div class="box">

                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">User Performance</div>
                                        </div>

                                        <div class="p-3">

                                            <table id="userPerformanace" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="7%">{{__('labels.no')}}</th>
                                                        <th>{{ __('labels.create_at') }}</th>
                                                        <th>Marketing Executive</th>
                                                        <th>Total Day</th>
                                                         <th>kms (Total)</th>
                                                        {{-- <th>Expense (avg)</th> --}}
                                                        <th>Visits</th>
                                                        {{-- <th>Avg Visit/Day</th>
                                                        <th>Avg Expense/Visit</th> --}}
                                                    </tr>
                                                </thead>
                                            </table>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->roleId == config('constant.hr_id'))

                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('leaves.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalLeaveRequest ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_leave_request') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalPendingLeaveRequest ?? 0}}</div>
                                                <div class="font-medium text-warning">{{ __('labels.pending') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalApprovedLeaveRequest ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.approved') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalRejectedLeaveRequest ?? 0}} </div>
                                                <div class="font-medium text-danger">{{ __('labels.rejected') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('reimbursements.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalReimbursements ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_reimbursements') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalPendingReimbursements ?? 0}}</div>
                                                <div class="font-medium text-warning">{{ __('labels.pending') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalApprovedReimbursements ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.approved') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalRejectedReimbursements ?? 0}} </div>
                                                <div class="font-medium text-danger">{{ __('labels.rejected') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">
                                <a href="{{ route('requestPaySlip') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalPaySlip ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">PaySlip</div>
                                        </div>
                                        <div class="row text-center p-3">

                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$totalPaySlipPending ?? 0}}</div>
                                                <div class="font-medium text-warning">{{ __('labels.total_orders_pending') }} </div>
                                            </div>

                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalPaySlipApproved ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.total_orders_success') }} </div>
                                            </div>

                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$totalPaySlipRejected ?? 0}} </div>
                                                <div class="font-medium text-danger">{{ __('labels.total_orders_reject') }} </div>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-6 intro-y" style="pointer-events:none;">
                            <div class="report-box">
                                <a href="javascript:;">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$attendanceTotalUser ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">Total Attendance</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold text-warning leading-8">{{$attendancePresent ?? 0}}</div>
                                                <div class="font-medium text-warning">Present </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$attendanceLeave ?? 0}} </div>
                                                <div class="font-medium text-success">Leave </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-danger leading-8">{{$attendanceAbsent ?? 0}} </div>
                                                <div class="font-medium text-danger">Absent </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-6 intro-y">
                            <div class="report-box">

                                <div class="box">

                                    <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                        <div class="text-base font-bold leading-8 mr-2">Top 10 Expence Categories</div>
                                    </div>

                                    <div class="p-5 box">

                                        <div id="userBar" style="width: 100%; height: 500px;"></div>

                                    </div>



                                </div>

                            </div>

                        </div>

                        @endif

                </div>



            </div>
            <!-- END: General Report -->
        </div>
    </div>
</div>
<!-- END: Content -->
@endsection
@section('js')

<script type="text/javascript" src="{{ url('admin-assets/js/daterangepicker.min.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>


google.charts.load('current', {'packages':['corechart','bar','corechart']});

google.charts.setOnLoadCallback(userBarChart);

/*
google.charts.setOnLoadCallback(VisitPieChart);


function VisitPieChart() {
            var data = google.visualization.arrayToDataTable({!! json_encode($visitPieChartData) !!});

            var options = {
                title: '',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('visitPie'));

            chart.draw(data, options);
}*/






//-------------------------------User-BAR-CHART------------------------------------------

function userBarChart() {

var data = google.visualization.arrayToDataTable({!! json_encode($userBarChartData) !!});

        var options = {

            // width: 600,

            // height: 400,

            bar: {groupWidth: "10%"},

            legend: { position: "none" },



            chart: {

                title: '',
            },

            colors:['#47456d'],

            bars: 'vertical',

            legend: { position: 'bottom' }

        };

        var chart = new google.charts.Bar(document.getElementById('userBar'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

}



var dealerBirthday;
var adminGift;
var adminOrder;
var userPerformance;

    $(function() {


        userPerformance = $('#userPerformanace').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('userPerformanceDashboard')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function(d) {
                    d.startDate = "{{$startDate}}";
                    d.endDate = "{{$endDate}}";
                },

            },
            columns: [
                {
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": false,
                },
                {
                    data: 'fullName',
                    name: 'fullName',
                },
                {
                    data: 'day',
                    name: 'day',
                },
                {
                    data: 'travelKm',
                    name: 'travelKm',
                },
                // {
                //     data: 'expense',
                //     name: 'expense',
                // },
                {
                    data: 'visitCount',
                    name: 'visitCount',
                },
            ],
            "aaSorting": [
                [1, 'asc']
            ],
            "pageLength": 50
        });



        dealerBirthday = $('#birthday').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('dealerBirthDateDashboard')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function(d) {
                    d.startDate = "{{$startDate}}";
                    d.endDate = "{{$endDate}}";
                },

            },
            columns: [
                {
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": false,
                },
                {
                    data: 'fullName',
                    name: 'fullName',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'dobFormate',
                    name: 'dobFormate',
                },

            ],
            "aaSorting": [
                [1, 'asc']
            ],
            "pageLength": 10
        });



        adminGift = $('#adminGift').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('merchaindiesTypeDashboard')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function(d) {
                    d.mType = "Gift",
                    d.startDate = "{{$startDate}}";
                    d.endDate = "{{$endDate}}";
                },

            },
            columns: [
                {
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": false,
                },
                {
                    data: 'fullName',
                    name: 'fullName',

                },
                {
                    data: 'name',
                    name: 'name',

                },
                {
                    data: 'fType',
                    name: 'fType',

                },

                {
                    data: 'itemQty',
                    name: 'itemQty',

                },
                {
                    data: 'mDateFormate',
                    name: 'createdAt',

                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },

            ],
            "aaSorting": [
                [1, 'desc']
            ],
            "pageLength": 10
        });


        adminGift = $('#adminOrder').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('merchaindiesTypeDashboard')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function(d) {
                    d.mType = "Order",
                    d.startDate = "{{$startDate}}";
                    d.endDate = "{{$endDate}}";
                },

            },
            columns: [
                {
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": false,
                },
                {
                    data: 'fullName',
                    name: 'fullName',

                },
                {
                    data: 'name',
                    name: 'name',

                },
                {
                    data: 'fType',
                    name: 'fType',

                },

                {
                    data: 'itemQty',
                    name: 'itemQty',

                },
                {
                    data: 'mDateFormate',
                    name: 'createdAt',

                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },

            ],
            "aaSorting": [
                [1, 'desc']
            ],
            "pageLength": 10
        });


        // table1 = $('#adminGift').DataTable({
        //     "pageLength": 10
        // });

        // table2 = $('#adminOrder').DataTable({
        //     "pageLength": 10
        // });

    });

var date = new Date();
    date.setMonth(date.getMonth() - 12);

$('#daterange').daterangepicker({
       'minDate': date,
        'maxDate': new Date(),
        // 'dateLimit': {
        //     days: 91
        // },
        locale: {
            format: 'DD/MM/YYYY'
        },
    });


    @if(empty($selectedDate))
         $('#daterange').val('Please Select Date');
    @endif

$(document).on("click",".applyBtn",function() {

    var dateValue = $('#daterange').val();
    var dateValue = dateValue.replace(/ /g,'');

    var dateValue = dateValue.replace(/\//g, "$");

    let url = '{{route('AdminDashboard', ':queryId')}}';
    url = url.replace(':queryId', dateValue);
    window.location.href=url;

});

</script>
@endsection
