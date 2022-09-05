@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
@section('title') {{__('labels.daily_update_list_title')}} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('schedules.index') }}"><span
                class="breadcrumb--active">{{__('labels.manage_daily_update')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')
    @include('admin.common.flash')

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
            <label for="name"><span class="fa fa-filter mb-2"></span> Actor Name</label>
            <select id="dealerType" name="dealerType" class="form-select form-select mt-2 dealerType select2Class">
                <option value="0" selected>All Actor Name </option>


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


        <div class="form-group col-md-3">
            <label for="scheduleType"><span class="fa fa-filter mb-2"></span> {{ __('labels.s_type') }}</label>
            <select id="scheduleType" name="scheduleType"
                class="form-select form-select mt-2 scheduleType select2Class">
                <option value="0">{{ __('labels.all_s_type') }}</option>
                <option value="{{__('labels.dealer_visit')}}">{{ __('labels.dealer_visit') }}</option>
                <option value="{{__('labels.site_visit')}}">{{ __('labels.site_visit') }}</option>
                <option value="{{__('labels.influencer_visit')}}">{{ __('labels.influencer_visit') }}</option>
            </select>
        </div>


        <div class="form-group col-md-3 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.state') }} </label>
            <select id="stateId" name="stateId"
                class="form-select form-select mt-2 stateId select2Class">
                <option value="" selected>{{ __('labels.state_select_valid') }}</option>

                {{-- @if(count($stateNameLists) > 0)

                @foreach($stateNameLists as $name)
                <option value="{{ $name->dStateId }}">{{ $name->dSName }}</option>
                @endforeach

                @endif --}}

                @if(count($allStates) > 0)
                @foreach($allStates as $name)
                     <option value="{{ $name->id }}">{{ $name->sName }}</option>
                @endforeach
                @endif

            </select>
        </div>

        <div class="form-group col-md-3 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.district_name') }}  </label>
            <select id="cityId" name="cityId"
                class="form-select form-select mt-2 cityId select2Class">
                <option value="" selected>{{ __('labels.select_district_name') }} </option>

                {{-- @if(count($districtNameLists) > 0)
                    @foreach($districtNameLists as $name)
                    <option value="{{ $name->dCityId }}">{{ $name->dCName }}</option>
                    @endforeach
                @endif --}}
            </select>
        </div>

        <div class="form-group col-md-3 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.taluka_name') }}  </label>
            <select id="talukaId" name="talukaId"
                class="form-select form-select mt-2 talukaId select2Class">
                <option value="" selected>{{ __('labels.select_taluka_name') }}</option>

                {{-- @if(count($talukaNameLists) > 0)

                @foreach($talukaNameLists as $name)
                <option value="{{ $name->dTalukaId }}">{{ $name->dTName }}</option>
                @endforeach

                @endif --}}
            </select>
        </div>

        <div class="form-group col-md-3 mt-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span>Other Brand</label>
            <select id="otherBrand" name="otherBrand" class="form-select form-select mt-2 expense_type select2Class">
                <option value="0">All Other Brand</option>

                @if(count($getExpense) > 0)

                @foreach($getExpense as $name)
                <option value="{{ $name->id }}">{{ $name->eName }}</option>
                @endforeach

                @endif
            </select>
        </div>

        <div class="form-group col-md-3 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Schedule Date  </label>

            <input type="text" name="scheduleDate" id="scheduleDate"
                class="form-control input-valid w-100 mt-2 mb-2 datepickerdate scheduleDate"  placeholder="please select date "
                aria-required="true" readonly>
        </div>

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
                        {{__('labels.daily_update_list_title')}}
                    </h2>
                    <!-- BEGIN: Show Modal Toggle -->
                    {{-- <a href="{{ route('schedules.create') }}" class="ms-auto d-flex align-items-center btn btn-primary"
                        id="addForm">
                        <i class="feather-plus-circle btn-add me-2"> </i>{{__('labels.add_new')}}</a> --}}
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
                                <th>{{__('labels.create_at')}}</th>
                                <th>{{__('labels.created_by')}}</th>
                                <th>{{__('labels.dealer_name')}}</th>
                                <th>{{__('labels.user_type')}}</th>
                                <th>{{__('labels.s_type')}}</th>
                                <th>Report Date</th>
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
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script>
    var table;
    $(function() {


        $('#stateId').on('change', function() {
        var s_name = this.value;

            $("#cityId").html('');
            $("#talukaId").html('');

            var ajaxUrl = '{{ route('get-city') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                s_name: s_name,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    console.log(result);
                    $("#cityId").attr("disabled", false);
                    $('#cityId').html('<option value="">Select City</option>');
                    $('#talukaId').html('<option value="">Select Taluka</option>');
                    $.each(result.city,function(key,value){
                    $("#cityId").append('<option value="'+value.id+'">'+value.cName+'</option>');
                    });
                }
            });
        });


        $('#cityId').on('change', function() {

            var cityId = this.value;

            $("#talukaId").html('');
            var ajaxUrl = '{{ route('get-taluka') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                 cityId: cityId,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    $("#talukaId").attr("disabled", false);
                    $('#talukaId').html('<option value="">Select Taluka</option>');
                    $.each(result.taluka,function(key,value){
                    $("#talukaId").append('<option value="'+value.id+'">'+value.tName+'</option>');
                    });
                }
            });
        });


        // $('#stateId').on('change', function() {
        // var s_name = this.value;

        //     $("#cityId").html('');
        //     $("#talukaId").html('');

        //     var ajaxUrl = '{{ route('dailyUpdates.index') }}';
        //     $.ajax({
        //         url: ajaxUrl,
        //         type: "GET",
        //         data: {
        //         s_name: s_name,
        //         _token: '{{csrf_token()}}'
        //         },
        //         dataType : 'json',
        //         success: function(result){


        //             $("#cityId").attr("disabled", false);
        //             $('#cityId').html('<option value="">Select City</option>');
        //             $('#talukaId').html('<option value="">Select Taluka</option>');
        //             $.each(result.city,function(key,value){
        //             $("#cityId").append('<option value="'+value.dCityId+'">'+value.dCName+'</option>');
        //             });
        //         }
        //     });
        // });


        // $('#cityId').on('change', function() {

        //     var cityId = this.value;

        //     $("#talukaId").html('');
        //     var ajaxUrl = '{{ route('dailyUpdates.index') }}';
        //     $.ajax({
        //         url: ajaxUrl,
        //         type: "GET",
        //         data: {
        //          cityId: cityId,
        //         _token: '{{csrf_token()}}'
        //         },
        //         dataType : 'json',
        //         success: function(result){
        //             $("#talukaId").attr("disabled", false);
        //             $('#talukaId').html('<option value="">Select Taluka</option>');
        //             $.each(result.taluka,function(key,value){
        //             $("#talukaId").append('<option value="'+value.dTalukaId+'">'+value.dTName+'</option>');
        //             });
        //         }
        //     });
        // });


        $(".datepickerdate").datepicker({
            shortYearCutoff: 1,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
        });

        table = $('#schedules').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('dailyUpdates.search')) }}',
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
                    d.scheduleDate = $('#scheduleDate').val();
                    d.cityId = $('#cityId').val();
                    d.talukaId = $('#talukaId').val();
                    d.stateId = $('#stateId').val();
                    d.otherBrand = $('#otherBrand').val();
                }

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
                    data: 'dvrFormate',
                    name: 'dvrFormate',
                    visible: true,
                },
                // {
                //     data: '{{__("labels.is_active_db")}}',
                //     name: '{{__("labels.is_active_db")}}',
                //     orderable: false
                // },

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

    $(document).on('change', '.userType, .employeeType, .dealerType, .scheduleType,.scheduleDate,.select2Class', function() {
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
