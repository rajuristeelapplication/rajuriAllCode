@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">



@endsection
@section('title') {{__('labels.schedule_list_title')}} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('schedules.index') }}"><span
                class="breadcrumb--active">{{__('labels.manage_schedules')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="row mt-3">
        <div class="form-group col-md-4">
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

        <div class="form-group col-md-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Actor Name</label>
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

        <div class="form-group col-md-4">
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


        <div class="form-group col-md-4 mt-4">
            <label for="scheduleType"><span class="fa fa-filter mb-2"></span> {{ __('labels.s_type') }}</label>
            <select id="scheduleType" name="scheduleType" class="form-select form-select mt-2 scheduleType select2Class">
                <option value="0">{{ __('labels.all_s_type') }}</option>
                <option value="{{__('labels.dealer_visit')}}">{{ __('labels.dealer_visit') }}</option>
                <option value="{{__('labels.site_visit')}}">{{ __('labels.site_visit') }}</option>
                <option value="{{__('labels.influencer_visit')}}">{{ __('labels.influencer_visit') }}</option>
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="schedule"><span class="fa fa-filter mb-2"></span> {{ __('labels.schedule_flag') }}</label>
            <select id="schedule" name="schedule" class="form-select form-select mt-2 schedule select2Class">
                <option value="0">{{ __('labels.all_schedule_flag') }}</option>
                <option value="{{__('labels.upcoming')}}">{{ __('labels.upcoming') }}</option>
                <option value="{{__('labels.history')}}">{{ __('labels.history') }}</option>
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> Purpose </label>
            @php
            $purposeArray = config('constant.purposeArray');
            @endphp
            <select id="purpose" name="purpose" class="form-select form-select w-100 mt-2 mb-2 purpose select2Class">
                <option value="" selected disabled>Select Purpose</option>

                @if(count($purposeArray) > 0)

                @foreach ($purposeArray as $key => $purpose)
                <option value="{{ $purpose }}"> {{ $purpose }}</option>
                @endforeach

                @endif
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Schedule Date  </label>

            <input type="text" name="scheduleDate" id="scheduleDate"
                class="form-control input-valid w-100 mt-2 mb-2 datepickerdate scheduleDate"  placeholder="please select date "
                aria-required="true" readonly>
        </div>

        <div class="form-group col-md-2 mt-4">
            <label for="schedule"><span class="fa fa-filter mb-2"></span> Hour</label>
            <select id="hours" name="hours" class="form-select form-select mt-2 hours select2Class">
                <option value="">Selectg Hour</option>
                @for ($i=1;$i<=12;$i++)
                <option value="{{ $i<=9 ? '0'.$i : $i }}">{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="form-group col-md-2 mt-4">
            <label for="schedule"><span class="fa fa-filter mb-2"></span>Minute</label>
            <select id="minutes" name="minutes" class="form-select form-select mt-2 hours select2Class">
                <option value="">Select Minute</option>
                @for ($i=0;$i<=59;$i++)



                <option value="{{ $i<=9 ? '0'.$i : $i }}">{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="form-group col-md-2 mt-4">
            <label for="schedule"><span class="fa fa-filter mb-2"></span>AM/PM</label>
            <select id="amPM" name="amPM" class="form-select form-select mt-2 amPM select2Class">
                <option value="">Select AM/PM</option>
                <option value="AM">AM</option>
                <option value="PM">PM</option>

            </select>
        </div>


        {{-- <div class="form-group col-md-3 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Schedule Time  </label>
            <input type="text" name="scheduleTime"
            id="scheduleTime"
            class="form-control input-valid w-100 mt-2 mb-2 timePic scheduleTime"
            placeholder="Please Select {{__('labels.time')}}"
            aria-required="true" readonly>
        </div> --}}


        <div class="form-group text-right">
            <button type="button" onClick="history.go(0)" title="Filter Reset" class="btn btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off">
                <i class="fa fa-filter" aria-hidden="true"></i> Reset
            </button>
        </div>

    </div>
    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{__('labels.schedule_list_title')}}
                    </h2>
                    <!-- BEGIN: Show Modal Toggle -->
                    <a href="{{ route('schedules.create') }}" class="ms-auto d-flex align-items-center btn btn-primary"
                        id="addForm">
                        <i class="feather-plus-circle btn-add me-2"> </i>{{__('labels.add_new')}}</a>
                    <!-- END: Show Modal Toggle -->
                </div>
                <br><br>
                <div class="table-responsive">
                    <table id="schedules"
                        class="last-cl-fxied display nowrap table table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">{{__('labels.no')}}</th>
                                <th>{{__('labels.created_by')}}</th>
                                <th>{{__('labels.dealer_name')}}</th>
                                <th>{{__('labels.user_type')}}</th>
                                <th>{{__('labels.s_type')}}</th>
                                <th>{{__('labels.date_time')}}</th>
                                <th>{{__('labels.create_at')}}</th>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script>
    var table;
    $(function() {

        $(".datepickerdate").datepicker({
            shortYearCutoff: 1,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
        });

        $(".timePic").timepicker({
                    timeFormat: 'HH:mm',
                    interval: 1,
                    scrollbar: true,
                    change: tmTotalHrsOnSite
                    });

                    function tmTotalHrsOnSite () {
                        table.ajax.reload();
                    };


        table = $('#schedules').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('schedules.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function ( d ) {
                    d.userType = $('#userType').val();
                    d.employeeType = $('#employeeType').val();
                    d.dealerType = $('#dealerType').val();
                    d.scheduleType = $('#scheduleType').val();
                    d.schedule = $('#schedule').val();
                    d.scheduleDate = $('#scheduleDate').val();
                    d.scheduleTime = $('#scheduleTime').val();
                    d.purpose = $('#purpose').val();
                    d.hours = $('#hours').val();
                    d.minutes = $('#minutes').val();
                    d.amPM = $('#amPM').val();
                }

            },
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: '{{__("labels.created_by_db")}}',
                    name: '{{__("labels.created_by_db")}}',
                    visible: true,
                },
                {
                    data: '{{__("labels.name_db")}}',
                    name: '{{__("labels.name_db")}}',
                    visible: true,
                },
                {
                    data: '{{__("labels.user_type_db")}}',
                    name: '{{__("labels.user_type_db")}}',
                    visible: true,
                },
                {
                    data: '{{__("labels.s_type_db")}}',
                    name: '{{__("labels.s_type_db")}}',
                    visible: true,
                },

                {
                    data: 'sDateFormate',
                    name: 'sDateFormate',
                    visible: true,
                },
                // {
                //     data: '{{__("labels.is_active_db")}}',
                //     name: '{{__("labels.is_active_db")}}',
                //     orderable: false
                // },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    visible: false,
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
    });



    // $(document).on('change', '.userType, .employeeType, .dealerType, .scheduleType, .schedule,.scheduleDate,.purpose', function() {
    $(document).on('change', '.select2Class,.scheduleDate', function() {
            table.ajax.reload();
        });

    $("#schedules").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this brochure?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

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
