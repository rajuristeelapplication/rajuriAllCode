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
@section('title') {{ __('labels.manage_complaints_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('complaints.index') }}"><span class="breadcrumb--active">
                {{__('labels.manage_complaints')}}</span></a>
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


        @if(count($dealerNameList) > 0)
        <div class="form-group col-md-4">
            <label for="name"><span class="fa fa-filter mb-2"></span>Actor Name</label>
            <select id="dealerType" name="dealerType"
                class="form-select form-select mt-2 dealerType select2Class">
                <option value="0" selected>All Actor Name</option>
                @foreach($dealerNameList as $name)
                <option value="{{ $name->id }}">{{ $name->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if(count($userNameList) > 0)
        <div class="form-group col-md-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> Executive</label>
            <select id="employeeType" name="employeeType"
                class="form-select form-select mt-2 employeeType select2Class">
                <option value="0" selected>All Executive</option>


                @foreach($userNameList as $name)
                <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                @endforeach


            </select>
        </div>
        @endif




        <div class="form-group col-md-4 mt-4">
            <label for="complaintType"><span class="fa fa-filter mb-2"></span> {{ __('labels.c_type') }}</label>
            <select id="complaintType" name="complaintType" class="form-select form-select mt-2 complaintType select2Class">
                <option value="">{{ __('labels.all_complaint') }}</option>
                <option value="{{__('labels.c_q_type')}}">{{ __('labels.c_q_type') }}</option>
                <option value="{{__('labels.c_g_type')}}">{{ __('labels.c_g_type') }}
                </option>
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="complaintType"><span class="fa fa-filter mb-2"></span> Select Status</label>
            <select id="selectStatus" name="selectStatus" class="form-select form-select mt-2 selectStatus select2Class">
                <option value="">Select Status</option>
                <option value="Pending">Pending</option>
                <option value="Solved">Solved</option>
            </select>
        </div>

        <div class="form-group col-md-4 mt-4">
            <label for="name"><span class="fa fa-filter mb-2"></span> {{ __('labels.state') }} </label>
            <select id="stateId" name="stateId"
                class="form-select form-select mt-2 stateId select2Class">
                <option value="" selected>{{ __('labels.state_select_valid') }}</option>
                {{-- @if(count($stateNameLists) > 0)
                    @foreach($stateNameLists as $name)
                    <option value="{{ $name->cStateId }}">{{ $name->cSName }}</option>
                    @endforeach
                @endif --}}

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
                <option value="{{ $name->cCityId }}">{{ $name->cCName }}</option>
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
                <option value="{{ $name->cTalukaId }}">{{ $name->cTName }}</option>
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
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.manage_complaints_list_title') }}
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
                                <th>{{ __('labels.c_type') }}</th>
                                <th>{{ __('labels.complaint_dealer_name') }}</th>
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
@include('admin.complaint.modal')

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

        //     var ajaxUrl = '{{ route('complaints.index') }}';
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
        //             $("#cityId").append('<option value="'+value.cCityId+'">'+value.cCName+'</option>');
        //             });
        //         }
        //     });
        // });


        // $('#cityId').on('change', function() {

        //     var cityId = this.value;

        //     $("#talukaId").html('');
        //     var ajaxUrl = '{{ route('complaints.index') }}';
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
        //             $("#talukaId").append('<option value="'+value.cTalukaId+'">'+value.cTName+'</option>');
        //             });
        //         }
        //     });
        // });
        table = $('#complaints').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('complaints.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.complaintType = $('#complaintType').val();
                    d.selectStatus = $('#selectStatus').val();
                    d.userType = $('#userType').val();
                    d.employeeType = $('#employeeType').val();
                    d.dealerType = $('#dealerType').val();
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
                    data: "cType",
                    name: "cType",
                },
                {
                    data: "name",
                    name: "name",
                },
                {
                    data: "cDateFormate",
                    name: "cDateFormate",
                },
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

    $(document).on('change', '.complaintType,.selectStatus,.select2Class', function() {
            table.ajax.reload();
        });

    $("#complaints").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var complaintType = $(this).attr('data-complaintType');

        $('#statusTitle').text('Do you really want to ' + status + ' this complaint?');
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
        if($(this).val() == 'Pending')
        {
            $('.remarkTextarea').addClass('hideClass');
        }
        else
        {
            $('.remarkTextarea').removeClass('hideClass');
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
                    cDesc:{
                        required:true,
                    },
                },
                messages: {
                    cDesc:{
                        required:'Please enter description',
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                },
                invalidHandler: function(form, validator) {
                }
            });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {

     $(".select2Class").select2({});

        $('.modal').on('shown.bs.modal', function (e) {
        $(this).find('.select2Class1').select2({
            dropdownParent: $(this).find('.modal-content')
        });
    })


    // $(".select2Class1").select2({});
});
</script>


@endsection
