@extends('admin.layout.auth.index')
@section('title') {{ __('labels.reset_password_heading') }} @endsection
@section('content')
    <div class="container px-sm-5">
        <div class="d-block d-xl-grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="d-none d-xl-flex flex-column min-h-screen">
                <a href="" class="-intro-x d-flex align-items-center pt-5">
                    <!-- <img alt="{{ config('app.name') }} Admin panel" class="w-6" src="{{ url('images/logo.svg') }}"> -->
                    <span class="text-white text-lg ml-3"></span>
                </a>
                <div class="my-auto">
                    <img alt="{{ config('app.name') }} Admin panel" class="-intro-x w-1/2 -mt-16 w-sm-75"
                        src="{{ url('images/illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        {{__('labels.admin_forgotpassword_content')}}
                    </div>
                    <div class="-intro-x mt-5 text-lg text-white dark:text-gray-500"></div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto d-flex">
                <div
                    class="my-auto mx-auto ms-xl-5 bg-white xl:bg-transparent px-3 px-sm-3 py-5 p-xl-0 rounded-3 shadow-md xl:shadow-none w-100 w-sm-75 w-lg-50 w-xl-auto">
                    <div class="text-center"><img alt="Admin panel" src="{{ url('admin-assets/images/logo/logo.png') }}"
                            class="mb-2" style="height: auto; width: 300px; max-width: 100%;"></div>
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center text-xl-start">{{ __('labels.reset_password_heading') }}</h2>
                    <div class="intro-x mt-2 text-gray-500 d-xl-none text-center">{{__('labels.admin_signin_content')}}</div>

                    @include('admin.common.flash')
                    @if (!$alreadySet)
                        <form class="form-horizontal form-material" id="Resetform" method="post"
                            action="{{ route('ma.password.reset_process') }}">
                            @csrf
                            <input type="hidden" value="{{ $data['email'] ?? '' }}" name="email">
                            <div class="intro-x input-form mt-8">
                                <div class="password-field">
                                    <input type="password" id="password" name="password"
                                        class="intro-x form-control login__input input input--lg border-gray-300 d-block"
                                        placeholder="{{__('labels.new_password')}}" required>
                                    <a href="javascript:void(0)" class="btn-design"
                                        onclick="showPassword('password', 'passwordIconId')"><span id="passwordIconId"><i
                                                class="feather-eye-off"></i></span></a>
                                </div>
                                <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="intro-x input-form mt-8">
                                <div class="password-field">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="intro-x form-control login__input input input--lg border-gray-300 d-block"
                                        placeholder="{{__('labels.confirm_password')}}" required>
                                    <a href="javascript:void(0)" class="btn-design"
                                        onclick="showPassword('password_confirmation', 'confirmationPasswordIconId')"><span
                                            id="confirmationPasswordIconId"><i class="feather-eye-off"></i></span></a>
                                    <div class="invalid-feedback">{{__('labels.field_required')}}</div>
                            </div>
                            <div class="intro-x d-flex text-gray-500 text-xs sm:text-sm mt-4">
                                <div class="d-flex align-items-center me-auto">

                                    <label class="cursor-pointer select-none" for="remember-me"></label>
                                </div>
                                <a href="{{ route('adminLogin') }}">{{__('labels.admin_back_to_login')}}</a>
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center text-xl-start">
                                <button type="submit"
                                    class="button button--lg w-100 xl:w-32 text-white theme-bg xl:mr-3 align-top reset-btn">Send</button>
                                <button
                                    class="button mr-1 mb-2  button--lg w-100 xl:w-32 text-white theme-bg xl:mr-3 align-top reseting-btn"
                                    style="display: none;">
                                    <svg width="25" viewBox="0 0 120 30" xmlns="" fill="1a202c" class="w-4 h-4 ml-2">
                                        <circle cx="15" cy="15" r="15">
                                            <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s"
                                                values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                                            <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s"
                                                values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                                        </circle>
                                        <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                            <animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9"
                                                calcMode="linear" repeatCount="indefinite"></animate>
                                            <animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s"
                                                values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate>
                                        </circle>
                                        <circle cx="105" cy="15" r="15">
                                            <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s"
                                                values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                                            <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s"
                                                values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                                        </circle>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    @else
                        @if (!empty($data))
                            <h3> {{ $data }} </h3>
                            <div class="ml-auto m-t-10">
                                <a href="{{ route('maLogin') }}" class="text-muted"> {{__('labels.back_to_login_text')}} </a>
                            </div>
                        @else
                            <h3 class="text-right"> {{__('labels.pass_already_reset_text')}} </h3>
                            <div class="ml-auto m-t-10">
                                <a href="{{ route('maLogin') }}" class="text-muted"> {{__('labels.back_to_login_text')}} </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });

        function showPassword(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                $('#' + iconId).find('i').removeClass("feather-eye-off").addClass("feather-eye");
            } else {
                passwordInput.type = "password";
                $('#' + iconId).find('i').removeClass("feather-eye").addClass("feather-eye-off");
            }
        }
        $(document).ready(function() {
            var emailpattern =
                /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

            $.validator.addMethod(
                "regex",
                function(value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Please check your input."
            );

            $("#Resetform").validate({
                rules: {
                    email: {
                        required: true,
                        regex: emailpattern,
                        maxlength: 150,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 16,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    email: {
                        required: "{{__('labels.reset_email_valid')}}",
                        regex: "{{__('labels.reset_email_regex')}}",
                        maxlength: "{{__('labels.reset_email_max')}}",
                    },
                    password: {
                        required: "{{__('labels.reset_password_valid')}}",
                        minlength: "{{__('labels.reset_min_password_valid')}}",
                        maxlength: "{{__('labels.reset_max_password_valid')}}",
                    },
                    password_confirmation: {
                        required: "{{__('labels.reset_confirm_pass_valid')}}",
                        equalTo: "{{__('labels.reset_confirm_pass_equalto_valid')}}"
                    }
                },
                submitHandler: function(form) {
                    $('.reseting-btn').attr('disabled', true);
                    $('.reseting-btn').show();
                    $('.reset-btn').hide();
                    form.submit();
                }
            });

            $.validator.addMethod("same_value", function(value, element) {
                return $('#password').val() != $('#confirm_password').val()
            });
        });
    </script>
@endsection
