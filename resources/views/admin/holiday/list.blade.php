@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
@section('title') {{__('labels.holiday_list_title')}} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('holidays.index') }}"><span class="breadcrumb--active">{{__('labels.holidays')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="row mt-3">
        <div class="form-group col-md-3">
            <label for="{{__('labels.year')}}"><span class="fa fa-filter mb-2"></span> {{__('labels.year')}}</label>
            <select id="{{__('labels.yearType')}}" name="{{__('labels.yearType')}}"
                class="form-select form-select mt-2 {{__('labels.yearType')}} select2Class">
                <option value="0" selected>{{__('labels.all_year')}}</option>

                @if(count($getYear) > 0)
                @foreach($getYear as $name)
                <option value="{{ $name->year }}">{{ $name->year }}</option>
                @endforeach

                @endif
            </select>
        </div>

        {{-- Import & Download Holiday File --}}
        <div class="form-group col-md-7" style="width: 52.333333%;">
            <form style="border: 4px solid #ebb5c0;margin-top: 15px;padding: 10px;" action="{{ route('importExcel') }}" class="form-horizontal import-file" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" name="file"  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                <button class="btn btn-danger" type="submit" title="Click Here For Import File "><i class="fa fa-upload" aria-hidden="true" ></i> Import File</button>
                <a  href="{{ url('/sample_holiday_list.xlsx')  }}" class=" ms-auto align-items-center btn btn-success" download type="button" title="Click Here For Sample File Download"><i class="fa fa-download" aria-hidden="true"></i> Sample File</a>
            </form>
        </div>

    </div>

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{__('labels.holiday_list_title')}}
                    </h2>

                    <!-- BEGIN: Show Modal Toggle -->
                    <a href="{{ route('holidays.create') }}" class="ms-auto d-flex align-items-center btn btn-primary"
                        id="addForm">
                        <i class="feather-plus-circle"> </i>&nbsp;{{__('labels.add_new')}}</a>
                    <!-- END: Show Modal Toggle -->
                </div>
                <br><br>
                <div class="table-responsive">
                    <table id="holidays"
                        class="last-cl-fxied display nowrap table table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">{{__('labels.no')}}</th>
                                <th>{{__('labels.title')}}</th>
                                <th>{{__('labels.day')}}</th>
                                <th>{{__('labels.date')}}</th>
                                <th>{{__('labels.isActive')}}</th>
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
<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>
<script>
    var table;
    $(function() {
        table = $('#holidays').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('holidays.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function(d) {
                    d.yearType = $('#yearType').val();
                },

            },
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: '{{__("labels.title_db")}}',
                    name: '{{__("labels.title_db")}}',
                    visible: true,
                },
                {
                    data: '{{__("labels.hDateDay")}}',
                    name: '{{__("labels.hDateDay")}}',
                    visible: true,
                },
                {
                    data: '{{__("labels.hDate")}}',
                    name: '{{__("labels.hDate")}}',
                    visible: true,
                },
                {
                    data: 'isActive',
                    name: 'isActive',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "aaSorting": [
                [3, 'asc']
            ],
            "pageLength": 50
        });
    });

    jQuery(function($) {
    $(document).on('change', '.yearType', function() {
            console.log($('#yearType').val());
            table.ajax.reload();
    });
    });

    $("#holidays").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this holiday?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


</script>
@endsection
