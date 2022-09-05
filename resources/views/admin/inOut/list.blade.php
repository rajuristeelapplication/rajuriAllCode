@extends('admin.layout.index')
@section('css')

<style>
    .badge {
        cursor: context-menu !important;
    }
</style>
@endsection
@section('title') {{ __('labels.in_out_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('inOuts.index') }}"><span class="breadcrumb--active">
                {{__('labels.in_out_flag')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')

    <div class="row mt-3">


            @if(Auth::user()->roleId == config('constant.ma_id'))
            <div class="form-group col-md-4">
                <label for="daterange"><span class="fa fa-filter "></span> {{ __('labels.users_type') }}</label>
                <select id="userType" name="userType" class="form-select form-select mt-2 userType">
                    <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                    </option>
                </select>
            </div>
            @else
            <div class="form-group col-md-4">
                <label for="daterange"><span class="fa fa-filter "></span> {{ __('labels.users_type') }}</label>
                <select id="userType" name="userType" class="form-select form-select mt-2 userType select2Class">
                    <option value="">{{ __('labels.all_user_type') }}</option>
                    <option value="{{config('constant.sales_executive_id')}}">{{ __('labels.sales_executive') }}</option>
                    <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                    </option>
                </select>
            </div>
            @endif

            <div class="form-group col-md-4">
                <label for="name"><span class="fa fa-filter "></span> {{__('labels.executive')}} </label>
                <select id="employeeType" name="employeeType"
                    class="form-select form-select  employeeType select2Class">
                    <option value="0" selected>{{__('labels.all_executive')}} </option>

                    @if(count($userNameList) > 0)
                        @foreach($userNameList as $name)
                        <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                        @endforeach
                    @endif
                </select>
            </div>


            <div class="form-group col-md-4">
                <label for="daterange"><span class="fa fa-filter mb-2"></span> {{__('labels.date_range')}} </label>
                <input type="text" class="form-control daterange" id="daterange" name="daterange"
                    placeholder="Select Date Range" readonly="readonly">
            </div>
    </div>


    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.in_out_list_title') }}
                    </h2>

                    {{-- @if($leadType == '')
                    <a href="{{ route('users.create',['leadType' => $leadType]) }}"
                        class="ms-auto d-flex align-items-center btn btn-primary" id="addForm"><i
                            class="feather-plus-circle btn-add me-2"> </i> {{ __('labels.add_new') }}</a>
                    @endif --}}
                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="inOuts"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{__('labels.create_at')}}</th>
                                <th>{{__('labels.created_by')}}</th>
                                <th >{{ __('labels.inAddress')}}</th>
                                <th>{{ __('labels.inDateTime') }}</th>
                                <th>{{ __('labels.outAddress') }}</th>
                                <th>{{ __('labels.outDateTime') }}</th>
                                <th> Travel Km</th>
                                <th>Travel Hours</th>
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
@include('admin.manageDealer.modal')

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var table;
    $(function() {
        table = $('#inOuts').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('inOuts.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.employeeType = $('#employeeType').val();
                    d.daterange = $('#daterange').val();
                    d.userType  = $('#userType').val();
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
                    data: '{{__('labels.created_by_db')}}',
                    name: '{{__('labels.created_by_db')}}',
                },
                {
                    data: '{{__('labels.inAddress_db')}}',
                    name: '{{__('labels.inAddress_db')}}',
                },
                {
                    data: '{{__('labels.inDateTime_db')}}',
                    name: '{{__('labels.inDateTime_db')}}'
                },
                {
                    data: '{{__('labels.outAddress_db')}}',
                    name: '{{__('labels.outAddress_db')}}'
                },
                {
                    data: '{{__('labels.outDateTime_db')}}',
                    name: '{{__('labels.outDateTime_db')}}',
                },
                {
                    data: 'travelKm',
                    name: 'travelKm',
                },
                {
                    data: 'travelMinutes',
                    name: 'travelMinutes',
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
    jQuery(function($) {
    $(document).on('change', '.employeeType, .daterange,.userType', function() {
            table.ajax.reload();
        });
    });

    $("#inOuts").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var leadType = $(this).attr('data-leadType');

        $('#statusTitle').text('Do you really want to ' + status + ' this attendance?');
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

<script>
    var date = new Date();
    date.setMonth(date.getMonth() - 12);

    $('#daterange').daterangepicker({
        'minDate': date,
        'maxDate': new Date(),
        'drops': "down",
        'dateLimit': {
            days: 91
        },
        locale: {
            // format: 'YYYY-MM-DD'
            format: 'DD/MM/YYYY'
        }
    });


    $( function() {
    $(".select2Class").select2({
});
});
</script>
@endsection
