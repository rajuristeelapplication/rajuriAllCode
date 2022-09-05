@extends('admin.layout.index')
@section('css')
<style>
    .badge {
        cursor: context-menu !important;
    }
</style>
@endsection
@section('title') {{ __('labels.user_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>



        <a href="{{ route('users.index',['parameter' => $parameter]) }}"><span class="breadcrumb--active">
                @if($parameter == 'all')
                {{__('labels.users')}}
                @elseif($parameter == 'hr')
                {{__('labels.users_hr')}}
                @elseif($parameter == 'ma')
                {{__('labels.users_ma')}}
                @endif
                </span>
        </a>



    </div>
    @endsection
    @include('admin.common.notification')

    @if($parameter == 'all')
    <div class="row mt-3">

        @if(Auth::user()->roleId != config('constant.ma_id'))
            <div class="form-group col-md-4">
                <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.users_type') }}</label>
                <select id="userType" name="userType" class="form-select form-select mt-2 userType select2Class">
                    <option value="0">{{ __('labels.all_user_type') }}</option>
                    <option value="{{config('constant.sales_executive_id')}}">{{ __('labels.sales_executive') }}</option>
                    <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                    </option>
                </select>
            </div>
        @endif

        <div class="form-group col-md-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span>Select Status</label>
            <select id="userStatus" name="userStatus" class="form-select form-select mt-2 userStatus select2Class">
                <option value="">Select Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
            </select>
        </div>

    </div>
    @endif

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">

                        @if($parameter == 'all')
                        {{__('labels.user_list_title')}}
                        @elseif($parameter == 'hr')
                        {{__('labels.hr_list_title')}}
                        @elseif($parameter == 'ma')
                        {{__('labels.ma_list_title')}}
                       @endif

                    </h2>

                    @if($parameter == 'all')
                        @if(Auth::user()->roleId != config('constant.hr_id'))

                        <a href="{{ route('users.create') }}" class="ms-auto d-flex align-items-center btn btn-primary"
                        id="addForm"><i class="feather-plus-circle btn-add me-2"> </i> {{ __('labels.add_new') }}</a>

                        @endif
                    @endif

                    @if($parameter != 'all')
                    <a href="{{ route('users.create.param',['parameter' => $parameter]) }}"
                        class="ms-auto d-flex align-items-center btn btn-primary" id="addForm"><i
                        class="feather-plus-circle btn-add me-2"> </i> {{ __('labels.add_new') }}
                    </a>
                    @endif

                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="users"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{ __('labels.create_at') }}</th>
                                <th>{{ __('labels.full_name') }}</th>
                                <th>{{ __('labels.email') }}</th>
                                <th>{{ __('labels.mobile_no') }}</th>
                                <th>{{ __('labels.users_type') }}</th>
                                <th>{{ __('labels.isActive') }}</th>
                                <th>{{ __('labels.status') }}</th>
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
@include('admin.users.modal')

@endsection

@section('js')
<script>
    var table;
    $(function() {

        table = $('#users').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('users.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.userType = $('#userType').val();
                    d.parameter = "{{ $parameter }}";
                    d.userStatus  = $('#userStatus').val();
                }
            },
            aaSorting: true,
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": false,
                },
                {
                    data: 'fullName',
                    name: 'fullName',
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'mobileNumber',
                    name: 'mobileNumber'
                },
                {
                    data: 'roleId',
                    name: 'roleId',
                    visible:"{{  $parameter != 'all' ? false : true }}"
                },

                {
                    data: 'isActive',
                    name: 'isActive',
                    orderable: false
                },
                {
                    data: 'userStatus',
                    name: 'userStatus',
                    orderable: false,
                    visible:"{{  $parameter != 'all' ? false : true }}"
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "aaSorting": [
                [1, 'desc']
            ],

            "pageLength": 50
        });

        // filter after reload table

    });

    $(document).on('change', '.userType,.userStatus', function() {
            table.ajax.reload();
        });

    $("#users").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this user?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


    $(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending"){
            $('#userStatusChange-modal').modal('show');
            }

            var url = $(this).data('url');
            form = $('#updateUserStatus');
            form.attr('action', url);

            $('#leaveId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');
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
