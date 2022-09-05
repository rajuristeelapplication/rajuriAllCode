@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
@endsection
@section('title') Setting @endsection
@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
        <!-- BEGIN: Top Bar -->
    @section('navigation')
        <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
            <a href="{{ route('AdminDashboard') }}"><span class="">Dashboard</span></a>
            <i class="
                    feather-chevron-right" class="breadcrumb__icon"></i>
                    <a href="{{ route('settings.index') }}"><span class="">Setting</span></a>
            <i class="
                            feather-chevron-right" class="breadcrumb__icon"></i>
                            <span class="breadcrumb--active"> Setting
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
                        Setting </h2>
                </div>
                <div class="mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: Form Validation -->
                        <div class="intro-y box">

                            <div class="p-5">
                                <div class="preview">
                                    @include('admin.common.flash')

                                        <form class="form" name="glossaryForm" id="glossaryForm" method="post"
                                            enctype="multipart/form-data" action="{{ route('settings.store') }}">

                                    {{ csrf_field() }}


                                    <div class="input-form">
                                        <label class="d-flex flex-column flex-sm-row"> Service Charge {{' ('. config('constant.currency') .')'}} <span
                                                class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="serviceCharge" id="serviceCharge"
                                            class="form-control input w-100 mt-2 mb-2 decimalOnly"
                                            value="{{  $userDetail->serviceCharge  ?? '' }}" maxlength="100"
                                            placeholder="Please enter service charge" aria-required="true">
                                    </div>


                                    <div class="text-right">
                                        <button type="submit"
                                            class="submit btn btn-primary text-white mt-5">Submit</button>
                                        <a href="{{ route('settings.index') }}"
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
                serviceCharge: {
                    required: true,
                },
            },

            messages: {
                serviceCharge:{
                    required: 'Please enter service charge',
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
