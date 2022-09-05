@extends('admin.layout.index')
@section('css')
<style>
    .badge {
        cursor: context-menu !important;
    }
    .hideClass{
        display:none;
    }
</style>
@endsection
@section('title')  {{ __('labels.follow_up_list') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>

        <a href="{{ route('followups.index') }}"><span class="breadcrumb--active">{{ __('labels.follow_up_list') }}</span></a>

    </div>
    @endsection
    @include('admin.common.notification')


    <div class="row mt-3">
        <div class="form-group col-md-3">
            <label for="userType"><span class="fa fa-filter mb-2"></span> {{ __('labels.form_type') }}</label>
            <select id="userType" name="userType" class="form-select form-select mt-2 userType select2Class">
                <option value="0">{{ __('labels.all_form') }}</option>
                <option value="{{__('labels.dealer_filter')}}">{{ __('labels.dealer_filter') }}</option>
                <option value="{{__('labels.engineer_filter')}}">{{ __('labels.engineer_filter') }}</option>
                <option value="{{__('labels.architect_filter')}}">{{ __('labels.architect_filter') }}</option>
                <option value="{{__('labels.mason_filter')}}">{{ __('labels.mason_filter') }}</option>
                <option value="{{__('labels.contractor_filter')}}">{{ __('labels.contractor_filter') }}</option>
                <option value="{{__('labels.construction_filter')}}">{{ __('labels.construction_filter') }}</option>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="name"><span class="fa fa-filter mb-2"></span>Actor Name</label>
            <select id="dealerType" name="dealerType"
                class="form-select form-select mt-2 dealerType select2Class">
                <option value="0" selected>All Actor Name</option>

                @if(count($dealerNameList) > 0)
                    @foreach($dealerNameList as $name)
                    <option value="{{ $name->id }}">{{ $name->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="name"><span class="fa fa-filter mb-2"></span> Executive</label>
            <select id="employeeType" name="employeeType"
                class="form-select form-select mt-2 employeeType select2Class">
                <option value="0" selected>All Executive</option>

                @if(count($userNameList) > 0)

                @foreach($userNameList as $name)
                <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                @endforeach

                @endif
            </select>
        </div>



    </div>

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0"> {{ __('labels.follow_up_list') }}
                    </h2>

                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="complaints"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>Date</th>
                                <th>{{ __('labels.complaint_user_name') }}</th>
                                <th>{{ __('labels.complaint_dealer_name') }}</th>
                                <th>{{ __('labels.follow_date') }}</th>
                                <th>{{ __('labels.follow_time') }}</th>
                                <th>{{ __('labels.follow_reminder_date') }}</th>
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

@endsection

@section('js')
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(".select2Class").select2({});

    var table;
    $(function() {
        table = $('#complaints').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('followups.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.userType = $('#userType').val();
                    d.employeeType = $('#employeeType').val();
                    d.dealerType = $('#dealerType').val();
                    d.scheduleType = $('#scheduleType').val();
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
                    data: "fullName",
                    name: "fullName",
                },

                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "fDateFormate",
                    name: "fDateFormate",
                },
                {
                    data: "fTimeFormate",
                    name: "fTimeFormate",
                },
                {
                    data: "rDateTimeFormate",
                    name: "rDateTimeFormate",
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

        $(document).on('change', '.select2Class', function() {
            table.ajax.reload();
        });



        // filter after reload table
    });

</script>
@endsection
