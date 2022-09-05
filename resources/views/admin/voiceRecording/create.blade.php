@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
@endsection
@section('title') Agent {{ $userDetail ? 'Edit' : 'Create' }} @endsection
@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
        <!-- BEGIN: Top Bar -->
    @section('navigation')
        <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
            <a href="{{ route('AdminDashboard') }}"><span class="">Dashboard</span></a>
            <i class="
                    feather-chevron-right" class="breadcrumb__icon"></i>
                    <a href="{{ route('users.index',['userType' => $userType]) }}"><span class="">Users</span></a>
            <i class="
                            feather-chevron-right" class="breadcrumb__icon"></i>
                            <span class="breadcrumb--active"> Agent {{ empty($userDetail) ? 'Create' : 'Edit' }}
                            </span>
        </div>
    @endsection
    @include('admin.common.notification')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        Agent {{ $userDetail ? 'Edit' : 'Create' }} </h2>
                </div>
                <div class="mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: Form Validation -->
                        <div class="intro-y box">

                            <div class="p-5">
                                <div class="preview">
                                    @include('admin.common.flash')

                                    @if (empty($userDetail))
                                        <form class="form" name="glossaryForm" id="glossaryForm" method="post"
                                            enctype="multipart/form-data" action="{{ route('users.update', ['userType' => $userType]) }}">
                                        @else
                                            <form class="form" name="glossaryForm" id="glossaryForm"
                                                method="post" enctype="multipart/form-data"
                                                action="{{ route('users.update', ['userType' => $userType]) }}">
                                    @endif
                                    {{ csrf_field() }}


                                    <input type="hidden" name="id" id="id" value="{{ !empty($userDetail)? $userDetail->id : '' }}" />

                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> First Name <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="firstName" id="firstName"
                                            class="form-control input w-100 mt-2 mb-2"
                                            value="{{  $userDetail->firstName  ?? '' }}"  maxlength="100"
                                            placeholder="Please enter first name" aria-required="true">
                                    </div>

                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Last Name <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="lastName" id="lastName"
                                            class="form-control input w-100 mt-2 mb-2"
                                            value="{{  $userDetail->lastName  ?? '' }}" maxlength="100"
                                            placeholder="Please enter last name" aria-required="true">
                                    </div>

                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Email <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="email" id="email"
                                            class="form-control input w-100 mt-2 mb-2"
                                            value="{{  $userDetail->email  ?? '' }}" maxlength="100"
                                            placeholder="Please enter email" aria-required="true">
                                    </div>

                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Mobile Number <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="mobileNumber" id="mobileNumber"
                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                            minlength="6" maxlength="15"
                                            value="{{  $userDetail->mobileNumber  ?? '' }}"
                                            placeholder="Please enter mobile number" aria-required="true">
                                    </div>

                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Area <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="area" id="area"
                                            class="form-control input w-100 mt-2 mb-2"
                                            value="{{  $userDetail->area  ?? '' }}" maxlength="100"
                                            placeholder="Please enter area" aria-required="true">
                                    </div>
                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> State <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="state" id="state"
                                            class="form-control input w-100 mt-2 mb-2"
                                            value="{{  $userDetail->state  ?? '' }}" maxlength="100"
                                            placeholder="Please enter state" aria-required="true">
                                    </div>
                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> City <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="city" id="city"
                                            class="form-control input w-100 mt-2 mb-2"
                                            value="{{  $userDetail->city  ?? '' }}" maxlength="100"
                                            placeholder="Please enter city" aria-required="true">
                                    </div>
                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Pin Code <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="pinCode" id="pinCode"
                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                            value="{{  $userDetail->pinCode  ?? '' }}" maxlength="20"
                                            placeholder="Please enter pin code" aria-required="true">
                                    </div>

                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Bank Name <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="bankName" id="bankName"
                                            class="form-control input w-100 mt-2 mb-2"
                                            value="{{  $userDetail->bankName  ?? '' }}" maxlength="100"
                                            placeholder="Please enter bank name" aria-required="true">
                                    </div>

                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Bank Account Number <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="bankAccountNumber" id="bankAccountNumber"
                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                            value="{{  $userDetail->bankAccountNumber  ?? '' }}" maxlength="100"
                                            placeholder="Please enter bank account number" aria-required="true">
                                    </div>


                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row ml-1 mb-2"> Profile Image

                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9 ml-1">
                                                <input class="form-control dropify" type="file" value=""
                                                    id="profileImage"  name="profileImage"
                                                    tabindex="5" placeholder="Please upload photo" data-show-remove="false"
                                                    accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                                    data-allowed-file-extensions="jpg png jpeg gif"
                                                    data-default-file="{{ !empty($userDetail->profileImage) ? $userDetail->profileImage : ''  }}">
                                                <label id="profileImage-error" class="error" for="profileImage"
                                                    style="display: none;">Please upload profile image</label>

                                            </div>
                                        </div>


                                     <div class="form-group m-t-40 row">
                                        <label for="isActive" class="col-12 col-form-label mb-2">Status</label>
                                        <div class="custom-control custom-radio col-1">
                                            <input type="radio" id="status1" name="isActive" value="1"
                                                class="custom-control-input"
                                                {{ !empty($userDetail) && $userDetail->isActive == 1 ? 'checked' : '' }}
                                                {{ empty($userDetail) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="status1">Active</label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio col-2">
                                            <input type="radio" id="status2" name="isActive" value="0"
                                                class="custom-control-input"
                                                {{ !empty($userDetail) && $userDetail->isActive == 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit"
                                            class="submit btn btn-primary text-white mt-5">Submit</button>
                                        <a href="{{ route('users.index',['userType' => $userType]) }}"
                                            class="btn btn-danger text-white mt-5"> Back</a>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- END: Form Validation -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content -->
@endsection
@section('js')
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.dropify').dropify();

        //override required method
        $.validator.methods.required = function(value, element, param) {

            return (value == undefined) ? false : (value.trim() != '');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // This Function is used for validation
        $(".form").validate({
            ignore: "",
            rules: {
                firstName: {
                    required: true,
                    minlength:3,
                },
                lastName: {
                    required: true,
                    minlength:3,
                },
                showPassword:{
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
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
                        }
                    },
                },
                area: {
                    required: true,
                },
                state: {
                    required: true,
                },
                city: {
                    required: true,
                },
                pinCode: {
                    required: true,
                },
                bankName: {
                    required: true,
                },
                bankAccountNumber: {
                    required: true,
                },
            },

            messages: {
                firstName: {
                    required: 'Please enter first name',
                    minlength:"Please enter at least ${0} characters",
                },
                lastName: {
                    required: 'Please enter last name',
                    minlength:"Please enter at least ${0} characters",
                },
                email: {
                    required: 'Please enter email',
                    remote: 'This Email has already available please make different',
                },
                mobileNumber: {
                    required: 'Please enter mobile number',
                    remote: 'This Mobile number has already available please make different',
                },
                area:{
                    required: 'Please enter area',
                },
                state:{
                    required: 'Please enter state',
                },
                city:{
                    required: 'Please enter city',
                },
                pinCode:{
                    required: 'Please enter pin code',
                },
                bankName:{
                    required: 'Please enter bank name',
                },
                bankAccountNumber:{
                    required: 'Please enter bank account number',
                },
            },
            submitHandler: function(form) {

                form.submit();

            },
            invalidHandler: function(form, validator) {


            }
        });

        $(".submit").click(function() {
            $(".submit").removeClass("loading");
        });

        $(".submit").addClass("loading");


    });
</script>
@endsection
