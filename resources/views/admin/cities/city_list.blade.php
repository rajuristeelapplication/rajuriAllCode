@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
@section('title') {{__('labels.cities')}} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('cities.index') }}"><span class="breadcrumb--active">{{__('labels.cities')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')
    @include('admin.common.flash')



    <div class="row mt-3">
        <div class="form-group col-md-3">
            <label for="formType"><span class="fa fa-filter mb-2"></span> State Name</label>
            <select id="stateId" name="stateId" class="form-select form-select mt-2 formType select2Class">
                <option value="">All State Name</option>

                @if(count($stateNameList) > 0)
                @foreach($stateNameList as $name)
                <option value="{{ $name->id }}">{{ $name->sName }}</option>
                @endforeach
                @endif


            </select>
        </div>
    </div>


    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{__('labels.city_list_title')}}
                    </h2>
                    <!-- BEGIN: Show Modal Toggle -->
                    <a href="{{ route('cities.create') }}" class="ms-auto d-flex align-items-center btn btn-primary"
                        id="addForm">
                        <i class="feather-plus-circle btn-add me-2"> </i>{{__('labels.add_new')}}</a>
                    <!-- END: Show Modal Toggle -->
                </div>
                <br><br>
                <div class="table-responsive">
                    <table id="cities"
                        class="last-cl-fxied display nowrap table table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">{{__('labels.no')}}</th>
                                <th>{{__('labels.state')}}</th>
                                <th>{{__('labels.city')}}</th>
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
        table = $('#cities').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('cities.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function( d ) {
                    d.stateId = $('#stateId').val();
                },

            },
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: '{{__("labels.state_db")}}',
                    name: '{{__("labels.state_db")}}',
                    visible: true,
                },
                {
                    data: '{{__("labels.city_db")}}',
                    name: '{{__("labels.city_db")}}',
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


    $(document).on('change', '.select2Class,.dob', function() {
            table.ajax.reload();
        });



    $("#cities").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this city?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $(".select2Class").select2({});
});
</script>

@endsection
