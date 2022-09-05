@extends('admin.layout.index')
@section('title') {{$type == "other" ? __('labels.reimbursement_report_title') : ucfirst($type) .' Report'}} @endsection
@section('css')
    <link href="{{ url('admin-assets/css/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')


    <!-- BEGIN: Content -->
    <div class="content">
        <!-- BEGIN: Top Bar -->
    @section('navigation')
        <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
            <a href="{{ route('AdminDashboard') }}"><span class="">{{__('labels.dashboard')}}</span></a>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <a href=""><span class="">{{__('labels.reports')}}</span></a>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <a href="{{ route('admin.reimbursementReport') }}">
                <span class="breadcrumb--active"> {{$type == "other" ? __('labels.reimbursement_report_title') : ucfirst($type) .' Report'}}</span>
            </a>
        </div>

    @endsection
    @include('admin.common.notification')
    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6 p-5 bg-white">
        <div class="col-span-12 xxl:col-span-9 grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{$type == "other" ? __('labels.reimbursement_report_title') : ucfirst($type) .' Report'}}</h2>
                </div>
                <br>
                @include('admin.common.flash')
                <div class="container">


                    <form class="form" target="_blank" name="reportReimbursements" id="reportReimbursements" method="post"
                        enctype="multipart/form-data" action="{{ route('admin.reimbursementReport') }}">

                        {{ csrf_field() }}

                        <div class="row">

                        @if(Auth::user()->roleId == config('constant.ma_id'))
                        <div class="form-group col-md-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.users_type') }}</label>
                            <select id="userType" name="userType" class="form-select form-select mt-2 userType">
                                <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                                </option>
                            </select>
                        </div>
                        @else
                        <div class="form-group col-md-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.users_type') }}</label>
                            <select id="userType" name="userType" class="form-select form-select mt-2 userType">
                                <option value="">{{ __('labels.all_user_type') }}</option>
                                <option value="{{config('constant.sales_executive_id')}}">{{ __('labels.sales_executive') }}</option>
                                <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                                </option>
                            </select>
                        </div>
                        @endif

                        <br>

                        @if(count($userNameList) > 0)

                        <div class="form-group col-md-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> Executive Name </label>
                            <select id="userId" name="userId" class="form-select form-select mt-2 expense_type">
                                <option value="">All Executive Name</option>
                                @foreach($userNameList as $name)
                                <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <br>

                        <input type="hidden" name="reimbursementType" value="{{ $type}}" />

                        @if($type == "birthday")
                            <input type="hidden" name="expense_type" value="e7897fb6-9563-11ec-879c-000c293f1073" />
                        @elseif ($type == "incentive")
                        <input type="hidden" name="expense_type" value="0608b614-9564-11ec-879c-000c293f1073" />
                        @else
                        <div class="form-group col-md-4 ">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.expenseType') }}</label>
                            <select id="expense_type" name="expense_type" class="form-select form-select mt-2 expense_type">
                                <option value="0">{{ __('labels.all_expense_type') }}</option>
                                @if(count($getExpense) > 0)

                                @foreach($getExpense as $name)
                                <option value="{{ $name->id }}">{{ $name->eName }}</option>
                                @endforeach

                                @endif
                            </select>
                        </div>
                        @endif



                        <div class="form-group col-md-4  {{$type == 'other' ? 'mt-4' : ''}}">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.status') }}</label>
                            <select id="reimbursement_status" name="reimbursement_status" class="form-select form-select mt-2 reimbursement_status">
                                <option value="0">{{ __('labels.all_status') }}</option>
                                <option value="{{__('labels.pending')}}">{{ __('labels.pending') }}</option>
                                <option value="{{__('labels.approved')}}">{{ __('labels.approved') }}</option>
                                <option value="{{__('labels.rejected')}}">{{ __('labels.rejected') }}</option>
                            </select>
                        </div>

                        @if($type == "birthday")

                        <div class="form-group col-md-4 mt-4">
                            <label for="formType"><span class="fa fa-filter mb-2"></span> {{ __('labels.form_type') }}</label>
                            <select id="formType" name="formType" class="form-select form-select mt-2 formType select2Class">
                                <option value="0">{{ __('labels.all_form') }}</option>
                                <option value="{{__('labels.dealer_filter')}}">{{ __('labels.dealer_filter') }}</option>
                                <option value="{{__('labels.engineer_filter')}}">{{ __('labels.engineer_filter') }}</option>
                                <option value="{{__('labels.architect_filter')}}">{{ __('labels.architect_filter') }}</option>
                                <option value="{{__('labels.mason_filter')}}">{{ __('labels.mason_filter') }}</option>
                                <option value="{{__('labels.contractor_filter')}}">{{ __('labels.contractor_filter') }}</option>
                                <option value="{{__('labels.construction_filter')}}">{{ __('labels.construction_filter') }}</option>
                            </select>
                        </div>


                        <div class="form-group col-md-4 mt-4">
                            <label for="name"><span class="fa fa-filter mb-2"></span> Actor Name</label>
                            <select id="dealerName" name="dealerName"
                                class="form-select form-select mt-2 dealerType select2Class">
                                <option value="0" selected>All Actor Name</option>
                                @if(count($dealerNameList) > 0)
                                @foreach($dealerNameList as $name)
                                <option value="{{ $name->id }}">{{ $name->name }}</option>
                                @endforeach

                                @endif
                            </select>
                        </div>


                        @endif

                            <div class="form-group col-md-4 mt-4">
                                <label for="daterange"><span class="fa fa-filter mb-2"></span> Date Range <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mt-2" id="daterange" name="daterange"
                                    placeholder="Select Date Range" readonly="readonly">
                            </div>

                        </div>

                        <div class="form-group mt-4">
                            <label for="export_type" class="col-2 col-form-label">Export Type<span
                                    class="text-danger">*</span></label>
                            <div class="row ">

                                <div class="custom-control col-2 custom-radio">
                                    <input type="radio" id="export_type1" name="export_type" value="Pdf"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="export_type1">Pdf </label>
                                </div>

                                <div class="custom-control col-2 custom-radio">
                                    <input type="radio" id="export_type2"  name="export_type" value="Excel"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="export_type2">Excel</label>
                                </div>


                                <div class="custom-control col-2 custom-radio">
                                    <input type="radio" id="export_type3" checked name="export_type" value="ViewReport"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="export_type3">View Report</label>
                                </div>

                            </div>

                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-10">
                                <button type="submit" class="btn btn-success"> Export</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: General Report -->
        </div>
    </div>
</div>
<!-- END: Content -->

@endsection

@section('js')
<script type="text/javascript" src="{{ url('admin-assets/js/moments.min.js') }}"></script>
<script type="text/javascript" src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ url('admin-assets/js/daterangepicker.min.js') }}"></script>
<script>
    var date = new Date();
    date.setMonth(date.getMonth() - 12);

    $('#daterange').daterangepicker({
        'minDate': date,
        'maxDate': new Date(),
        'drops': "down",
        'dateLimit': {
            days: 91
        },
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $("#reportReimbursements").validate({
        rules: {
            daterange: {
                required: true,
            },
            // type : {
            //     required:true,
            // }
        },
        messages: {

            daterange: {
                required: "Please select date",
            },
            // type : {
            //     required:"Please select type",
            // }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $("select").select2({
});
});
</script>

@endsection
