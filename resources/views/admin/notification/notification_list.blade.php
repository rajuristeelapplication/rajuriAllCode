@extends('admin.layout.index')
@section('css')
    <link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
@section('title') {{ __('labels.notification_list_title') }}  @endsection
@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
        <!-- BEGIN: Top Bar -->

    @section('navigation')
        <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
            <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <a href="{{ route('notifications.index') }}"><span class="breadcrumb--active">{{ __('labels.notification_list_title') }}</span></a>
        </div>
    @endsection
    @include('admin.common.notification')

    <div class="row mt-3">
        <div class="form-group col-md-3">
            <label for="formType"><span class="fa fa-filter mb-2"></span> Notification Modules</label>
            <select id="formType" name="formType" class="form-select form-select mt-2 formType select2Class">
                <option value="0">Select modules</option>
                <option value="Knowledge">Knowledge</option>
                <option value="Complain">Complain</option>
                <option value="Reimbursement">Reimbursement</option>
                <option value="Leave">Leave</option>
                <option value="Order">Order</option>

            </select>
        </div>
    </div>

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{ __('labels.notification_list_title') }}
                    </h2>
                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="faqs" class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">{{ __('labels.no') }}</th>
                                <th>{{ __('labels.create_at') }}</th>
                                {{-- <th>{{ __('labels.notification_user_name') }}</th> --}}
                                <th>{{ __('labels.notification_sender_name') }}</th>
                                <th>module</th>
                                <th>{{ __('labels.notification_title') }}</th>
                                {{-- <th>{{ __('labels.create_date') }}</th> --}}
                                <th>{{ __('labels.action') }}</th>
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


@endsection

@section('js')
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>
<script>
    var table;
    $(function() {
        table = $('#faqs').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            "ajax": {
                "url": '{{ url(route('notifications.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function(d) {
                    d.notificationModule = $('#formType').val();
                },

            },
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    visible: false,
                },
                // {
                //     data: 'userName',
                //     name: 'userName'
                // },
                {
                    data: 'senderName',
                    name: 'senderName'
                },
                {
                    data: 'module',
                    name: 'module'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                //   {
                //     data: 'createdOn',
                //     name: 'createdOn'
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
             ],
            "aaSorting": [
                [1, 'desc']
            ],
            "pageLength": 50
        });

        // filter after reload table
        $("#userType").on('click', function() {
            table.ajax.reload();
        });
    });

    $(document).on('change', '.select2Class,.dob', function() {
            table.ajax.reload();
        });

    $('.notificationCounterClass').addClass('notification-hide-class');

</script>
@endsection
