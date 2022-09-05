@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
    .ui-datepicker-calendar {
    display: none;
}
</style>
@endsection
@section('title') User {{ $userDetail ? '' : '' }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">Dashboard</span></a>
        <i class="
                    feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('users.index',['editType' => $editType]) }}"><span class="">Users</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active"> User {{ empty($userDetail) ? '' : '' }}
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
                        User {{ $userDetail ? '' : '' }} </h2>
                        {{-- User {{ $userDetail ? 'Edit' : 'Create' }} </h2> --}}
                </div>
                <div class="mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: Form Validation -->
                        <div class="intro-y box">

                            <div class="p-5">
                                <div class="preview">
                                    @include('admin.common.flash')

                                    @if (empty($userDetail))
                                    <form class="form" name="userForm" id="userForm" method="post"
                                        enctype="multipart/form-data"
                                        action="{{ route('users.update', ['users'=>$userDetail->id??" 0" ,'editType'=>
                                        $editType]) }}">
                                        @else
                                        <form class="form" name="userForm" id="userForm" method="post"
                                            enctype="multipart/form-data"
                                            action="{{ route('users.update', ['users'=>$userDetail->id ,'editType' => $editType]) }}">
                                            @endif
                                            {{ csrf_field() }}


                                            <input type="hidden" name="id" id="id"
                                                value="{{ !empty($userDetail)? $userDetail->id : '' }}" />

                                            <div class="input-form col-6">
                                                <label class="d-flex flex-column flex-sm-row">
                                                    {{__('labels.full_name')}}</label>
                                                <input type="text" name="fullName" id="fullName"
                                                    class="form-control input w-100 mt-2 mb-2"
                                                    value="{{  $userDetail->fullName  ?? '' }}" maxlength="30"
                                                    placeholder="Please enter first name" aria-required="true" readonly>
                                            </div>

                                        @if ($editType == 'credit')


                                            <div class="input-form col-6">
                                                <label class="d-flex flex-column flex-sm-row mt-2">
                                                    {{__('labels.casual_leave')}}  &nbsp;<span class="badge badge-info">{{$totalRemainingLeaveBalance->totalCasualLeave ?? 0}}</span></label>
                                                <input type="text" name="casualLeave" id="casualLeave"
                                                    class="form-control input w-100 mt-2 mb-2 decimalOnly"
                                                    value="{{  $casualLeave->noOfLeave  ?? '0' }}" maxlength="5"
                                                    placeholder="Please enter casual leave" aria-required="true">
                                            </div>

                                            <div class="input-form col-6">
                                                <label class="d-flex flex-column flex-sm-row mt-2">
                                                    {{__('labels.medical_leave')}}  &nbsp;<span class="badge badge-info">{{$totalRemainingLeaveBalance->totalMedicalLeave ?? 0}}</span></label>
                                                <input type="text" name="medicalLeave" id="medicalLeave"
                                                    class="form-control input w-100 mt-2 mb-2 decimalOnly"
                                                    value="{{  $medicalLeave->noOfLeave  ?? '0' }}" maxlength="5"
                                                    placeholder="Please enter medical leave" aria-required="true">
                                            </div>

                                        @endif

                                        @if ($editType == 'pdf')
                                        <div class="input-form">
                                            <label for="monthYear">Month Year</label>
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <input type='text' id='month' name="month" value="{{isset($paySlipPdf->month)? Carbon\Carbon::parse($paySlipPdf->month)->format('F Y') :""}}" class="form-control mt-2 mb-2" readonly/>
                                            </div>
                                        </div>

                                            <div class="input-form">
                                                <label for="vertical-form-1" class="form-label mt-2">
                                                    Pay Slip PDF </label>
                                                <div class="col-sm-6 col-md-6 col-lg-6">
                                                    <input class="form-control dropify mt-2 mb-2" type="file" value=""
                                                        id="payPdf" {{-- data-min-width="98" data-min-height="98"
                                                        data-max-width="998" data-max-height="998" --}} name="payPdf"
                                                        tabindex="5" placeholder="Please upload pdf"
                                                        data-show-remove="false" accept="application/pdf"
                                                        data-allowed-file-extensions="pdf"
                                                        data-default-file="{{$paySlipPdf->payPdf ?? ""}}">
                                                    <label id="image-error" class="error" for="payPdf"
                                                        style="display: none;">Please select pdf</label>
                                                    {{-- <small class="form-control-feedback"> Please upload image size
                                                        minimum
                                                        (width) 100 x
                                                        100 (Height) and maximum (width) 1000 x 1000 (Height) </small>
                                                    --}}

                                                </div>

                                                <div class="imageData col-xs-12 col-sm-12 col-md-2 col-lg-2 mt-2 mb-5"
                                                    style="display: {{ isset($paySlipPdf->payPdf) ? 'block' : 'none' }}">

                                                    <a href="{{$paySlipPdf->payPdf ?? ""}}" target="__blank" type="button"
                                                        title="View PDF"
                                                        class="btn btnProfileDelete btn-dark  btn-circle "><i
                                                            class="fa fa-file-pdf-o block mx-auto"></i></a>
                                                </div>

                                        @endif

                                                <div class="text-left">
                                                    <button type="submit"
                                                        class="submit btn btn-primary text-white mt-5">Add</button>
                                                    <a href="{{ route('users.index',['parameter' => 'all']) }}"
                                                        class="btn btn-danger text-white mt-5"> Back</a>
                                                </div>
                                        </form>
                                </div>

                                @if ($editType == 'credit')
                                <div class="grid grid-cols-12 gap-6 mt-3">
                                    <div class="intro-y box col-span-12 lg:col-span-12">
                                        <div class="p-5">
                                            <div class="container mt-5 mb-5">
                                                <div class="row">
                                                        <div class="table-responsive">
                                                            {{-- last-cl-fxied  --}}
                                                            <table id="leaves"
                                                                class="display nowrap table table-hover table-striped table-bordered"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr><th class="text-center text-white h5 bg-secondary" colspan="7">Leave History</th></tr>
                                                                    <tr>

                                                                        <th>{{ __('labels.no') }}</th>
                                                                        {{-- <th>{{ __('labels.full_name') }}</th> --}}
                                                                        <th>{{ __('labels.leave_type') }}</th>
                                                                        <th>{{ __('labels.noOfLeave') }}</th>
                                                                        <th>{{ __('labels.fromDate') }}</th>
                                                                        <th>{{ __('labels.toDate') }}</th>
                                                                        <th>{{ __('labels.status') }}</th>
                                                                        <th>{{ __('labels.create_date_time') }}</th>
                                                                        {{-- <th width="10px">{{ __('labels.action') }}</th> --}}
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif


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
<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

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
        @if (empty($paySlipPdf->payPdf))

        $(".form").validate({
            ignore: "",
            rules: {
                payPdf: {
                    required: true,
                },
                month: {
                    required: true,
                },
            },

            messages: {
                payPdf:{
                    required: 'Please select pdf',
                },
                month:{
                    required: 'Please select month year',
                },
            },
            submitHandler: function(form) {

                form.submit();

            },
            invalidHandler: function(form, validator) {


            }
        });
        @endif

        $(".submit").click(function() {
            $(".submit").removeClass("loading");
        });

        $(".submit").addClass("loading");


    });
