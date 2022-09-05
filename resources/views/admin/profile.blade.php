@extends('admin.layout.index')

@section('title') {{__('labels.admin_profile_heading')}} @endsection
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
            <span class="breadcrumb">Dashboard</span>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <span class="breadcrumb--active">{{__('labels.admin_profile_heading')}}</span>
        </div>
    @endsection
    @include('admin.common.notification')

    <div class="intro-y d-flex align-items-center mt-8">
        <h2 class="text-lg font-medium me-auto">{{__('labels.admin_profile_heading')}}</h2>
    </div>
    <div class="mt-5">
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <!-- <div class="d-flex flex-column flex-sm-row align-items-center p-4 border-b">
                            <h2 class="font-medium text-base me-auto mb-0">
                                Implementation
                            </h2>
                        </div> -->
                <div class="p-5">
                    <div class="preview">
                        @include('admin.common.flash')
                        <form class="form" id="Profileform" method="post" enctype="multipart/form-data"
                            action="{{ route('UpdateAdminProfile') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" id="id" value="{{ !empty($admin)? $admin->id : '' }}" />

                            <div class="input-form">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.first_name')}} <span
                                        class="ms-sm-auto mt-1 sm:mt-0 text-xs text-gray-600"></span> </label>
                                <input type="text" name="firstName" id="firstName" class="form-control input w-100 mt-2"
                                    value="{{ $admin->firstName ? ucfirst($admin->firstName): '' }}" placeholder="{{__('labels.first_name')}}"
                                    minlength="2" required>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.last_name')}} </label>
                                <input type="text" name="lastName" id="lastName" class="form-control input w-100 mt-2"
                                    value="{{ $admin->lastName ?? '' }}" placeholder="{{__('labels.last_name')}}"
                                    minlength="2" required>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.email')}} </label>
                                <input type="email" name="email" id="email" class="form-control input w-100 mt-2"
                                    value="{{ $admin->email ?? '' }}" placeholder="{{__('labels.email')}}" readonly>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>

                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.mobile_no')}} <span class="text-danger">*</span></label>
                                <input type="text" name="mobileNumber" id="mobileNumber"
                                    class="form-control input w-100 mt-2 mb-2 numeric"
                                    minlength="10" maxlength="10"
                                    value="{{  $admin->mobileNumber  ?? '' }}"
                                    placeholder="{{__('labels.mobile_no')}}" aria-required="true">
                            </div>


                            <div class="input-form row mt-6">
                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.profile_image')}} </label>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9">
                                    <input class="form-control dropify" type="file" value="" id="profileImage"
                                         name="profileImage" tabindex="5"
                                        placeholder="Please upload photo" data-show-remove="false"
                                        accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                        data-allowed-file-extensions="jpg png jpeg"
                                        data-default-file="{{ $adminImage }}">
                                    <label id="profileImage-error" class="error" for="profileImage"
                                        style="display: none;">Please select profile image</label>


                                </div>

                                <div class="imageData col-xs-12 col-sm-12 col-md-2 col-lg-2"
                                    style="display: {{ $admin->profileImage ? 'block' : 'none' }}">

                                    <img src="{{ $adminImage }}" class=" radius" height="50px" />
                                    @php $deleteRoute = route('AdminProfileImageDelete',$admin->id); @endphp

                                    <button type="button" class="btn btnProfileDelete btn-danger  btn-circle "
                                        data-url="{{ $deleteRoute ?? '' }}"><i
                                            class="feather feather-trash-2 block mx-auto"></i></button>
                                </div>

                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary text-white mt-5">Submit</button>
                                 <a href="{{ route('AdminDashboard') }}" class="btn btn-danger text-white mt-5">Back</a>
                            </div>
                        </form>
                    </div>
                    <form id="profileDeleteForm" action="" method="POST" name="profileDeleteForm">
                        @csrf
                        @method('DELETE')

                    </form>
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
                email: {
                    required: true,
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
                email: {
                    required: "{{__('labels.enter_email_validation')}}",
                    regex: "{{__('labels.enter_valid_email_address_validation')}}",
                    maxlength: "{{__('labels.email_max_valid')}}",

                },
                mobileNumber: {
                    required: "{{__('labels.moblie_number_require_valid')}}",
                    remote: "{{__('labels.moblie_number_remote_valid')}}",
                },
            },
            submitHandler: function(form) {
                save(form);
            }
        });
    });

    //For profile image delete
    $(document).on("click", '.btnProfileDelete', function(event) {
        event.preventDefault();
        var title = $(this).data('title');
        var url = $(this).data('url');
        var form = '';
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Profile Image ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
        }).then(function(result) {

            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('AdminProfileImageDelete') }}",
                    data: '',
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function(result) {
                        $('.loader').hide();

                        if (result.status == 1) {
                            var imagenUrl = result.result.adminImage;
                            var drEvent = $('#profileImage').dropify({
                                defaultFile: imagenUrl
                            });
                            drEvent = drEvent.data('dropify');
                            drEvent.resetPreview();
                            drEvent.clearElement();
                            drEvent.settings.defaultFile = result.result.adminImage;
                            drEvent.destroy();
                            drEvent.init();

                            $('.image1').attr('src', result.result.adminImage);

                            $('.imageData').css('display', 'none');
                            $.toast({
                                heading: 'Success',
                                text: result.message,
                                showHideTransition: 'fade',
                                icon: 'success',
                                position: 'top-right',
                                loader: false,
                            });
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: result.message,
                                showHideTransition: 'fade',
                                icon: 'error',
                                position: 'top-right',
                                loader: false,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        $('.loader').hide();
                    }
                });
            }
        });
    });


    function save(form) {
        $('.loader').show();
        var data = new FormData($('form')[0]);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('UpdateAdminProfile') }}",
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(result) {
                $('.loader').hide();
                console.log(result);

                if (result.status == 1) {
                    var image = result.result.admin.profileImage;
                    if (image) {
                        $('.imageData').css('display', 'block');
                        $('.radius').attr('src', result.result.image);

                    } else {
                        $('.imageData').css('display', 'none');
                    }
                    $('.image1').attr('src', result.result.image);

                    $.toast({
                        heading: 'Success',
                        text: result.message,
                        showHideTransition: 'fade',
                        icon: 'success',
                        position: 'top-right',
                        loader: false,
                    });
                } else {
                    $.toast({
                        heading: 'Error',
                        text: result.message,
                        showHideTransition: 'fade',
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    });

                }


            },
            error: function(error) {
                console.log(error);
                $('.loader').hide();
            }
        });
    }
</script>
@endsection
