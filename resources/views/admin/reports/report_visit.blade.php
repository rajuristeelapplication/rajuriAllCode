@extends('admin.layout.index')
@section('title') {{__('labels.visit_report_title')}} @endsection
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
            <a href="{{ route('admin.visitReport') }}">
                <span class="breadcrumb--active"> {{__('labels.visit_report_title')}}</span>
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
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{__('labels.visit_report_title')}}</h2>
                </div>
                <br>
                @include('admin.common.flash')
                <div class="container">


                    <form class="form" target="_blank" name="reportVisit" id="reportVisit" method="post"
                        enctype="multipart/form-data" action="{{ route('admin.visitReport') }}">

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

                        <div class="form-group col-md-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> Purpose </label>
                            @php
                            $purposeArray = config('constant.purposeArray');
                            @endphp
                            <select id="purpose" name="purpose" class="form-select form-select w-100 mt-2 mb-2 purpose">
                                <option value="" selected disabled>Select Purpose</option>

                                @if(count($purposeArray) > 0)

                                @foreach ($purposeArray as $key => $purpose)
                                <option value="{{ $purpose }}"> {{ $purpose }}</option>
                                @endforeach

                                @endif
                            </select>
                        </div>

                        @if(count($dealerNameList) > 0)

                        <div class="form-group col-md-4 mt-4">
                            <label for="fType"><span class="fa fa-filter mb-2"></span> {{ __('labels.form_type') }}</label>
                            <select id="fType" name="fType" class="form-select form-select mt-2 userType select2Class">
                                <option value="0">{{ __('labels.all_form') }}</option>
                                <option value="{{__('labels.dealer_filter')}}">{{ __('labels.dealer_filter') }}</option>
                                <option value="{{__('labels.engineer_filter')}}">{{ __('labels.engineer_filter') }}</option>
                                <option value="{{__('labels.architect_filter')}}">{{ __('labels.architect_filter') }}</option>
                                <option value="{{__('labels.mason_filter')}}">{{ __('labels.mason_filter') }}</option>
                                <option value="{{__('labels.contractor_filter')}}">{{ __('labels.contractor_filter') }}</option>
                                <option value="{{__('labels.construction_filter')}}">{{ __('labels.construction_filter') }}</option>
                            </select>
                        </div>


                        <br>
                        <div class="form-group col-md-4 mt-4">
                            <label for="dealerType"><span class="fa fa-filter mb-2"></span>Dealer Actor Type</label>
                            <select id="dealerType" name="dealerType" class="form-select form-select mt-2 dealerType select2Class">
                                <option value="0">All Dealer Actor Type</option>
                                <option value="{{__('labels.main_dealer')}}">{{ __('labels.main_dealer') }}</option>
                                <option value="{{__('labels.sub_dealer')}}">{{ __('labels.sub_dealer') }}</option>
                            </select>
                        </div>

                        <br>

                        <div class="form-group col-md-4 mt-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span> Actor Name </label>
                            <select id="dealerId" name="dealerId" class="form-select form-select mt-2 dealerId">
                                <option value="">All Actor Name</option>

                                @if(count($dealerNameList) > 0)

                                @foreach($dealerNameList as $name)
                                <option value="{{ $name->id }}">{{ $name->name }}</option>
                                @endforeach

                                @endif
                            </select>
                        </div>

                        @endif
                        <br>

                        <div class="form-group col-md-4  mt-4">
                            <label for="visitType"><span class="fa fa-filter mb-2"></span> {{ __('labels.visit_type') }}</label>
                            <select id="visitType" name="visitType" class="form-select form-select mt-2 visitType select2Class">
                                <option value="0">{{ __('labels.all_visit_type') }}</option>
                                <option value="{{__('labels.dealer_visit')}}">{{ __('labels.dealer_visit') }}</option>
                                <option value="{{__('labels.site_visit')}}">{{ __('labels.site_visit') }}</option>
                                <option value="{{__('labels.influencer_visit')}}">{{ __('labels.influencer_visit') }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 mt-4">
                            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.state') }} </label>
                            <select id="stateId" name="stateId"
                                class="form-select form-select mt-2 stateId select2Class">
                                <option value="" selected>{{ __('labels.state_select_valid') }}</option>

                                @if(count($allStates) > 0)
                                @foreach($allStates as $name)
                                     <option value="{{ $name->id }}">{{ $name->sName }}</option>
                                @endforeach
                                @endif

                                {{-- @if(count($stateNameLists) > 0)
                                @foreach($stateNameLists as $name)
                                <option value="{{ $name->dStateId }}">{{ $name->dSName }}</option>
                                @endforeach
                                @endif --}}
                            </select>
                        </div>

                        <div class="form-group col-md-4 mt-4">
                            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.district_name') }}  </label>
                            <select id="cityId" name="cityId"
                                class="form-select form-select mt-2 cityId select2Class">
                                <option value="" selected>{{ __('labels.select_district_name') }} </option>

                                {{-- @if(count($districtNameLists) > 0)

                                @foreach($districtNameLists as $name)
                                <option value="{{ $name->dCityId }}">{{ $name->dCName }}</option>
                                @endforeach

                                @endif --}}
                            </select>
                        </div>

                        <div class="form-group col-md-4 mt-4">
                            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.taluka_name') }}  </label>
                            <select id="talukaId" name="talukaId"
                                class="form-select form-select mt-2 talukaId select2Class">
                                <option value="" selected>{{ __('labels.select_taluka_name') }}</option>

                                {{-- @if(count($talukaNameLists) > 0)
                                @foreach($talukaNameLists as $name)
                                <option value="{{ $name->dTalukaId }}">{{ $name->dTName }}</option>
                                @endforeach
                                @endif --}}
                            </select>
                        </div>

                        <div class="form-group col-md-4 mt-4">
                            <label for="daterange"><span class="fa fa-filter mb-2"></span>Other Brand</label>
                            <select id="otherBrand" name="otherBrand" class="form-select form-select mt-2 expense_type">
                                <option value="0">All Other Brand</option>

                                @if(count($getExpense) > 0)

                                @foreach($getExpense as $name)
                                <option value="{{ $name->id }}">{{ $name->eName }}</option>
                                @endforeach

                                @endif
                            </select>
                        </div>



                            <div class="form-group col-md-4  mt-4">
                                <label for="daterange"><span class="fa fa-filter mb-2"></span> Date Range <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mt-2" id="daterange" name="daterange"
                                    placeholder="Select Date Range" readonly="readonly">
                            </div>


                    </div>

                        <div class="form-group mt-4">
                            <label for="export_type" class="col-2 col-form-label">Export Type<span
                                    class="text-danger">*</span></label>
                            <div class="row ">

                                {{-- <div class="custom-control col-2 custom-radio">
                                    <input type="radio" id="export_type1" name="export_type" value="Pdf"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="export_type1">Pdf </label>
                                </div> --}}

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

    $("#reportVisit").validate({
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
    $("select").select2({});




    $('#stateId').on('change', function() {
        var s_name = this.value;

            $("#cityId").html('');
            $("#talukaId").html('');

            var ajaxUrl = '{{ route('get-city') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                s_name: s_name,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    console.log(result);
                    $("#cityId").attr("disabled", false);
                    $('#cityId').html('<option value="">Select City</option>');
                    $('#talukaId').html('<option value="">Select Taluka</option>');
                    $.each(result.city,function(key,value){
                    $("#cityId").append('<option value="'+value.id+'">'+value.cName+'</option>');
                    });
                }
            });
        });


        $('#cityId').on('change', function() {

            var cityId = this.value;

            $("#talukaId").html('');
            var ajaxUrl = '{{ route('get-taluka') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                 cityId: cityId,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    $("#talukaId").attr("disabled", false);
                    $('#talukaId').html('<option value="">Select Taluka</option>');
                    $.each(result.taluka,function(key,value){
                    $("#talukaId").append('<option value="'+value.id+'">'+value.tName+'</option>');
                    });
                }
            });
        });


    // var ajaxUrl = '{{ route('admin.visitReport') }}';


    // $('#stateId').on('change', function() {
    //     var s_name = this.value;

    //         $("#cityId").html('');
    //         $("#talukaId").html('');


    //         $.ajax({
    //             url: ajaxUrl,
    //             type: "GET",
    //             data: {
    //             s_name: s_name,
    //             _token: '{{csrf_token()}}'
    //             },
    //             dataType : 'json',
    //             success: function(result){

    //                 $("#cityId").attr("disabled", false);
    //                 $('#cityId').html('<option value="">Select City</option>');
    //                 $('#talukaId').html('<option value="">Select Taluka</option>');
    //                 $.each(result.city,function(key,value){
    //                 $("#cityId").append('<option value="'+value.dCityId+'">'+value.dCName+'</option>');
    //                 });
    //             }
    //         });
    //     });


    //     $('#cityId').on('change', function() {

    //         var cityId = this.value;

    //         $("#talukaId").html('');

    //         $.ajax({
    //             url: ajaxUrl,
    //             type: "GET",
    //             data: {
    //              cityId: cityId,
    //             _token: '{{csrf_token()}}'
    //             },
    //             dataType : 'json',
    //             success: function(result){
    //                 $("#talukaId").attr("disabled", false);
    //                 $('#talukaId').html('<option value="">Select Taluka</option>');
    //                 $.each(result.taluka,function(key,value){
    //                 $("#talukaId").append('<option value="'+value.dTalukaId+'">'+value.dTName+'</option>');
    //                 });
    //             }
    //         });
    //     });

});
</script>

@endsection
