@extends('admin.layout.index')
@section('css')
<style>
    .badge {
        cursor: context-menu !important;
    }
</style>
@endsection
@section('title') Merchandise  {{ ucfirst($type) }} List  @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('merchandise.index',['type' => $type]) }}"><span class="breadcrumb--active"> Merchandise  {{ ucfirst($type) }} List </span></a>
    </div>
    @endsection
    @include('admin.common.notification')

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
            <label for="name"><span class="fa fa-filter mb-2"></span> Actor Name </label>
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
            <label for="mStatus"><span class="fa fa-filter mb-2"></span>Order Status</label>
            <select id="mStatus" name="mStatus"
                class="form-select form-select mt-2 mStatus select2Class">
                <option value="0" selected>All Status</option>

                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Delivered">Delivered</option>
                <option value="Rejected">Rejected</option>

            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
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
                <option value="{{ $name->mStateId }}">{{ $name->mSName }}</option>
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
                <option value="{{ $name->mCityId }}">{{ $name->mCName }}</option>
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
                <option value="{{ $name->mTalukaId }}">{{ $name->mTName }}</option>
                @endforeach

                @endif --}}
            </select>
        </div>



    </div>



    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0"> Merchandise  {{ ucfirst($type) }} List
                    </h2>
                    <a href="{{ route('merchandise.create',['type' => $type]) }}"
                        class="ms-auto d-flex align-items-center btn btn-primary" id="addForm"><i
                            class="feather-plus-circle btn-add me-2"> </i> {{ __('labels.add_new') }}
                    </a>

                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="merchandise"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{ __('labels.create_at') }}</th>
                                <th>{{ __('labels.created_by') }}</th>
                                <th>{{ __('labels.merchandise_type') }}</th>
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
@include('admin.merchandise.modal')

@endsection

@section('js')
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    $(".select2Class").select2({});

    var table;
    $(function() {
        table = $('#merchandise').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('merchandise.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.mType = "{{ $type }}";
                    d.employeeType = $('#employeeType').val();
                    d.dealerType = $('#dealerType').val();
                    d.userType = $('#userType').val();
                    d.mStatus = $('#mStatus').val();
                    d.cityId = $('#cityId').val();
                    d.talukaId = $('#talukaId').val();
                    d.stateId = $('#stateId').val();


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
                    data: "mType",
                    name: "mType",
                },
                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "dealerId",
                    name: "dealerId",
                },
                {
                    data: "mDateFormate",
                    name: "mDateFormate",
                },
                {
                    data: 'mStatus',
                    name: 'mStatus',
                    orderable: false,
                    "visible": "{{ $type == "gift" ? false : true }}",
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


        var ajaxUrl = '{{ route('merchandise.index',['type' => $type]) }}';


    $('#stateId').on('change', function() {
        var s_name = this.value;

            $("#cityId").html('');
            $("#talukaId").html('');


            $.ajax({
                url: ajaxUrl,
                type: "GET",
                data: {
                s_name: s_name,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){

                    $("#cityId").attr("disabled", false);
                    $('#cityId').html('<option value="">Select City</option>');
                    $('#talukaId').html('<option value="">Select Taluka</option>');
                    $.each(result.city,function(key,value){
                    $("#cityId").append('<option value="'+value.mCityId+'">'+value.mCName+'</option>');
                    });
                }
            });
        });

    $('#cityId').on('change', function() {

        var cityId = this.value;

        $("#talukaId").html('');

        $.ajax({
            url: ajaxUrl,
            type: "GET",
            data: {
            cityId: cityId,
            _token: '{{csrf_token()}}'
            },
            dataType : 'json',
            success: function(result){
                $("#talukaId").attr("disabled", false);
                $('#talukaId').html('<option value="">Select Taluka</option>');
                $.each(result.taluka,function(key,value){
                $("#talukaId").append('<option value="'+value.mTalukaId+'">'+value.mTName+'</option>');
                });
            }
        });
    });


    });

    // $(document).on('change', '.mType, .employeeType, .dealerType', function() {
        $(document).on('change', 'select', function() {
            table.ajax.reload();
        });

    $("#merchandise").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this merchandise?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


    $(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending" || statusValue == "Approved"){
            $('#userStatusChange-modal').modal('show');
            }

            //Disabled Value When Status Is Approved
            if (statusValue == "Approved") {
                $("#userStatusChange-modal option[value='Pending']").attr("disabled","disabled");
                $("#userStatusChange-modal option[value='Rejected']").attr("disabled","disabled");
            }

            var url = $(this).data('url');
            form = $('#updateStatus');
            form.attr('action', url);

            $('#dataId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');

            $("#requestStatus").val($(this).attr('data-st')); //Dropdown Value Selected
    });

    // $(document).on('change', '.requestStatus', function() {
    //     if($(this).val() == 'Approved')
    //     {
    //         $('.vehicleNumber').show(1000);
    //     }
    //     else
    //     {
    //         $('.vehicleNumber').hide(1000);
    //     }
    // });


    $(document).on('click', '.resetForm', function() {

        $("#updateStatus").trigger('reset');
        // $('.vehicleNumber').hide(1000);
    });


    // $(function() {

    //     // This Function is used for validation
    //     $(".updateStatus").validate({
    //             ignore: ":hidden:not(.my_item)",
    //             rules: {
    //                 vehicleNumber:{
    //                     required:true,
    //                 },
    //             },
    //             messages: {
    //                 vehicleNumber:{
    //                     required:'Please enter vehicle number',
    //                 },
    //             },
    //             submitHandler: function(form) {
    //             var requestStatus =  $('#requestStatus').find(":selected").text();

    //             if(requestStatus == 'Approved')
    //             {
    //                 var val = checkDataIsCorrect();
    //                 if(val)
    //                 {
    //                     form.submit();
    //                 }
    //                 return false;
    //             }
    //             else
    //             {
    //                 form.submit();
    //             }
    //             },
    //             invalidHandler: function(form, validator) {
    //             }
    //         });
    // });
</script>
@endsection