</script>

{{-- MONTH YEAR PICKER --}}
{{-- <script>
    $(document).ready(function() {
   $('#month').datepicker({
     changeMonth: true,
     changeYear: true,
     dateFormat: 'MM yy',
     maxDate: new Date(),

     onClose: function() {
        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
     },

     beforeShow: function() {
       if ((selDate = $(this).val()).length > 0)
       {
          iYear = selDate.substring(selDate.length - 4, selDate.length);
          iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), $(this).datepicker('option', 'monthNames'));
          $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
           $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
       }
    }
  });
});
</script> --}}
<script>
    $("#month").datepicker( {
    format: "MM yyyy",
    viewMode: "months",
    minViewMode: "months",
    endDate: '+0m',
});
</script>

<script>
$(function() {
        table = $('#leaves').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('users.searchLeave')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.userId = $('#id').val();
                }
            },
            aaSorting: true,
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                // {
                //     data: 'fullName',
                //     name: 'fullName',
                // },
                {
                    data: 'lType',
                    name: 'lType'
                },
                {
                    data: 'noOfLeave',
                    name: 'noOfLeave'
                },
                {
                    data: 'fromDate',
                    name: 'fromDate'
                },
                {
                    data: 'toDate',
                    name: 'toDate'
                },
                {
                    data: 'lRStatus',
                    name: 'lRStatus',
                    orderable: false
                },
                {
                    data: 'createdAtFormate',
                    name: 'createdAtFormate',
                    "visible": true,
                },
                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false
                // },
            ],
            "aaSorting": [
                [6, 'desc']
            ],
            "pageLength": 10
        });

        // filter after reload table

    });

    $(document).ready(function(){
        $('#id').val();
    });
</script>
@endsection
