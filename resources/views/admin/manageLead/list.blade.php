@extends('admin.layout.index')
@section('css')
<style>
    .badge {
        cursor: context-menu !important;
    }
</style>
@endsection
@section('title') {{ __('labels.lead_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('leads.index') }}"><span class="breadcrumb--active">
                {{__('labels.manage_lead')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')


    <div class="row mt-3">
        <div class="form-group col-md-4 mt-4">
            <label for="leadType"><span class="fa fa-filter mb-2"></span> {{ __('labels.lead_type') }}</label>
            <select id="leadType" name="leadType" class="form-select form-select mt-2 leadType select2Class">
                <option value="0">{{ __('labels.all_lead') }}</option>
                <option value="{{__('labels.material_lead')}}">{{ __('labels.material_lead') }}</option>
                <option value="{{__('labels.dealership_lead')}}">{{ __('labels.dealership_lead') }}</option>
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

            {{-- <div class="form-group col-md-4 ">
                <label for="daterange"><span class="fa fa-filter mb-2"></span> Type </label>
                <select id="lType" name="lType" class="form-select form-select mt-2 select2Class lType">
                    <option value="">All Type</option>
                    <option value="Material Lead">Material Lead</option>
                    <option value="Dealership Lead">Dealership Lead</option>
                </select>
            </div> --}}

            <div class="form-group col-md-4">
                <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.state') }} </label>
                <select id="stateId" name="stateId"
                    class="form-select form-select mt-2 stateId select2Class">
                    <option value="" selected>{{ __('labels.state_select_valid') }}</option>

                    @if(count($allStates) > 0)
                    @foreach($allStates as $name)
                         <option value="{{ $name->id }}">{{ $name->sName }}</option>
                    @endforeach
                    @endif

                    {{-- @if(count($stateNameLists) > 0)

                    @foreach($stateNameLists as $name)
                    <option value="{{ $name->pStateId }}">{{ $name->pSName }}</option>
                    @endforeach

                    @endif --}}
                </select>
            </div>

            <div class="form-group col-md-4 mt-4">
                <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.district_name') }}  </label>
                <select id="cityId" name="cityId"
                    class="form-select form-select mt-2 cityId select2Class">
                    <option value="" selected>{{ __('labels.select_district_name') }} </option>

                    {{-- @if(count($districtNameLists) > 0)
                    @foreach($districtNameLists as $name)
                    <option value="{{ $name->pCityId }}">{{ $name->pCName }}</option>
                    @endforeach
                    @endif --}}
                </select>
            </div>

            <div class="form-group col-md-4 mt-4">
                <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.taluka_name') }}  </label>
                <select id="talukaId" name="talukaId"
                    class="form-select form-select mt-2 talukaId select2Class">
                    <option value="" selected>{{ __('labels.select_taluka_name') }}</option>

                    {{-- @if(count($talukaNameLists) > 0)

                    @foreach($talukaNameLists as $name)
                    <option value="{{ $name->pTalukaId }}">{{ $name->pTName }}</option>
                    @endforeach

                    @endif --}}
                </select>
            </div>
            <div class="form-group col-md-4 mt-4">
                <label for="daterange"><span class="fa fa-filter mb-2"></span> Status </label>
                <select id="moveStatus" name="moveStatus" class="form-select form-select mt-2 select2Class">
                    <option value="">All Status</option>
                    <option value="Pending">In Progress</option>
                    <option value="Converted">Converted</option>
                </select>
            </div>

    </div>


    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.lead_list_title') }}
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
                    <table id="leads"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{__('labels.created_by')}}</th>
                                <th >{{ __('labels.name')}}</th>
                                <th>{{ __('labels.lead_type') }}</th>
                                <th>{{ __('labels.company_name') }}</th>
                                {{-- <th>{{ __('labels.shop_name') }}</th> --}}
                                <th>{{ __('labels.mobile_no') }}</th>
                                {{-- <th>{{ __('labels.isActive') }}</th> --}}
                                <th>{{__('labels.create_at')}}</th>

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


//         var ajaxUrl = '{{ route('leads.index') }}';

// $('#stateId').on('change', function() {
//     var s_name = this.value;

//         $("#cityId").html('');
//         $("#talukaId").html('');


//         $.ajax({
//             url: ajaxUrl,
//             type: "GET",
//             data: {
//             s_name: s_name,
//             _token: '{{csrf_token()}}'
//             },
//             dataType : 'json',
//             success: function(result){

//                 $("#cityId").attr("disabled", false);
//                 $('#cityId').html('<option value="">Select City</option>');
//                 $('#talukaId').html('<option value="">Select Taluka</option>');
//                 $.each(result.city,function(key,value){
//                 $("#cityId").append('<option value="'+value.pCityId+'">'+value.pCName+'</option>');
//                 });
//             }
//         });
//     });

// $('#cityId').on('change', function() {

//     var cityId = this.value;

//     $("#talukaId").html('');

//     $.ajax({
//         url: ajaxUrl,
//         type: "GET",
//         data: {
//         cityId: cityId,
//         _token: '{{csrf_token()}}'
//         },
//         dataType : 'json',
//         success: function(result){
//             $("#talukaId").attr("disabled", false);
//             $('#talukaId').html('<option value="">Select Taluka</option>');
//             $.each(result.taluka,function(key,value){
//             $("#talukaId").append('<option value="'+value.pTalukaId+'">'+value.pTName+'</option>');
//             });
//         }
//     });
// });

        table = $('#leads').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('leads.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.leadType = $('#leadType').val();
                    d.employeeType = $('#employeeType').val();
                    d.cityId = $('#cityId').val();
                    d.talukaId = $('#talukaId').val();
                    d.stateId = $('#stateId').val();
                    d.moveStatus = $('#moveStatus').val();

                }
            },
            aaSorting: true,
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: '{{__('labels.created_by_db')}}',
                    name: '{{__('labels.created_by_db')}}',
                },
                {
                    data: '{{__('labels.lFullName_db')}}',
                    name: '{{__('labels.lFullName_db')}}',
                },
                {
                    data: '{{__('labels.lType_db')}}',
                    name: '{{__('labels.lType_db')}}'
                },
                {
                    data: '{{__('labels.lCompanyName_db')}}',
                    name: '{{__('labels.lCompanyName_db')}}'
                },
                // {
                //     data: '{{__('labels.lShopName_db')}}',
                //     name: '{{__('labels.lShopName_db')}}'
                // },

                {
                    data: '{{__('labels.lMobileNumber_db')}}',
                    name: '{{__('labels.lMobileNumber_db')}}',
                },
                // {
                //     data: '{{__('labels.is_active_db')}}',
                //     name: '{{__('labels.is_active_db')}}',
                //     orderable: false
                // },
                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": false,
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

    $(document).on('change', '.leadType, .employeeType,.select2Class', function() {
            table.ajax.reload();
        });

    $("#leads").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var leadType = $(this).attr('data-leadType');

        $('#statusTitle').text('Do you really want to ' + status + ' this lead?');
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
