@extends('admin.layout.index')
@section('title') Attendance  Report @endsection
@section('css')
    <link href="{{ url('admin-assets/css/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')


    <!-- BEGIN: Content -->
    <div class="content">
        <!-- BEGIN: Top Bar -->
    @section('navigation')
        <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
            <a href="{{ route('AdminDashboard') }}"><span class="">Dashboard</span></a>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <a href=""><span class="">Reports</span></a>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <a href="{{ route('admin.attendanceReport') }}">
                <span class="breadcrumb--active"> Attendance Report</span>
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
                    <h2 class="text-lg font-medium truncate me-4 mb-0">Attendance Report</h2>
                </div>
                <br>
                @include('admin.common.flash')
                <div class="container">


                    <form class="form" target="_blank" name="reportKnowledge" id="reportKnowledge" method="post"
                        enctype="multipart/form-data" action="{{ route('admin.attendanceReport') }}">

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
                            <select id="userId" name="userId" class="form-select form-select mt-2 userId">
                                <option value="">All Executive Name</option>
                                @foreach($userNameList as $name)
                                <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                                @endforeach
                            </select>
                        </div>

                        @endif
                        <br>


                            <div class="form-group col-md-4 ">
                                <label for="daterange"><span class="fa fa-filter mb-2"></span> Date Range <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mt-2" id="daterange" name="daterange"
                                    placeholder="Select Date Range" readonly="readonly">
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
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
                        <br>
                        <div class="form-group row">
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

    $("#reportKnowledge").validate({
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

