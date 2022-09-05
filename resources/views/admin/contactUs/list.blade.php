@extends('admin.layout.index')
@section('css')
<style>
    .badge {
        cursor: context-menu !important;
    }
</style>
@endsection
@section('title') {{ __('labels.contact_us_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('contactUs.index') }}"><span class="breadcrumb--active">
                {{__('labels.contact_us_center')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')


    {{-- <div class="row mt-3">
        <div class="form-group col-md-4">
            <label for="daterange">{{ __('labels.users_type') }}</label>
            <select id="userType" name="userType" class="form-select form-select mt-2 userType">
                <option value="0">{{ __('labels.all_user') }}</option>
                <option value="{{config('constant.sales_executive_id')}}">{{ __('labels.sales_executive') }}</option>
                <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                </option>
            </select>
        </div>
    </div> --}}


    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.contact_us_list_title') }}
                    </h2>

                    {{-- @if($userType == '')
                    <a href="{{ route('users.create',['userType' => $userType]) }}"
                        class="ms-auto d-flex align-items-center btn btn-primary" id="addForm"><i
                            class="feather-plus-circle btn-add me-2"> </i> {{ __('labels.add_new') }}</a>
                    @endif --}}
                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="contactUs"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{ __('labels.created_by') }}</th>
                                <th>{{ __('labels.email') }}</th>
                                <th>{{ __('labels.mobile_no') }}</th>
                                {{-- <th>{{__('labels.message')}}</th> --}}
                                <th>{{ __('labels.create_date_time') }}</th>
                                <th width="10px">{{ __('labels.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END: General Report -->
        </div>
    </div>
</div>
<!-- END: Content -->
{{-- @include('admin.contactUs.modal') --}}

@endsection

@section('js')
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script>
    $('.vehicleNumber').hide(1000);

    var table;
    $(function() {
        table = $('#contactUs').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('contactUs.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.userType = $('#userType').val();
                }
            },
            aaSorting: true,
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: "fullName",
                    name: "fullName",
                },
                {
                    data: "email",
                    name: "email",
                },
                {
                    data: "mobileNumber",
                    name: "mobileNumber",
                },
                // {
                //     data: "message",
                //     name: "message",
                // },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": true,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "aaSorting": [
                [4, 'desc']
            ],
            "pageLength": 50
        });

        // filter after reload table

    });

    $(document).on('change', '.userType', function() {
            console.log($('#userType').val());
            table.ajax.reload();
        });

    $("#contactUs").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this contact?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


    $(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending"){
            $('#userStatusChange-modal').modal('show');
            }

            var url = $(this).data('url');
            form = $('#updateStatus');
            form.attr('action', url);

            $('#dataId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');
    });

    $(document).on('change', '.requestStatus', function() {
        if($(this).val() == 'Approved')
        {
            $('.vehicleNumber').show(1000);
        }
        else
        {
            $('.vehicleNumber').hide(1000);
        }
    });


    $(document).on('click', '.resetForm', function() {

        $("#updateStatus").trigger('reset');
        $('.vehicleNumber').hide(1000);
    });


    $(function() {

        // This Function is used for validation
        $(".updateStatus").validate({
                ignore: ":hidden:not(.my_item)",
                rules: {
                    vehicleNumber:{
                        required:true,
                    },
                },
                messages: {
                    vehicleNumber:{
                        required:'Please enter vehicle number',
                    },
                },
                submitHandler: function(form) {
                var requestStatus =  $('#requestStatus').find(":selected").text();

                if(requestStatus == 'Approved')
                {
                    var val = checkDataIsCorrect();
                    if(val)
                    {
                        form.submit();
                    }
                    return false;
                }
                else
                {
                    form.submit();
                }
                },
                invalidHandler: function(form, validator) {
                }
            });
    });
</script>
@endsection
