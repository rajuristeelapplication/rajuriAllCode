@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
@section('title') {{__('labels.brochures_list_title')}} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('brochures.index') }}"><span class="breadcrumb--active">{{__('labels.brochures')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')
    @include('admin.common.flash')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{__('labels.brochures_list_title')}}
                    </h2>
                    <!-- BEGIN: Show Modal Toggle -->
                    {{-- <a href="javascript:;" class="ms-auto d-flex btn btn-primary" id="addForm"><i
                            class="feather-plus-circle btn-add me-2"> </i>{{__('labels.add_new')}}</a> --}}
                    <a href="{{ route('brochures.create') }}" class="ms-auto d-flex align-items-center btn btn-primary"
                        id="addForm">
                        <i class="feather-plus-circle btn-add me-2"> </i>{{__('labels.add_new')}}</a>
                    <!-- END: Show Modal Toggle -->
                </div>
                <br><br>
                <div class="table-responsive">
                    <table id="brochures"
                        class="last-cl-fxied display nowrap table table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">{{__('labels.no')}}</th>
                                <th>{{__('labels.brochure')}}</th>
                                <th>{{__('labels.image')}}</th>
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
        table = $('#brochures').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('brochures.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "async": false,
                "data": function() {

                },

            },
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    visible: true,
                },
                {
                    data: 'image',
                    name: 'image',
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
                [1, 'asc']
            ],
            "pageLength": 50
        });
    });


    $("#brochures").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var userType = $(this).attr('data-userType');

        $('#statusTitle').text('Do you really want to ' + status + ' this brochure?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });


    $('#addForm').click(function() {
        $('#formTitle').text('{{__('labels.add_brochure')}}');
        $("#brochure-modal").modal('show');
        $('#id').val("");
        $('.createBrochure')[0].reset();
    });

    $(".createBrochure").validate({
        rules: {
            {{__('labels.brochure_name')}}: {
                required: true,
            },
            {{__('labels.brochure_pdf')}}: {
                required: true,
            },
        },
        messages: {
            {{__('labels.brochure_name')}}: {
                required: '{{__('labels.brochure_valid')}}',
            },
            {{__('labels.brochure_pdf')}}: {
                required: '{{__('labels.brochure_pdf_valid')}}',
            }
        },
        submitHandler: function(form) {

            save(form);
        }
    });


</script>
@endsection
