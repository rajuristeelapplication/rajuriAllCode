@extends('admin.layout.index')
@section('css')
<style>
    .badge {
        cursor: context-menu !important;
    }
</style>
@endsection
@section('title') {{ __('labels.knowledge_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('knowledge.index') }}"><span class="breadcrumb--active">
                {{__('labels.knowledge_center')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')

    <div class="row mt-3">
        <div class="form-group col-md-4">
            <label for="complaintType"><span class="fa fa-filter mb-2"></span> Select Status</label>
            <select id="selectStatus" name="selectStatus" class="form-select form-select mt-2 selectStatus">
                <option value="">Select Status</option>
                <option value="Pending">Pending</option>
                <option value="Not Available">Not Available</option>
                <option value="Approved">Approved</option>
            </select>
        </div>

        <div class="form-group col-md-4">
            <label for="userType"><span class="fa fa-filter mb-2"></span> Actor Type </label>
            <select id="userType" name="userType" class="form-select form-select mt-2 userType select2Class selectStatus">
                <option value="0">All Actor Type</option>
                <option value="{{__('labels.dealer_filter')}}">{{ __('labels.dealer_filter') }}</option>
                <option value="{{__('labels.engineer_filter')}}">{{ __('labels.engineer_filter') }}</option>
                <option value="{{__('labels.architect_filter')}}">{{ __('labels.architect_filter') }}</option>
                <option value="{{__('labels.mason_filter')}}">{{ __('labels.mason_filter') }}</option>
                <option value="{{__('labels.contractor_filter')}}">{{ __('labels.contractor_filter') }}</option>
                <option value="{{__('labels.construction_filter')}}">{{ __('labels.construction_filter') }}</option>
            </select>
        </div>

        <div class="form-group col-md-4 ">
            <label for="name"><span class="fa fa-filter mb-2"></span> Actor Name</label>
            <select id="dealerType" name="dealerType"
                class="form-select form-select mt-2 dealerType select2Class selectStatus">
                <option value="0" selected>All Actor Name</option>

                @if(count($dealerNameList) > 0)

                @foreach($dealerNameList as $name)
                <option value="{{ $name->id }}">{{ $name->name }}</option>
                @endforeach

                @endif
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Executive</label>
            <select id="employeeType" name="employeeType"
                class="form-select form-select mt-2 employeeType select2Class selectStatus">
                <option value="0" selected>All Executive</option>

                @if(count($userNameList) > 0)

                @foreach($userNameList as $name)
                <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                @endforeach

                @endif
            </select>
        </div>



        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.state') }} </label>
            <select id="stateId" name="stateId"
                class="form-select form-select mt-2 stateId select2Class selectStatus">
                <option value="" selected>{{ __('labels.state_select_valid') }}</option>

                @if(count($allStates) > 0)
                @foreach($allStates as $name)
                     <option value="{{ $name->id }}">{{ $name->sName }}</option>
                @endforeach
                @endif

                {{-- @if(count($stateNameLists) > 0)

                @foreach($stateNameLists as $name)
                <option value="{{ $name->ksStateId }}">{{ $name->ksSName }}</option>
                @endforeach

                @endif --}}
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.district_name') }}  </label>
            <select id="cityId" name="cityId"
                class="form-select form-select mt-2 cityId select2Class selectStatus">
                <option value="" selected>{{ __('labels.select_district_name') }} </option>

                {{-- @if(count($districtNameLists) > 0)
                @foreach($districtNameLists as $name)
                <option value="{{ $name->ksCityId }}">{{ $name->ksCName }}</option>
                @endforeach
                @endif --}}
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.taluka_name') }}  </label>
            <select id="talukaId" name="talukaId"
                class="form-select form-select mt-2 talukaId select2Class selectStatus">
                <option value="" selected>{{ __('labels.select_taluka_name') }}</option>

                {{-- @if(count($talukaNameLists) > 0)

                @foreach($talukaNameLists as $name)
                <option value="{{ $name->ksTalukaId }}">{{ $name->ksTName }}</option>
                @endforeach

                @endif --}}
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> Vehicle </label>
            <select id="vehicleNumber1" name="vehicleNumber1"
                        class="form-select form-select mt-2 selectStatus vehicleNumber1">
                        <option value="">Please select vehicle number</option>
                        @php
                        $vehicleArray = config('constant.vehicleArray');
                        @endphp
                            @foreach ($vehicleArray as $key => $vehicle)
                            <option value="{{ $vehicle }}"> {{ $vehicle }}</option>
                            @endforeach
                     </select>
        </div>

    </div>

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.knowledge_list_title') }}
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
                    <table id="knowledge"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{ __('labels.create_at') }}</th>
                                <th>{{ __('labels.created_by') }}</th>
                                <th>Actor Type</th>
                                <th>{{ __('labels.dealer_name') }}</th>
                                <th>{{ __('labels.id') }}</th>
                                <th>{{ __('labels.date_time') }}</th>
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
@include('admin.knowledge.modal')

@endsection

@section('js')
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script>
    $('.vehicleNumber').hide(1000);

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

        //     var ajaxUrl = '{{ route('knowledge.index') }}';
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
        //             $("#cityId").append('<option value="'+value.ksCityId+'">'+value.ksCName+'</option>');
        //             });
        //         }
        //     });
        // });


        // $('#cityId').on('change', function() {

        //     var cityId = this.value;

        //     $("#talukaId").html('');
        //     var ajaxUrl = '{{ route('knowledge.index') }}';
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
        //             $("#talukaId").append('<option value="'+value.ksTalukaId+'">'+value.ksTName+'</option>');
        //             });
        //         }
        //     });
        // });


        table = $('#knowledge').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('knowledge.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.selectStatus = $('#selectStatus').val();
                    d.userType = $('#userType').val();
                    d.employeeType = $('#employeeType').val();
                    d.dealerType = $('#dealerType').val();
                    d.cityId = $('#cityId').val();
                    d.talukaId = $('#talukaId').val();
                    d.stateId = $('#stateId').val();
                    d.vehicleNumber1 = $('#vehicleNumber1').val();

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
                    data: "fType",
                    name: "fType",
                },
                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "dealerId",
                    name: "dealerId",
                },
                // {
                //     data: "kCurrentLocation",
                //     name: "kCurrentLocation",
                // },
                {
                    data: "kdateFormate",
                    name: "kdateFormate",
                },
                // {
                //     data: "ktimeFormate",
                //     name: "ktimeFormate",
                // },

                // {
                //     data: 'isActive',
                //     name: 'isActive',
                //     orderable: false
                // },
                {
                    data: 'userStatus',
                    name: 'userStatus',
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

        // filter after reload table

    });

    $(document).on('change', '.selectStatus,.select2Class', function() {
            table.ajax.reload();
        });

    $("#knowledge").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this knowledge?');
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
                        required:'Please select vehicle number',
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $(".selectStatus").select2({
});
});
</script>


@endsection
