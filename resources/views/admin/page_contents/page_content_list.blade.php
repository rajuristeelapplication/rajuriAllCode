@extends('admin.layout.index')

@section('title') {{__('labels.page_contents')}} @endsection
@section('css')
    <link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.css') }}">

@endsection
@section('content')

    <!-- BEGIN: Content -->
    <div class="content">
        <!-- BEGIN: Top Bar -->
    @section('navigation')
        <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
            <a href="{{ route('AdminDashboard') }}"><span class="">{{__('labels.dashboard_navigation')}}</span></a>
            <i class="feather-chevron-right" class="breadcrumb__icon"></i>
            <a href="{{ route('page-contents.index') }}"><span class="breadcrumb--active">{{__('labels.page_contents')}}</span></a>
        </div>
    @endsection
    @include('admin.common.notification')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12   grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">{{__('labels.page_contents_list_title')}}</h2>
                </div>
                @include('admin.common.flash')
                <br><br>

                <div class="table-responsive">
                    <table id="page_contents" class="display nowrap table table-hover table-striped table-bordered"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">{{__('labels.no')}}</th>
                                <th>{{__('labels.page_name')}}</th>
                                <th>{{__('labels.action')}}</th>
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

@include('admin.common.forms')
@endsection
@section('js')
<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script>
    var table;
    $(function() {
        table = $('#page_contents').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            "ajax": {
                "url": '{{ url(route('page-contents.search')) }}',
                "type": "POST",
                "async": false,
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [

                {
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name'
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


    function edit(url) {
        $('.loader').show();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: url,
            cache: false,
            processData: false,
            contentType: false,
            type: 'GET',
            success: function(result) {

                $('.loader').hide();
                if (result.status == 1) {
                    $('#formTitle').text('Edit Page Content');
                    $("#page-content-modal").modal('show');
                    $('#id').val(result.result.id);
                    $('#name').val(result.result.name);

                    $(".summernote").summernote("code", result.result.content);


                    return true;
                }
                $.toast({
                    heading: 'Error',
                    text: result.message,
                    showHideTransition: 'fade',
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                });
            },
            error: function(error) {
                console.log(error);
                $('.loader').hide();
                $("#industry-modal").modal('hide');
            }
        });
    }

    $(document).ready(function() {
        //override required method
        $.validator.methods.required = function(value, element, param) {

            return (value == undefined) ? false : (value.trim() != '');
        }

        $('.summernote').summernote({
            height: 300, // set editor height
            disableDragAndDrop:true, // disable drag and drop on editor
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true, // set focus to editable area after initializing summernote
            toolbar: [
              [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold','underline'] ],
                // [ 'fontsize', [ 'fontsize' ] ],
               //  [ 'color', [ 'color' ] ],
              //   [ 'para', [ 'ul', 'ol', 'paragraph' ] ],
              //   [ 'table', [ 'table' ] ],
              //   [ 'insert', [ 'link'] ],
                [ 'view', [ 'fullscreen', 'codeview', 'help' ] ]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    myElement = $('#content');
                    myElement.val(myElement.summernote('isEmpty') ? "" : contents);
                    showContent();
                }
            }
        });


        $("#editPageContent").validate({
            ignore: "",
            rules: {
                name: {
                    required: true,
                },
                content: {
                    required: true,
                }
            },
            messages: {
                title: {
                    required: 'Title must be required',
                },
                content: {
                    required: 'Content must be required',
                }
            },
            submitHandler: function(form) {


                save(form);

            },
            invalidHandler: function(form, validator) {

                showContent();
            }
        });

        function save(form) {
            $('.loader').show();
            var data = new FormData($('form')[0]);
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('page-contents.store') }}",
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function(result) {
                    $('.loader').hide();
                    console.log(result);
                    if (result.status == 1) {
                        $("#page-content-modal").modal('hide');
                        $.toast({
                            heading: 'Success',
                            text: result.message,
                            showHideTransition: 'fade',
                            icon: 'success',
                            position: 'top-right',
                            loader: false,
                        });
                        table.ajax.reload();
                        return true;
                    }
                    $.toast({
                        heading: 'Error',
                        text: result.message,
                        showHideTransition: 'fade',
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    });
                },
                error: function(error) {
                    console.log(error);
                    $('.loader').hide();
                    $("#page-content-modal").modal('hide');
                }
            });

        }

        function requiredDescription() {
            var content = $('.summernote').summernote('isEmpty');
            console.log(content);
            if (content.trim() == '') {
                $('#content-error-msg').show();
                return false;
            }

            $('#content-error-msg').hide();
            return true;
        }

        function showContent() {
            var content = $('.summernote').summernote('isEmpty');
            if (content) {
                $('#content-error-msg').show();
                return false;
            }

            $('#content-error-msg').hide();
            return true;
        }
    });
</script>
@endsection
