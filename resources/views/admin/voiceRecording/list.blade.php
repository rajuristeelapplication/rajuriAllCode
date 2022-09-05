@extends('admin.layout.index')
@section('css')

@endsection

@section('title') {{ __('labels.voice_recording_list_title') }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>


        <a href="{{ route('voiceRecordings.index') }}"><span class="breadcrumb--active">
                {{__('labels.manage_voice_recording')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')


    <div class="row mt-3">
            <div class="form-group col-md-4">
                <label for="name"><span class="fa fa-filter "></span> Executive</label>
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
    </div>


    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{ __('labels.voice_recording_list_title') }}
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
                    <table id="voiceRecordings"
                        class="display nowrap table table-hover table-striped table-bordered last-cl-fxied"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>{{__('labels.created_by')}}</th>
                                <th >{{ __('labels.voice_recording')}}</th>
                                {{-- <th>{{ __('labels.isActive') }}</th> --}}
                                <th>{{__('labels.create_date_time')}}</th>
                                <th>{{__('labels.create_date_time')}}</th>

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
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var table;
    $(function() {
        table = $('#voiceRecordings').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: true,
            "ajax": {
                "url": '{{ url(route('voiceRecordings.search')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.employeeType = $('#employeeType').val();
                }
            },
            aaSorting: true,

            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false,
                },
                {
                    data: '{{__('labels.created_by_db')}}',
                    name: '{{__('labels.created_by_db')}}',
                },
                {
                    data: '{{__('labels.voice_rec_db')}}',
                    name: '{{__('labels.voice_rec_db')}}',
                    orderable: false
                },
                {
                    data: '{{__('labels.created_at_db')}}',
                    name: '{{__('labels.created_at_db')}}',
                    "visible": false,
                },
                {
                    data: 'created_at_format',
                    name: 'created_at_format',
                    "visible": true,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "aaSorting": [
                [3, 'desc']
            ],
            "pageLength": 50
        });

        // filter after reload table

    });
    // jQuery(function($) {
    $(document).on('change', '.employeeType', function() {
            console.log($('#employeeType').val());
            table.ajax.reload();
        });
    // });

    $("#voiceRecordings").on('change', '.btnChangeStatus', function() {

        $('#status-confirmation-modal').modal('show');
        var status = $(this).prop('checked') ? "activate" : "inactive";

        var leadType = $(this).attr('data-leadType');

        $('#statusTitle').text('Do you really want to ' + status + ' this voice recording?');
        $('#statusData').attr('data-status-link', $(this).attr('data-url'));

    });

</script>

<script>
    $( function() {
    $(".select2Class").select2({
});
});
</script>

{{--
    You can use event delegation.
    Simply listen to the play event
    in the capturing phase and then
    pause all video file, but not
    the target one:
--}}
<script>
    document.addEventListener('play', function(e){
    var audios = document.getElementsByTagName('audio');
    for(var i = 0, len = audios.length; i < len;i++){
        if(audios[i] != e.target){
            audios[i].pause();
        }
    }
}, true);
</script>
@endsection
