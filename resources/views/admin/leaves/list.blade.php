@extends('admin.layout.index')
@section('css')
<style>
    .badge {
        cursor: context-menu !important;
    }

    .addClass
    {
        display: none;
    }
</style>
@endsection
@section('title') {{ __('labels.leave_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('leaves.index') }}"><span class="breadcrumb--active"> {{__('labels.manage_leave')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')


    <div class="row mt-3">
        <div class="form-group col-md-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.leave_type') }}</label>
            <select id="leave_type" name="leave_type" class="form-select form-select mt-2 leave_type select2Class">
                <option value="0">{{ __('labels.all_leave_type') }}</option>
                <option value="{{__('labels.casual_leave')}}">{{ __('labels.casual_leave') }}</option>
                <option value="{{__('labels.medical_leave')}}">{{ __('labels.medical_leave') }}</option>
            </select>
        </div>

        <div class="form-group col-md-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.status') }}</label>
            <select id="leave_status" name="leave_status" class="form-select form-select mt-2 leave_status select2Class">
                <option value="0">{{ __('labels.all_status') }}</option>
                <option value="{{__('labels.pending')}}">{{ __('labels.pending') }}</option>
                <option value="{{__('labels.approved')}}">{{ __('labels.approved') }}</option>
                <option value="{{__('labels.rejected')}}">{{ __('labels.rejected') }}</option>
            </select>
        </div>


        @if(count($userNameList) >0 )

            <div class="form-group col-md-4">
                <label for="name"><span class="fa fa-filter mb-2"></span> Executive</label>
                <select id="employeeType" name="employeeType"
                    class="form-select form-select mt-2 employeeType select2Class">
                    <option value="0" selected>All Executive</option>
                    @foreach($userNameList as $name)
                    <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                    @endforeach
                </select>
            </div>

        @endif


    </div>


    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.leave_list_title') }}
                    </h2>

                    {{-- @if($leave_type == '')
                    <a href="{{ route('users.create',['leave_type' => $leave_type]) }}"
                        class="ms-auto d-flex align-items-center btn btn-primary" id="addForm"><i
                            class="feather-plus-circle btn-add me-2"> </i> {{ __('labels.add_new') }}</a>
                    @endif --}}
                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="leaves"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{ __('labels.full_name') }}</th>
                                <th>{{ __('labels.leave_type') }}</th>
                                <th>{{ __('labels.fromDate') }}</th>
                                <th>{{ __('labels.toDate') }}</th>
                                <th>{{ __('labels.status') }}</th>
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
@include('admin.leaves.modal')

@endsection

@section('js')
<script>
    var table;
    $(function() {
        table = $('#leaves').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('leaves.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.leave_type = $('#leave_type').val();
                    d.leave_status = $('#leave_status').val();
                    d.employeeType = $('#employeeType').val();
                }
            },
            aaSorting: true,
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'fullName',
                    name: 'fullName',
                },
                {
                    data: 'lType',
                    name: 'lType'
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
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "aaSorting": [
                [6, 'desc']
            ],
            "pageLength": 50
        });

        // filter after reload table

    });

    $(document).on('change', 'select', function() {
            table.ajax.reload();
        });

    $("#leaves").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this leave?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


    $(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending"){
            $('#leaveStatusChange-modal').modal('show');
            }

            var url = $(this).data('url');
            form = $('#updateLeaveStatus');
            form.attr('action', url);

            $('#leaveId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');
    });


    $(document).on('change', '.requestStatus', function() {

            var value = $(this).val();

           if(value == "Rejected")
           {
                $('.adminRejectOtherText').removeClass('addClass');
           }else{
                $('.adminRejectOtherText').addClass('addClass');
           }

        });

</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $(".select2Class").select2({
});
});
</script>
@endsection
