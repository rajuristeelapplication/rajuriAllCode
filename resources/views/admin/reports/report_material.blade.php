@extends('admin.layout.index')
@section('title') {{__('labels.material_report_title')}} @endsection
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
                <span class="breadcrumb--active"> {{__('labels.material_report_title')}}</span>
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
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{__('labels.material_report_title')}}</h2>
                </div>
                <br>
                @include('admin.common.flash')
                <div class="container">


                    <form class="form" target="_blank" name="reportMaterials" id="reportMaterials" method="post"
                        enctype="multipart/form-data" action="{{ route('admin.materialReport') }}">

                        {{ csrf_field() }}

                        {{-- <div class="form-group col-md-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.leave_type') }}</label>
                            <select id="leave_type" name="leave_type" class="form-select form-select mt-2 leave_type">
                                <option value="0">{{ __('labels.all_leave_type') }}</option>
                                <option value="{{__('labels.casual_leave')}}">{{ __('labels.casual_leave') }}</option>
                                <option value="{{__('labels.medical_leave')}}">{{ __('labels.medical_leave') }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 mt-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.status') }}</label>
                            <select id="leave_status" name="leave_status" class="form-select form-select mt-2 leave_status">
                                <option value="0">{{ __('labels.all_status') }}</option>
                                <option value="{{__('labels.pending')}}">{{ __('labels.pending') }}</option>
                                <option value="{{__('labels.approved')}}">{{ __('labels.approved') }}</option>
                                <option value="{{__('labels.rejected')}}">{{ __('labels.rejected') }}</option>
                            </select>
                        </div> --}}


                        <div class="form-row mt-4">
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

    $("#reportMaterials").validate({
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
@endsection
