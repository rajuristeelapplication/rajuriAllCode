@extends('admin.layout.index')
@section('title') {{ empty($scheduleDetail) ? 'Create' : 'Edit' }} {{__('labels.schedule_flag')}} @endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"
    integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

<style type="text/css">
    .previewImage {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }

    .close-btn {
        background: transparent;
        padding: 0;
    }

    .close-btn:focus {
        border: none;
        outline: none;
    }

    .pointer-class {
        cursor: pointer;
    }

    .hideClass{
        display: none;
    }
</style>

@endsection
@section('content')
<div class="content">
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="___class_+?2___">{{ __('labels.dashboard_navigation')
                }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('schedules.index') }}"><span class="breadcrumb">{{ __('labels.manage_schedules') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($scheduleDetail) ? 'Create' : 'Edit' }} {{ __('labels.schedule_flag')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($scheduleDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.schedule_flag') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($scheduleDetail))
                            <form class="createSchedule" name="scheduleForm" id="scheduleForm" method="post"
                                enctype="multipart/form-data" action="{{route('schedules.store')}}">
                                @else
                                <form class="createSchedule" name="scheduleForm" id="scheduleForm" method="post"
                                    enctype="multipart/form-data" action="{{route('schedules.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($scheduleDetail) ? $scheduleDetail->id : '' }}" />

                                    <input type="hidden" name="isAdmin" id="isAdmin" value="1" />

                                    <div class="">
                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.date')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="{{__('labels.sdate_db')}}"
                                                    id="{{__('labels.sdate_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2 datepickerdate"
                                                    value="{{  $scheduleDetail->sDate  ?? '' }}" minlength="4"
                                                    maxlength="40" placeholder="Please Select {{__('labels.date')}}"
                                                    aria-required="true" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.time')}}
                                                    <span class="text-danger">*</span> </label>
                                                <input type="text" name="{{__('labels.time_db')}}"
                                                    id="{{__('labels.time_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2 timePic"
                                                    value="{{ !empty($scheduleDetail->
                                                        sTime) ? Carbon\Carbon::parse($scheduleDetail->sTime)->format(config('constant.admin_dealer_time_format_create')) : '' }}"
                                                    minlength="4" maxlength="40"
                                                    placeholder="Please Select {{__('labels.time')}}"
                                                    aria-required="true" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">
                                                    {{__('labels.schedule_visit')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <select id="{{__('labels.s_type_db')}}"
                                                    name="{{__('labels.s_type_db')}}"
                                                    class="form-select form-select mt-2  w-100 mt-2 mb-2">
                                                    <option value="0" selected disabled>{{
                                                        __('labels.select_schedule_visit') }}</option>
                                                    <option value="{{__('labels.dealer_visit')}}" {{
                                                        !empty($scheduleDetail->
                                                        sType) && ($scheduleDetail->sType == __('labels.dealer_visit'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.dealer_visit') }}</option>
                                                    <option value="{{__('labels.site_visit')}}" {{
                                                        !empty($scheduleDetail->
                                                        sType) && ($scheduleDetail->sType == __('labels.site_visit'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.site_visit') }}</option>
                                                    <option value="{{__('labels.influencer_visit')}}" {{
                                                        !empty($scheduleDetail->
                                                        sType) && ($scheduleDetail->sType ==
                                                        __('labels.influencer_visit'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.influencer_visit') }}</option>
                                                </select>
                                                <span id="sType-error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">Actor Type<span class="text-danger">*</span> </label>
                                                <select id="userType" name="userType"
                                                    class="form-select form-select w-100 mt-2 mb-2 userType">
                                                    <option value="0" selected disabled>{{ __('labels.select_user_type')
                                                        }}</option>
                                                    <option value="{{__('labels.dealer_filter')}}" {{
                                                        !empty($scheduleDetail->
                                                        fType) && ($scheduleDetail->fType ==
                                                        __('labels.dealer_filter'))
                                                        ? ' selected' : ''}}>{{ __('labels.dealer_filter') }}</option>
                                                    <option value="{{__('labels.engineer_filter')}}" {{
                                                        !empty($scheduleDetail->
                                                        fType) && ($scheduleDetail->fType ==
                                                        __('labels.engineer_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.engineer_filter') }}</option>
                                                    <option value="{{__('labels.architect_filter')}}" {{
                                                        !empty($scheduleDetail->
                                                        fType) && ($scheduleDetail->fType ==
                                                        __('labels.architect_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.architect_filter') }}</option>
                                                    <option value="{{__('labels.mason_filter')}}" {{
                                                        !empty($scheduleDetail->
                                                        fType) && ($scheduleDetail->fType == __('labels.mason_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.mason_filter') }}</option>
                                                    <option value="{{__('labels.contractor_filter')}}" {{
                                                        !empty($scheduleDetail->
                                                        fType) && ($scheduleDetail->fType ==
                                                        __('labels.contractor_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.contractor_filter') }}</option>
                                                    <option value="{{__('labels.construction_filter')}}" {{
                                                        !empty($scheduleDetail->
                                                        fType) && ($scheduleDetail->fType ==
                                                        __('labels.construction_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.construction_filter') }}</option>
                                                </select>
                                                <span id="userType-error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{
                                                    __('labels.created_by') }}<span class="text-danger">*</span>
                                                </label>
                                                <select id="{{__('labels.user_id_db')}}"
                                                    name="{{__('labels.user_id_db')}}"
                                                    class="form-select form-select w-100 mt-2 mb-2 {{__('labels.user_id_db')}} ">
                                                    <option value="0" selected disabled>{{
                                                        __('labels.created_by_select') }}</option>
                                                    @foreach ($userDetail as $key => $userInfo)
                                                    <option value="{{ $userInfo->id }}" {{ !empty($scheduleDetail->
                                                        userId) && ($scheduleDetail->userId == $userInfo->id)
                                                        ? ' selected' : ''}}> {{ $userInfo->fullName }}</option>
                                                    @endforeach
                                                </select>
                                                <span id="userId-error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{
                                                    __('labels.dealer_name') }}<span
                                                        class="text-danger">*</span></label>
                                                <select id="{{__('labels.r_dealer_id')}}"
                                                    name="{{__('labels.r_dealer_id')}}"
                                                    class="form-control form-select mt-2 mb-2 search_place"
                                                    aria-invalid="false">
                                                </select>
                                                <span id="rjDealerId-error"></span>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">
                                                    {{__('labels.id')}}</label>
                                                <input type="text" name="{{__('labels.id')}}" id="{{__('labels.id')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $scheduleDetail->dealerId  ?? '' }}" minlength="4"
                                                    maxlength="40" aria-required="true" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row ">
                                                    {{__('labels.firm_name')}}
                                                    <span class="text-danger">*</span> </label>
                                                <input type="text" name="sFirmName"
                                                    id="sFirmName"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $scheduleDetail->sFirmName  ?? '' }}" minlength="2"
                                                    maxlength="200" placeholder="Enter {{__('labels.firm_name')}}"
                                                    aria-required="true" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.purpose')}}
                                                    <span class="text-danger">*</span> </label>
                                                {{-- <input type="text" name="{{__('labels.purpose_db')}}"
                                                    id="{{__('labels.purpose_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $scheduleDetail->purpose  ?? '' }}" minlength="4"
                                                    maxlength="40" placeholder="Enter {{__('labels.purpose')}}"
                                                    aria-required="true"> --}}

                                                    @php
                                                        // $purposeArray = [100,200,300];
                                                        $purposeArray = config('constant.purposeArray');
                                                    @endphp

                                                    <select id="purpose" name="purpose" class="form-select form-select w-100 mt-2 mb-2 purpose">
                                                    <option value="" selected disabled>Select Purpose</option>
                                                    @foreach ($purposeArray as $key => $purpose)
                                                    <option value="{{ $purpose }}" {{ !empty($scheduleDetail) && ($scheduleDetail->purpose == $purpose)
                                                        ? ' selected' : ''}}> {{ $purpose }}</option>

                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>


                                        @php
                                            $hideClass = 'hideClass';

                                            if(!empty($scheduleDetail) && ($scheduleDetail->purpose == "Other"))
                                            {
                                                $hideClass =  '';
                                            }
                                        @endphp


                                        <div class="col-md-6 mt-2 purposeText {{ $hideClass  }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">Other Purpose Text
                                                    <span class="text-danger">*</span> </label>
                                                 <input type="text" name="purposeText"
                                                    id="purposeText"
                                                    class="form-control input-valid w-100 mt-2 mb-2 purposeText"
                                                    value="{{  $scheduleDetail->purposeText  ?? '' }}" minlength="4"
                                                    maxlength="255" placeholder="Enter Other Purpose Text"
                                                    aria-required="true">
                                            </div>
                                        </div>


                                        <div class="col-md-6 slocation">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">
                                                    {{__('labels.location')}}</label>
                                                <input type="text" name="{{__('labels.s_location_db')}}"
                                                    id="{{__('labels.s_location_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $scheduleDetail->slocation  ?? '' }}" minlength="4"
                                                    maxlength="255" placeholder="Enter {{__('labels.location')}}"
                                                    aria-required="true">
                                            </div>
                                        </div>

                                        <div class="form-group" id="latitudeArea">
                                            <input type="hidden" id="latitude" name="slatitude" class="form-control">
                                        </div>
                                        <div class="form-group" id="longtitudeArea">
                                            <input type="hidden" name="slongitude" id="longitude" class="form-control">
                                        </div>

                                        <div class="text-left">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('schedules.index') }}"
                                                class="btn btn-danger text-white mt-5">
                                                {{__('labels.cancel')}}</a>
                                        </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"
    integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(".createSchedule").validate({
        rules: {
            "sType": {
                required: true,
            },
            {{__('labels.sdate_db')}}: {
                required: true,
            },
            {{__('labels.time_db')}}: {
                required: true,
            },
            'userType': {
                required: true,
            },
            'rjDealerId': {
                required: true,
            },
            'purposeText': {
                required: true,
            },
            {{__('labels.s_firm_name_db')}}: {
                required: true,
            },
            {{__('labels.purpose_db')}}: {
                required: true,
            },
            'userId': {
                required: true,
            },

        },
        errorPlacement: function (error, element) {
          console.log(error.text());

             if (error.text() == "Please select user type") {
                 error.appendTo("#userType-error");
             }else if (error.text() == "Please select dealer name") {
                error.appendTo("#rjDealerId-error");
             }else if(error.text() == "Please select user"){
                error.appendTo("#userId-error");
             }else if (error.text() == "Please select schedule visit") {
                error.appendTo("#sType-error");
             }
             else{
              error.insertAfter($(element));
             }
          },
        messages: {
            "sType": {
                required: 'Please select schedule visit',
            },
            {{__('labels.sdate_db')}}: {
                required: '{{__('labels.date_db_valid')}}',
            },
            {{__('labels.time_db')}}: {
                required: '{{__('labels.time_db_valid')}}',
            },
            'userType': {
                required: 'Please select user type',
            },
            'rjDealerId': {
                required: 'Please select dealer name',
            },
            'purposeText':{
                required: 'Please enter purpose text',
            },
            {{__('labels.s_firm_name_db')}}: {
                required: '{{__('labels.s_firm_name_db_valid')}}',
            },
            {{__('labels.purpose_db')}}: {
                required: '{{__('labels.purpose_valid')}}',
            },
            'userId': {
                required: 'Please select user',
            },




        },
        submitHandler: function(form) {

            save(form);
        }
    });
</script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> --}}
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBWF9wFs2DV5_Ywpme7CSJg4F1wWOg0yK4"></script> --}}

<script>
    $( function() {
    $( ".datepickerdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );

    $( function() {
    $('.timePic').timepicker();
    });
</script>

{{-- Get Dealers Id --}}
<script>
    $(document).ready(function() {

        var type = $('#id').val() ? 'edit' : 'add';

        getEditDropDownValue('edit');

        $("#rjDealerId").attr("disabled", true);

            $(document).on('change', '.userType,.userId', function() {

            var userType = $('#userType').val();
             var userId = $('#userId').val();

             if(userType && userId)
             {
                getEditDropDownValue(type);
             }

        });
    });

    function getEditDropDownValue(type) {

    var userType = $('#userType').val();
    var userId = $('#userId').val();
    var schdualRjId = '{{ $scheduleDetail->rjDealerId  ?? ''}}';
    var ajaxUrl = '{{ route('getDealerByType') }}';
    var selected = '';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                userType: userType,
                userId: userId,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    $('#rjDealerId').html('<option value="">Select Dealer Name</option>');
                    $("#rjDealerId").attr("disabled", false);
                    $.each(result.dName,function(key,value){

                        if(type == "edit")
                        {
                            selected =  (value.id == schdualRjId) ? 'selected' : '';
                            $("#rjDealerId").append('<option value="'+value.id+'"  '+selected+'>'+value.name+'</option>');
                        }else{
                            $("#rjDealerId").append('<option value="'+value.id+'" >'+value.name+'</option>');
                        }


                    });
                }
            });
    }
</script>
<script>
    //Get Dealer Id
    $(document).ready(function() {


        $('#purpose').on('change', function() {

            var val = $(this).val();

            if(val == "Other")
            {
                $('.purposeText').removeClass('hideClass');
            }else{
                $('.purposeText').addClass('hideClass');
            }

        });


        setTimeout(function(){
            getEditDealerIdValue();
        },2000);

        $('#rjDealerId').on('change', function() {
        var dealerIdVal = this.value;

        $("#ID").html('');
            var ajaxUrl = '{{ route('getDealerDetail') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                dealerIdVal: dealerIdVal,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    console.log(result);
                    document.getElementById("ID").value = result.dealerId ??"";;
                    document.getElementById("sFirmName").value = result.firmName ??"";
                    //
                }
            });
        });

    });

    function getEditDealerIdValue() {
        var dealerIdVal = $('#rjDealerId').val();
        var ajaxUrl = '{{ route('getDealerDetail') }}';
        $.ajax({
            url: ajaxUrl,
            type: "POST",
            data: {
            dealerIdVal: dealerIdVal,
            _token: '{{csrf_token()}}'
            },
            dataType : 'json',
            success: function(result){

                document.getElementById("ID").value = result.dealerId??"";
                document.getElementById("sFirmName").value = result.firmName??"";
            }
        });
    }

    //Hide/Show Location
    $(".slocation").hide();
    $(document).ready(function() {
        $('#sType').on('change', function() {
        var schedualeType = this.value;

        if (schedualeType == "Site Visit") {
            $(".slocation").show();
        }else{
            $(".slocation").hide();
        }

        });
    });
</script>



{{-- Address --}}
<script>
    $(document).ready(function () {
            $("#latitudeArea").addClass("d-none");
            $("#longtitudeArea").addClass("d-none");
        });
</script>
<script>
    // google.maps.event.addDomListener(window, 'load', initialize);
    //     function initialize() {
    //         var input = document.getElementById('slocation');
    //         var autocomplete = new google.maps.places.Autocomplete(input);
    //         autocomplete.addListener('place_changed', function () {
    //             var place = autocomplete.getPlace();
    //             $('#latitude').val(place.geometry['location'].lat());
    //             $('#longitude').val(place.geometry['location'].lng());
    //             $("#latitudeArea").removeClass("d-none");
    //             $("#longtitudeArea").removeClass("d-none");
    //         });
    //     }
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $("select").select2({
});
});
</script>

@endsection
