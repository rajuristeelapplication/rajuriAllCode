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


                        @endif




                    @if( (Auth::user()->roleId == config('constant.ma_id')) )
                    <div class="col-span-12 sm:col-span-8 xl:col-span-4 intro-y">
                        <div class="report-box">
                            <a href="{{ route('users.index').'/all' }}">
                                <div class="box">
                                    <div class="d-flex">
                                        <i class="feather-users report-box__icon col-red"></i>
                                    </div>

                                    <div class="text-3xl font-bold leading-8 mt-6">{{$totalMarketingUsers ?? 0}}</div>
                                    <div class="text-base text-gray-600 mt-1">{{ __('labels.total_users') }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="col-span-12 sm:col-span-6 xl:col-span-4 xxl:col-span-3 intro-y">
                        <div class="report-box">
                            <a href="{{ route('users.index').'/all' }}">
                                <div class="box">
                                    <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                        <div class="text-base font-bold leading-8 mr-2">{{$totalUsers ?? 0}}</div>
                                        <div class="text-base font-medium">{{ __('labels.total_users') }}</div>
                                    </div>

                                    <div class="row text-center p-3">
                                        <div class="col report-box__indicator cursor-pointer">
                                            <div class="text-3xl font-bold leading-8">{{$totalSalesUsers ?? 0}}</div>
                                            <div class="font-medium text-gray-600">{{ __('labels.sales') }} </div>
                                        </div>
                                        <div class="col report-box__indicator  cursor-pointer">
                                            <div class="text-3xl font-bold leading-8">{{$totalMarketingUsers ?? 0}} </div>
                                            <div class="font-medium text-gray-600">{{ __('labels.marketing') }} </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endif


                    @if(Auth::user()->roleId != config('constant.hr_id'))

                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 xxl:col-span-3 intro-y">
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
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$dealershipLead ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_dealership_lead') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 xxl:col-span-3 intro-y">
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
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$qualityComplain ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_quality_complain') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <div class="col-span-12 sm:col-span-6 xl:col-span-4 xxl:col-span-3 intro-y">
                            <div class="report-box">
                                <a href="{{ route('followups.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalFollowUP ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_follow_up') }}</div>
                                        </div>
                                        <div class="row text-center p-3">
                                            <div class="col report-box__indicator cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$FollowUpIsDone ?? 0}}</div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_follow_up_is_done') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$FollowUpIsNotDone ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_follow_up_is_not_done') }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-8 xxl:col-span-8 xxl:col-span-12 intro-y">
                            <div class="report-box">
                                <a href="{{ route('dealers.index') }}">
                                    <div class="box">
                                        <div class="d-flex align-items-center justify-content-center text-center p-3 border-bottom">
                                            <div class="text-base font-bold leading-8 mr-2">{{$totalDealers ?? 0}}</div>
                                            <div class="text-base text-gray-600 font-medium">{{ __('labels.total_dealers') }}</div>
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
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalSiteVisit ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_site_visit') }} </div>
                                            </div>
                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold leading-8">{{$totalInfluencerSiteVisit ?? 0}} </div>
                                                <div class="font-medium text-gray-600">{{ __('labels.total_influencer_site_visit') }} </div>
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
                                                <div class="font-medium text-warning">{{ __('labels.total_orders_pending') }} </div>
                                            </div>

                                            <div class="col report-box__indicator  cursor-pointer">
                                                <div class="text-3xl font-bold text-success leading-8">{{$totalVanRequestApproved ?? 0}} </div>
                                                <div class="font-medium text-success">{{ __('labels.total_orders_success') }} </div>
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



                    @endif


                    @if( (Auth::user()->roleId != config('constant.ma_id')) )

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
                    @endif

                    <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                        <div class="report-box">
                            <a href="{{ route('merchandise.index',['type' => 'gift']) }}">
                                <div class="box">
                                    <div class="d-flex align-items-center justify-content-center text-center p-3">
                                        <div class="text-base font-bold leading-8 mr-2">{{$totalMerchaindiesGift ?? 0}}</div>
                                        <div class="text-base text-gray-600 font-medium">{{ __('labels.total_gift_orders') }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    @if( (Auth::user()->roleId != config('constant.hr_id')) && (Auth::user()->roleId != config('constant.ma_id')) )

                    <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                        <div class="report-box">
                            <a href="{{ route('departments.index') }}">
                                <div class="box">
                                    <div class="d-flex align-items-center justify-content-center text-center p-3">
                                        <div class="text-base font-bold leading-8 mr-2">{{$totalDepartments ?? 0}}</div>
                                        <div class="text-base text-gray-600 font-medium">{{ __('labels.total_department') }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y">
                        <div class="report-box">
                            <a href="{{ route('brochures.index') }}">
                                <div class="box">
                                    <div class="d-flex align-items-center justify-content-center text-center p-3">
                                        <div class="text-base font-bold leading-8 mr-2">{{$totalBrochures ?? 0}}</div>
                                        <div class="text-base text-gray-600 font-medium">{{ __('labels.total_brochure') }}</div>
                                    </div>
                                </div>
                            </a>
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

<script>

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
