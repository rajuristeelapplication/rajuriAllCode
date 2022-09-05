@extends('admin.layout.index')

@section('title') {{ empty($userDetail) ? 'Create' : 'Edit' }}

@endsection
@section('css')
    <link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style>
        .text-right{
            text-align:right;
        }
    </style>

@endsection
@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
    @section('navigation')

    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="___class_+?2___">{{ __('labels.dashboard_navigation')
                }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>

        <a href="{{ route('users.index').'/all' }}"><span class="breadcrumb">{{__('labels.users')}}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($userDetail) ? 'Create' : 'Edit' }} {{__('labels.users')}} </span>
    </div>
    @endsection
    @include('admin.common.notification')

    <div class="intro-y d-flex align-items-center mt-8">
        <h2 class="text-lg font-medium me-auto">{{ empty($userDetail) ? 'Create' : 'Edit' }}
            {{__('labels.users')}}
        </h2>
    </div>
    <div class="mt-5">
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

                <div class="p-5">
                    <div class="preview">
                        @include('admin.common.flash')
                        <form class="form" id="Profileform" method="post" enctype="multipart/form-data"
                            action="{{ route('users.insertUpdateSalesMarketingUser') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" id="id" value="{{ !empty($userDetail)? $userDetail->id : '' }}" />
                            {{-- <input type="hidden" name="parameter" id="parameter" value="{{ $parameter }}" /> --}}

                            @if(Auth::user()->roleId == config('constant.ma_id'))
                            <div class="input-form">
                                <label for="userType">{{ __('labels.users_type') }} <span class="text-danger">*</span></label>
                                <select id="roleId" name="roleId" class="form-select form-select mt-2 roleId">
                                    <option value="{{config('constant.marketing_executive_id')}}" {{ !empty($userDetail->
                                        roleId) && ($userDetail->roleId == config('constant.marketing_executive_id'))
                                        ? ' selected' : ''}}>{{ __('labels.marketing_executive') }}
                                    </option>
                                </select>
                            </div>
                            @else
                            <div class="input-form">
                                <label for="userType">{{ __('labels.users_type') }} <span class="text-danger">*</span></label>
                                <select id="roleId" name="roleId" class="form-select form-select mt-2 roleId">
                                    <option value="">Select User Type</option>
                                    <option value="{{config('constant.sales_executive_id')}}" {{ !empty($userDetail->
                                        roleId) && ($userDetail->roleId == config('constant.sales_executive_id'))
                                        ? ' selected' : ''}}>{{ __('labels.sales_executive') }}</option>
                                    <option value="{{config('constant.marketing_executive_id')}}" {{ !empty($userDetail->
                                        roleId) && ($userDetail->roleId == config('constant.marketing_executive_id'))
                                        ? ' selected' : ''}}>{{ __('labels.marketing_executive') }}
                                    </option>
                                </select>
                            </div>
                            @endif

                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.first_name')}} <span class="text-danger">*</span><span
                                        class="ms-sm-auto mt-1 sm:mt-0 text-xs text-gray-600"></span> </label>
                                <input type="text" name="firstName" id="firstName" class="form-control input w-100 mt-2"
                                    value="{{ $userDetail->firstName  ?? '' }}" placeholder="{{__('labels.first_name')}}"
                                    minlength="2" maxlength="30" required>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.last_name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="lastName" id="lastName" class="form-control input w-100 mt-2"
                                    value="{{ $userDetail->lastName ?? '' }}" placeholder="{{__('labels.last_name')}}"
                                    minlength="2" maxlength="30"  required>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.email')}} <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control input w-100 mt-2"
                                    value="{{ $userDetail->email ?? '' }}" placeholder="{{__('labels.email')}}" >
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>

                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.mobile_no')}} <span class="text-danger">*</span></label>
                                <input type="text" name="mobileNumber" id="mobileNumber"
                                    class="form-control input w-100 mt-2 mb-2 numeric"
                                    minlength="6" maxlength="10"
                                    value="{{  $userDetail->mobileNumber  ?? '' }}"
                                    placeholder="{{__('labels.mobile_no')}}" aria-required="true">
                            </div>

                            <div class="input-form mt-3">
                                <div class="input-form">
                                    <label class="d-flex flex-column flex-sm-row"> {{__('labels.dob')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="dob" id="dob"
                                        class="form-control input-valid w-100 mt-2 mb-2 datepickerdate"
                                        value="{{  $userDetail->dob  ?? '' }}" minlength="4"
                                        maxlength="40" placeholder="{{__('labels.dob')}}"
                                        aria-required="true" readonly>
                                </div>
                            </div>

                            <div class="input-form mt-3">
                                <div class="input-form">
                                    <label class="d-flex flex-column flex-sm-row"> {{__('labels.address')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="address" id="address"
                                        class="form-control input-valid w-100 mt-2 mb-2"
                                        value="{{  $userDetail->address  ?? '' }}" minlength="4"
                                        maxlength="150" placeholder="{{__('labels.address')}}"
                                        aria-required="true" >
                                </div>
                            </div>

                            <div class="input-form mt-3">
                                <div class="input-form">
                                    <label class="d-flex flex-column flex-sm-row"> {{__('labels.zip_code')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="zipCode" id="zipCode"
                                        class="form-control input-valid w-100 mt-2 mb-2"
                                        value="{{  $userDetail->zipCode  ?? '' }}" minlength="4"
                                        maxlength="6" placeholder="{{__('labels.zip_code')}}"
                                        aria-required="true" >
                                </div>
                            </div>

                            <div class="input-form mt-3">
                                <div class="input-form">
                                    <label class="d-flex flex-column flex-sm-row">{{__('labels.city')}}<span
                                            class="text-danger">*</span></label>
                                    <select id="cityId" name="cityId"
                                        class="form-control form-select mt-2 mb-2" aria-invalid="false">
                                        <option value="" selected>Select {{__('labels.city')}}</option>
                                        @foreach ($city as $key => $cityInfo)
                                        <option value="{{ $cityInfo->id }}" {{ !empty($userDetail->cityId) && ($userDetail->cityId == $cityInfo->id)
                                            ? ' selected' : ''}}> {{ $cityInfo->cName }}</option>
                                        @endforeach
                                    </select>
                                    <label id="cityId-error" class="error" style="display: none;" for="cityId">Please select city</label>
                                </div>
                            </div>

                            <div class="input-form row mt-6">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.profile_image')}} </label>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 mb-2">
                                    <input class="form-control dropify" type="file" value="" id="profileImage"
                                         name="profileImage" tabindex="5"
                                        placeholder="Please upload photo" data-show-remove="false"
                                        accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                        data-allowed-file-extensions="jpg png jpeg"
                                        data-default-file="{{ $userDetail->profileImage ?? '' }}">
                                    <label id="profileImage-error" class="error" for="profileImage"
                                        style="display: none;">Please select profile image</label>
                                </div>
                            </div>

                            <div class="input-form row mt-6">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.photo_id_proof')}} </label>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 mb-2">
                                    <input class="form-control dropify" type="file" value="" id="photoIdProof"
                                         name="photoIdProof" tabindex="5"
                                        placeholder="Please upload photo" data-show-remove="false"
                                        accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                        data-allowed-file-extensions="jpg png jpeg"
                                        data-default-file="{{ $userDetail->photoIdProof ?? '' }}">
                                    <label id="photoIdProof-error" class="error" for="photoIdProof"
                                        style="display: none;">Please select Photo Id Proof</label>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary text-white mt-5">Submit</button>
                                 <a href="{{ route('users.index') }}" class="btn btn-danger text-white mt-5">Back</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- END: Form Validation -->
        </div>
    </div>
</div>
<!-- END: Content -->
@endsection
@section('js')
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script type="text/javascript">
    $(document).ready(function() {

        $(function() {
            $( ".datepickerdate" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                maxDate: "2004-01-01",
            });
        });

        $(function() {
            $("#cityId").select2({});
        });

        var emailpattern =
            /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

        $.validator.addMethod('minStrict', function(value, el, param) {
            return value > param;
        });

        $.validator.addMethod('maxStrict', function(value, el, param) {
            return value < param;
        });

        // image dropify
        $('.dropify').dropify({
            error: {
                'minWidth': 'The profile image width must be greater than 100px',
                'maxWidth': 'The profile image width must be less than 1000px',
                'minHeight': 'The profile image height must be greater than 100px',
                'maxHeight': 'The profile image height must be less than 1000px',
                'imageFormat': 'The profile image format is not allowed jpg png gif jpeg only.'
            }
        });

        $("#Profileform").validate({
            ignore: "",
            debug: true,
            rules: {
                firstName: {
                    required: true,
                    minlength: 3,
                    maxlength: 100,
                },
                lastName: {
                    required: true,
                    minlength: 3,
                    maxlength: 100,
                },
                dob: {
                    required: true,
                },
                address: {
                    required: true,
                },
                zipCode: {
                    required: true,
                },
                cityId: {
                    required: true,
                },
                roleId: {
                    required: true,
                },
                email: {
                    required: true,
                    remote: {
                        url:baseUrl+'/check/unique/users/email/id',
                        type: "post",
                        data: {
                            value: function() {
                                return $( "#email" ).val();
                            },
                            id: function() {
                                return $( "#id" ).val();
                            },
                            _token: '{{csrf_token()}}'
                        }
                    },
                },
                mobileNumber: {
                    required: true,
                    remote: {
                        url:baseUrl+'/check/unique/users/mobileNumber/id',
                        type: "post",
                        data: {
                            value: function() {
                                return $( "#mobileNumber" ).val();
                            },
                            id: function() {
                                return $( "#id" ).val();
                            },
                            _token: '{{csrf_token()}}'
                        }
                    },
                },
            },
            messages: {
                firstName: {
                    required: "{{__('labels.first_name_require_valid')}}",
                    maxlength: "{{__('labels.first_name_max_valid')}}",
                    minlength: "{{__('labels.first_name_min_valid')}}",
                },
                lastName: {
                    required: "{{__('labels.last_name_require_valid')}}",
                    maxlength: "{{__('labels.first_name_max_valid')}}",
                    minlength: "{{__('labels.first_name_min_valid')}}",
                },
                dob: {
                    required: "Please select date of birth",
                },
                address: {
                    required: "Please enter address",
                },
                zipCode: {
                    required: "Please enter zip code",
                },
                cityId: {
                    required: "Please select city",
                },
                roleId: {
                    required: "Please select user type",
                },
                email: {
                    required: "{{__('labels.enter_email_validation')}}",
                    regex: "{{__('labels.enter_valid_email_address_validation')}}",
                    maxlength: "{{__('labels.email_max_valid')}}",
                    remote: "{{__('labels.email_remote_valid')}}",

                },
                mobileNumber: {
                    required: "{{__('labels.moblie_number_require_valid')}}",
                    remote: "{{__('labels.moblie_number_remote_valid')}}",
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });



</script>
@endsection
