@extends('admin.layout.index')

@section('title') Change Password @endsection
@section('css')
    <link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
@endsection
@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
        <!-- BEGIN: Top Bar -->
    @section('navigation')
        <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
            <span class="breadcrumb">Dashboard</span>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <a href=""><span class="breadcrumb--active">Change Password</span></a>
        </div>
    @endsection
    @include('admin.common.notification')
    <!-- END: Top Bar -->
    <div class="intro-y d-flex align-items-center mt-8">
        <h2 class="text-lg font-medium me-auto">
            Change Password
        </h2>
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
                        <form class="form" id="Resetform" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="input-form">
                                <label class="d-flex flex-column flex-sm-row">Current Password </label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control input w-100 mt-2"
                                    placeholder="Please enter your current password" minlength="2" required>
                                <div class="invalid-feedback">
                                    This field is required
                                </div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> Password </label>
                                <input type="password" name="password" id="password"
                                    class="form-control input w-100 mt-2" placeholder="Please enter your new password"
                                    minlength="2" required>
                                <div class="invalid-feedback">
                                    This field is required
                                </div>
                            </div>
                            <div class="input-form mt-3">
                                <label class="d-flex flex-column flex-sm-row"> Confirm Password </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control input w-100 mt-2"
                                    placeholder="Please enter your confirm password" minlength="2" required>
                                <div class="invalid-feedback">
                                    This field is required
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-white mt-5">Submit</button>
                             <a href="{{ route('AdminDashboard') }}" class="btn btn-danger text-white mt-5"> Back </a>
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
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.validator.addMethod("same_value", function(value, element) {
            return $('#current_password').val() != $('#password').val()
        });

        $(".form").validate({
            rules: {
                current_password: {
                    required: true,
                    minlength: 8,
                    maxlength: 16,
                },
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 16,
                    same_value: true,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                current_password: {
                    required: "Please enter current password",
                    minlength: "Current password must be greater than {0} characters",
                    maxlength: "Current password must be less than {0} characters",
                },
                password: {
                    required: "Please enter new password",
                    minlength: "New password must be greater than {0} characters",
                    maxlength: "New password must be less than {0} characters",
                    same_value: 'New Password and Current Password must be different',
                },
                password_confirmation: {
                    required: "Please enter confirmation password",
                    equalTo: "Confirm password must be same as New Password"
                }
            },

            submitHandler: function(form) {
                save(form);
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
            url: "{{ route('UpdateAdminChangePassword') }}",
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(result) {
                $('.loader').hide();
                console.log(result);
                $(".form")[0].reset();
                if (result.status == 1) {
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
