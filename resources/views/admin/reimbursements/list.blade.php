@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
    .badge {
        cursor: context-menu !important;
    }
</style>
@endsection
@section('title') {{ __('labels.reimbursements_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('reimbursements.index') }}"><span class="breadcrumb--active"> {{__('labels.manage_reimbursements')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')


    <div class="row mt-3">
        <div class="form-group col-md-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.expenseType') }}</label>
            <select id="expense_type" name="expense_type" class="form-select form-select mt-2 expense_type select2Class">
                <option value="0">{{ __('labels.all_expense_type') }}</option>

               @if(count($getExpense) > 0)
                @foreach($getExpense as $name)
                <option value="{{ $name->id }}">{{ $name->eName }}</option>
                @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-md-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.status') }}</label>
            <select id="reimbursement_status" name="reimbursement_status" class="form-select form-select mt-2 select2Class reimbursement_status">
                <option value="0">{{ __('labels.all_status') }}</option>
                <option value="{{__('labels.pending')}}">{{ __('labels.pending') }}</option>
                <option value="{{__('labels.approved')}}">{{ __('labels.approved') }}</option>
                <option value="{{__('labels.rejected')}}">{{ __('labels.rejected') }}</option>
            </select>
        </div>

        @if(Auth::user()->roleId == config('constant.ma_id'))
        <div class="form-group col-md-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.users_type') }}</label>
            <select id="userType" name="userType" class="form-select form-select mt-2 select2Class userType">
                <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                </option>
            </select>
        </div>
        @else
        <div class="form-group col-md-4">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> {{ __('labels.users_type') }}</label>
            <select id="userType" name="userType" class="form-select form-select mt-2 select2Class reimbursement_status">
                <option value="">{{ __('labels.all_user_type') }}</option>
                <option value="{{config('constant.sales_executive_id')}}">{{ __('labels.sales_executive') }}</option>
                <option value="{{config('constant.marketing_executive_id')}}">{{ __('labels.marketing_executive') }}
                </option>
            </select>
        </div>
        @endif

        <br>

        @if(count($userNameList) > 0)

        <div class="form-group col-md-4 mt-2">
            <label for="daterange"><span class="fa fa-filter mb-2"></span> Executive Name </label>
            <select id="userId" name="userId" class="form-select form-select mt-2 select2Class reimbursement_status">
                <option value="">All Executive Name</option>
                @if(count($userNameList) > 0)
                @foreach($userNameList as $name)
                <option value="{{ $name->id }}">{{ $name->fullName }}</option>
                @endforeach
                @endif
            </select>
        </div>

        @endif


        <div class="form-group col-md-4 mt-2">
            <label for="name"><span class="fa fa-filter mb-2"></span> Start Date Filter </label>


            <input type="text" name="dobStart" id="dob"
                class="form-control input-valid w-100 mt-2 mb-2 datepickerdate dobStart"  placeholder="please select date "
                aria-required="true" readonly>
        </div>

        <div class="form-group col-md-4 mt-2">
            <label for="name"><span class="fa fa-filter mb-2"></span> End Date Filter </label>

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
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.reimbursements_list_title') }}
                    </h2>

                    {{-- @if($expense_type == '')
                    <a href="{{ route('users.create',['expense_type' => $expense_type]) }}"
                        class="ms-auto d-flex align-items-center btn btn-primary" id="addForm"><i
                            class="feather-plus-circle btn-add me-2"> </i> {{ __('labels.add_new') }}</a>
                    @endif --}}
                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="table-responsive">
                    <table id="reimbursements"
                        class="last-cl-fxied display nowrap table table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{ __('labels.create_at') }}</th>
                                <th>{{ __('labels.created_by') }}</th>
                                <th>{{ __('labels.expenseType') }}</th>
                                <th>{{ __('labels.rName') }}</th>
                                <th>{{ __('labels.dateOfTravelling') }}</th>
                                <th>Description</th>
                                <th>Total Amount (₹)</th>
                                <th>{{ __('labels.create_date') }}</th>
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
@include('admin.reimbursements.modal')

@endsection

@section('js')

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script>
    var table;
    $(function() {

        $(".datepickerdate").datepicker({
        shortYearCutoff: 1,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        // minDate: "-70Y",
        // maxDate: "-16Y",
        // yearRange: "1942:2010"

        // dateFormat: 'yy-mm-dd',
        // changeMonth: true,
        // changeYear: true,
        // constrainInput: false,
        // minDate: "-70Y",
        });

        table = $('#reimbursements').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('reimbursements.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.expense_type = $('#expense_type').val();
                    d.reimbursement_status = $('#reimbursement_status').val();
                    d.userType = $('#userType').val();
                    d.userId = $('#userId').val();
                    d.startDate = $('#dob').val();
                    d.endDate = $('#dob1').val();
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
                    data: 'fullName',
                    name: 'fullName',
                },
                {
                    data: 'expenseId',
                    name: 'expenseId'
                },
                {
                    data: 'rName',
                    name: 'rName'
                },
                {
                    data: 'dateOfTravelling',
                    name: 'dateOfTravelling'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'totalAmount',
                    name: 'totalAmount'
                },
                {
                    data: 'dateOfCreateAtFormate',
                    name: 'dateOfCreateAtFormate',
                    "visible": true,
                },

                {
                    data: 'rStatus',
                    name: 'rStatus',
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

    $(document).on('change', '.expense_type, .reimbursement_status,.datepickerdate', function() {
            table.ajax.reload();
        });

    $("#reimbursements").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this leave?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


    $(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending"){
            $('#leaveStatusChange-modal').modal('show');
            }

            var url = $(this).data('url');
            form = $('#updateLeaveStatus');
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
