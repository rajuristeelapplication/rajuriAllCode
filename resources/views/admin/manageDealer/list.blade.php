@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
    .badge {
        cursor: context-menu !important;
    }

    .ui-datepicker-year
    {
    display:none;
    }
</style>
@endsection
@section('title') {{ __('labels.dealer_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('dealers.index') }}"><span class="breadcrumb--active">
                {{__('labels.manage_dealer')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')


    <div class="row mt-3">

        @if($type == 2)
        <div class="form-group col-md-4">
            <label for="formType"><span class="fa fa-filter mb-2"></span> {{ __('labels.form_type') }}</label>
            <select id="formType" name="formType" class="form-select form-select mt-2 formType select2Class">
                <option value="0">{{ __('labels.all_form') }}</option>
                {{-- <option value="{{__('labels.dealer_filter')}}">{{ __('labels.dealer_filter') }}</option> --}}
                <option value="{{__('labels.engineer_filter')}}">{{ __('labels.engineer_filter') }}</option>
                <option value="{{__('labels.architect_filter')}}">{{ __('labels.architect_filter') }}</option>
                <option value="{{__('labels.mason_filter')}}">{{ __('labels.mason_filter') }}</option>
                <option value="{{__('labels.contractor_filter')}}">{{ __('labels.contractor_filter') }}</option>
                <option value="{{__('labels.construction_filter')}}">{{ __('labels.construction_filter') }}</option>
            </select>
        </div>
        @endif

        @if($type == 1)
        <div class="form-group col-md-4">
            <label for="dealerType"><span class="fa fa-filter mb-2"></span>Dealer Actor Type</label>
            <select id="dealerType" name="dealerType" class="form-select form-select mt-2 dealerType select2Class">
                <option value="0">All Dealer Actor Type</option>
                <option value="{{__('labels.main_dealer')}}">{{ __('labels.main_dealer') }}</option>
                <option value="{{__('labels.sub_dealer')}}">{{ __('labels.sub_dealer') }}</option>
            </select>
        </div>
        @endif

        <div class="form-group col-md-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.executive') }} </label>
            <select id="employeeType" name="employeeType"
                class="form-select form-select mt-2 employeeType select2Class">
                <option value="0" selected>{{ __('labels.all_executive') }} </option>

                @if(count($userNameList) > 0)
                @foreach($userNameList as $name)
                <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-md-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.dealer_name') }}</label>
            <select id="dealerNameType" name="dealerNameType"
                class="form-select form-select mt-2 dealerNameType select2Class">
                <option value="" selected>{{ __('labels.select_dealer_name') }}</option>

                @if(count($dealerNameList) > 0)

                @foreach($dealerNameList as $name)
                <option value="{{ $name->id }}">{{ $name->name }}</option>
                @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.state') }} </label>
            <select id="stateId" name="stateId"
                class="form-select form-select mt-2 stateId select2Class">
                <option value="" selected>{{ __('labels.state_select_valid') }}</option>

                {{-- @if(count($stateNameLists) > 0)
                @foreach($stateNameLists as $name)
                <option value="{{ $name->stateId }}">{{ $name->sName }}</option>
                @endforeach
                @endif --}}

                <?php
                        // echo "<pre>";
                        //     print_r($allStates->toArray());
                        //     exit;
                ?>

                @if(count($allStates) > 0)
                @foreach($allStates as $name)
                     <option value="{{ $name->id }}">{{ $name->sName }}</option>
                @endforeach
                @endif

            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.district_name') }}  </label>
            <select id="cityId" name="cityId"
                class="form-select form-select mt-2 cityId select2Class">
                <option value="" selected>{{ __('labels.select_district_name') }} </option>

                {{-- @if(count($districtNameLists) > 0)

                @foreach($districtNameLists as $name)
                <option value="{{ $name->cityId }}">{{ $name->cName }}</option>
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
                <option value="{{ $name->talukaId }}">{{ $name->tName }}</option>
                @endforeach

                @endif --}}
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Select Status </label>
            <select id="dealerStatus" name="dealerStatus"
                class="form-select form-select mt-2 talukaId select2Class">
                <option value="" selected>Select Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="InActive">In Active</option>

            </select>
        </div>


        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Start DOB Filter </label>

            <input type="text" name="dobStart" id="dob"
                class="form-control input-valid w-100 mt-2 mb-2 datepickerdate dobStart"  placeholder="please select date "
                aria-required="true" readonly>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> End DOB Filter </label>

            <input type="text" name="dobEnd" id="dob1"
                class="form-control input-valid w-100 mt-2 mb-2 datepickerdate dobEnd"  placeholder="please select date "
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
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.dealer_list_title') }}
                    </h2>

                    <a href="{{ route('dealers.create',['type' => $type]) }}"
                        class="ms-auto d-flex align-items-center btn btn-primary" id="addForm"><i
                            class="feather-plus-circle me-2"> </i> {{ __('labels.add_new') }}</a>

                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="dealers"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{__('labels.created_by')}}</th>
                                <th>{{ __('labels.form_type') }}</th>
                                <th title="Dealer/Engineer/Architect/Contractor/Construction Firm">{{ __('labels.dealer_name')}}</th>
                                <th>{{ __('labels.email') }}</th>
                                <th>Whatsapp Mobile Number</th>
                                <th>Actor Type</th>
                                <th>{{__('labels.create_at')}}</th>
                                <th>{{__('labels.status')}}</th>
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

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script>


// // $('#daterange').val('');

//      // var date = new Date();
//     // date.setMonth(date.getMonth() - 12);

//     $('#daterange').daterangepicker({
//         // 'minDate': date,
//         // 'maxDate': new Date(),
//         'drops': "down",
//         // 'dateLimit': {
//         //     days: 365
//         // },
//         showDropdowns: true,

//         locale: {
//             // format: 'DD/MM/YYYY'
//             format: 'YYYY-MM-DD'
//         }
//     });



    // var dobRange = "";
    // $('#daterange').val('');

    // $(document).on('click', '.applyBtn', function() {
    //         dobRange  = $('#daterange').val();
    //     });

    // $(document).on('click', '.cancelBtn', function() {
    //      $('#daterange').val('');
    //     });


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

        // var ajaxUrl = '{{ route('dealers.index',['type' => $type]) }}';

        //     $('#stateId').on('change', function() {
        //         var s_name = this.value;

        //             $("#cityId").html('');
        //             $("#talukaId").html('');


        //             $.ajax({
        //                 url: ajaxUrl,
        //                 type: "GET",
        //                 data: {
        //                 s_name: s_name,
        //                 _token: '{{csrf_token()}}'
        //                 },
        //                 dataType : 'json',
        //                 success: function(result){

        //                     $("#cityId").attr("disabled", false);
        //                     $('#cityId').html('<option value="">Select City</option>');
        //                     $('#talukaId').html('<option value="">Select Taluka</option>');
        //                     $.each(result.city,function(key,value){
        //                     $("#cityId").append('<option value="'+value.cityId+'">'+value.cName+'</option>');
        //                     });
        //                 }
        //             });
        //         });

        //     $('#cityId').on('change', function() {

        //         var cityId = this.value;

        //         $("#talukaId").html('');

        //         $.ajax({
        //             url: ajaxUrl,
        //             type: "GET",
        //             data: {
        //             cityId: cityId,
        //             _token: '{{csrf_token()}}'
        //             },
        //             dataType : 'json',
        //             success: function(result){
        //                 $("#talukaId").attr("disabled", false);
        //                 $('#talukaId').html('<option value="">Select Taluka</option>');
        //                 $.each(result.taluka,function(key,value){
        //                 $("#talukaId").append('<option value="'+value.talukaId+'">'+value.tName+'</option>');
        //                 });
        //             }
        //         });
        //     });


        // $( ".datepickerdate" ).datepicker({ dateFormat: 'yy-mm-dd' });

        $(".datepickerdate").datepicker({

        shortYearCutoff: 1,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'mm-dd',
        // minDate: "-70Y",
        // maxDate: "-16Y",
        // yearRange: "1942:2010"

        // dateFormat: 'yy-mm-dd',
        // changeMonth: true,
        // changeYear: true,
        // constrainInput: false,
        // minDate: "-70Y",
    });

        table = $('#dealers').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('dealers.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.formType = $('#formType').val();
                    d.dealerType = $('#dealerType').val();
                    d.employeeType = $('#employeeType').val();
                    d.dealerNameType = $('#dealerNameType').val();
                    d.cityId = $('#cityId').val();
                    d.talukaId = $('#talukaId').val();
                    d.stateId = $('#stateId').val();
                    d.dob = $('#dob').val();
                    d.dob1 = $('#dob1').val();
                    d.dealerStatus =  $('#dealerStatus').val();;
                    d.type="{{$type}}"
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
                    data: '{{__('labels.form_type_db')}}',
                    name: '{{__('labels.form_type_db')}}'
                },

                {
                    data: '{{__('labels.name_db')}}',
                    name: '{{__('labels.name_db')}}',
                },


                {
                    data: '{{__('labels.email_db')}}',
                    name: '{{__('labels.email_db')}}'
                },

                {
                    data: 'wpMobileNumber',
                    name: 'wpMobileNumber',
                },
                {
                    data: '{{__('labels.dealer_type_db')}}',
                    name: '{{__('labels.dealer_type_db')}}',
                    visible:"{{  $type == 2 ? false : true }}"
                },

                {
                    data: 'createdAt',
                    name: 'createdAt',
                    "visible": false,
                },
                {
                    data: 'statusDealers',
                    name: 'statusDealers',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "aaSorting": [
                [7, 'desc']
            ],
            "pageLength": 50
        });

        // filter after reload table

    });
        $(document).on('change', '.select2Class,.dob,.dealerStatus,.dobStart,.dobEnd', function() {
            table.ajax.reload();
        });

    $("#dealers").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var formType = $(this).attr('data-formType');

        $('#statusTitle').text('Do you really want to ' + status + ' this dealer?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


    $(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

        $("#requestStatus").val(statusValue);

            // if(statusValue == "Pending"){
            $('#userStatusChange-modal').modal('show');
            // }



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
    $(".select2Class").select2({});
});
</script>
@endsection
