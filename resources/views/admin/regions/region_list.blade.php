@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
@section('title') {{__('labels.regions')}} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('regions.index') }}"><span class="breadcrumb--active">{{__('labels.regions')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')
    @include('admin.common.flash')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{__('labels.region_list_title')}}
                    </h2>
                    <!-- BEGIN: Show Modal Toggle -->
                    {{-- <a href="{{ route('regions.create') }}" class="ms-auto d-flex align-items-center btn btn-primary"
                        id="addForm">
                        <i class="feather-plus-circle btn-add me-2"> </i>{{__('labels.add_new')}}</a> --}}
                    <!-- END: Show Modal Toggle -->
                </div>
                <br><br>
                <div class="table-responsive">
                    <table id="regions"
                        class="last-cl-fxied display nowrap table table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">{{__('labels.no')}}</th>
                                <th>Created At</th>
                                {{-- <th>{{__('labels.state')}}</th> --}}
                                <th>{{__('labels.region')}}</th>
                                <th>{{__('labels.isActive')}}</th>
                                <th width="10px">{{__('labels.action')}}</th>
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
        table = $('#regions').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('regions.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function() {

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
                //     data: '{{__("labels.state_db")}}',
                //     name: '{{__("labels.state_db")}}',
                //     visible: true,
                // },
                {
                    data: '{{__("labels.region_db")}}',
                    name: '{{__("labels.region_db")}}',
                    visible: true,
                },
                {
                    data: 'isActive',
                    name: 'isActive',
                    orderable: false
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
    });


    $("#regions").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this region?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


</script>
@endsection
