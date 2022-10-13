@extends('admin.layout.index')
@section('title') {{__('labels.marketing_van_request_title')}} @endsection
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
            <a href="{{ route('admin.materialReport') }}">
                <span class="breadcrumb--active"> {{__('labels.marketing_van_request_title')}}</span>
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
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{__('labels.marketing_van_request_title')}}</h2>
                </div>
                <br>
                @include('admin.common.flash')
                <div class="container">


                    <form class="form" target="_blank" name="reportMarketingVanReport" id="reportMarketingVanReport" method="post"
                        enctype="multipart/form-data" action="{{ route('admin.marketingVanReport') }}">

                        {{ csrf_field() }}
                        <div class="row">

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

                        @if(count($dealerNameList) > 0)
                        <div class="form-group col-md-4 ">
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


                        <div class="form-group col-md-4 ">
                            <label for="name"><span class="fa fa-filter mb-2"></span> Actor Name</label>
                            <select id="dealerName" name="dealerName"
                                class="form-select form-select mt-2 dealerType select2Class">
                                <option value="0" selected>All Actor Name</option>

                                @foreach($dealerNameList as $name)
                                <option value="{{ $name->id }}">{{ $name->name }}</option>
                                @endforeach


                            </select>
                        </div>

                        @endif


                            <div class="form-group col-md-4">
                                <label for="daterange"><span class="fa fa-filter mb-2"></span> Vehicle </label>
                                <select id="vehicleNumber1" name="vehicleNumber1"
                                            class="form-select form-select mt-2 select2Class">
                                            <option value="">Please select vehicle number</option>
                                            @php
                                            $vehicleArray = config('constant.vehicleArray');
                                            @endphp
                                                @foreach ($vehicleArray as $key => $vehicle)
                                                <option value="{{ $vehicle }}"> {{ $vehicle }}</option>
                                                @endforeach
                                        </select>
                            </div>


                            <div class="form-group col-md-4">
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
            days: 365
        },
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $("#reportMarketingVanReport").validate({
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
