@extends('admin.layout.index')

@section('title') {{ empty($admin) ? 'Create' : 'Edit' }}

@if($parameter == 'hr')
{{__('labels.users_hr')}}
@elseif($parameter == 'ma')
{{__('labels.users_ma')}}
@endif @endsection
@section('css')
    <link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
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

        <a href="{{ route('users.index',['parameter' => $parameter]) }}"><span class="breadcrumb">

            @if($parameter == 'hr')
             {{__('labels.users_hr')}}
             @elseif($parameter == 'ma')
             {{__('labels.users_ma')}}
            @endif

        </span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($admin) ? 'Create' : 'Edit' }}

            @if($parameter == 'hr')
             {{__('labels.users_hr')}}
             @elseif($parameter == 'ma')
             {{__('labels.users_ma')}}
            @endif


        </span>
    </div>
    @endsection
    @include('admin.common.notification')

    <div class="intro-y d-flex align-items-center mt-8">
        <h2 class="text-lg font-medium me-auto">{{ empty($admin) ? 'Create' : 'Edit' }}
            @if($parameter == 'hr')
            {{__('labels.users_hr')}}
            @elseif($parameter == 'ma')
            {{__('labels.users_ma')}}
           @endif
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
                            action="{{ route('users.diff_insert_update') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" id="id" value="{{ !empty($admin)? $admin->id : '' }}" />

                            <input type="hidden" name="parameter" id="parameter" value="{{ $parameter }}" />

                            <div class="input-form">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.first_name')}} <span class="text-danger">*</span><span
                                        class="ms-sm-auto mt-1 sm:mt-0 text-xs text-gray-600"></span> </label>
                                <input type="text" name="firstName" id="firstName" class="form-control input w-100 mt-2"
                                    value="{{ $admin->firstName  ?? '' }}" placeholder="{{__('labels.first_name')}}"
                                    minlength="2" maxlength="30" required>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.last_name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="lastName" id="lastName" class="form-control input w-100 mt-2"
                                    value="{{ $admin->lastName ?? '' }}" placeholder="{{__('labels.last_name')}}"
                                    minlength="2" maxlength="30" required>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.email')}} <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control input w-100 mt-2"
                                    value="{{ $admin->email ?? '' }}" placeholder="{{__('labels.email')}}" >
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>

                            @if($parameter == 'ma')
                                <div class="input-form mt-3">
                                    <label class="d-flex flex-column flex-sm-row">Select Marketing Executive</label>

                                    <select id="maEmployessAssign" data-placeholder="Select Marketing Executive" name="maEmployessAssign[]"   class="form-select form-select w-100 mt-2 mb-2 " multiple>
                                        @forelse ($meEmployees as $meEmployee)
                                        <option value="{{ $meEmployee->id }}" {{ (!empty($admin) && in_array($meEmployee->id,$selectedMeEmployees)) ? 'selected' : ''  }}>{{ $meEmployee->fullName ?? '' }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <br>
                                    <input type="checkbox"  class="mt-2" id="checkbox" >    Select All
                                </div>
                            @endif


                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.mobile_no')}} <span class="text-danger">*</span></label>
                                <input type="text" name="mobileNumber" id="mobileNumber"
                                    class="form-control input w-100 mt-2 mb-2 numeric"
                                    minlength="6" maxlength="10"
                                    value="{{  $admin->mobileNumber  ?? '' }}"
                                    placeholder="{{__('labels.mobile_no')}}" aria-required="true">
                            </div>

                            @if (empty($admin))
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.password')}} <span class="text-danger">*</span></label>
                                <input type="text" name="password" id="password"
                                class="form-control input w-100 mt-2 mb-2"
                                minlength="6" maxlength="20"
                                value="{{  $admin->password  ?? '' }}"
                                placeholder="{{__('labels.password')}}" aria-required="true">
                            </div>
                            @endif

                            <div class="input-form row mt-6">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.profile_image')}} </label>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9">
                                    <input class="form-control dropify" type="file" value="" id="profileImage"
                                         name="profileImage" tabindex="5"
                                        placeholder="Please upload photo" data-show-remove="false"
                                        accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                        data-allowed-file-extensions="jpg png jpeg"
                                        data-default-file="{{ $admin->profileImage ?? '' }}">
                                    <label id="profileImage-error" class="error" for="profileImage"
                                        style="display: none;">Please select profile image</label>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary text-white mt-5">Submit</button>
                                 <a href="{{ route('users.index',['parameter' => $parameter]) }}" class="btn btn-danger text-white mt-5">Back</a>
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

<script type="text/javascript">
    $(document).ready(function() {


        $("#checkbox").click(function(){
            if($("#checkbox").is(':checked') ){ //select all
                $("#maEmployessAssign").find('option').prop("selected",true);
                $("#maEmployessAssign").trigger('change');
            } else { //deselect all
                $("#maEmployessAssign").find('option').prop("selected",false);
                $("#maEmployessAssign").trigger('change');
            }
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
                    maxlength: 30,
                },
                lastName: {
                    required: true,
                    minlength: 3,
                    maxlength: 30,
                },
                password: {
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
                password: {
                    required: "{{__('labels.password_required_valid')}}",
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $("select").select2({});
    });
</script>
@endsection
