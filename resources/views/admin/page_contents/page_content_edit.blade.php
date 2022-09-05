@extends('admin.layout.index')
@section('title') Edit {{ $pageContent->name ?? '' }} Content @endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.css') }}">
@endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">Dashboard</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('page-contents.index') }}">
            <span class="breadcrumb">Page Content</span>
        </a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href=""><span class="breadcrumb--active">Page Content Edit</span></a>
    </div>

    @endsection
    @include('admin.common.notification')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        Page Content Edit
                    </h2>
                    <!-- <a href="" class="ms-auto d-flex text-theme-color"> <i class="feather-refresh-ccw w-4 h-4 me-2"></i> Reload Data </a> -->
                </div>
                @include('admin.common.flash')
                <br><br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="preview">
                                    <form class="form" name="editPageContent" id="editPageContent" method="post"
                                        enctype="multipart/form-data"
                                        action="{{ route('page-contents.update', ['page_content' => $pageContent->id]) }}">
                                        @method('PUT')
                                        {{ csrf_field() }}

                                        <div class="input-form">
                                            <label class="d-flex flex-column flex-sm-row"> Title <span
                                                    class="ms-sm-auto mt-1 sm:mt-0 text-xs text-gray-600"></span>
                                            </label>
                                            <input type="text" name="name" id="name"
                                                class="form-control input w-100 mt-2" tabindex="1"
                                                value="{{ $pageContent->name ?? '' }}" required>
                                            <div class="invalid-feedback">
                                                This field is required
                                            </div>
                                        </div>

                                        <div class="input-form">
                                            <label class="d-flex flex-column flex-sm-row mt-2 mb-2"> Content <span
                                                    class="ms-sm-auto mt-1 sm:mt-0 text-xs text-gray-600"></span>
                                            </label>
                                            <textarea name="content" id="content"
                                                class="summernote form-control">{{ $pageContent->content ?? '' }}</textarea>
                                            <label id="content-error-msg" class="error" style="display:none;">Please
                                                enter
                                                content</label>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-12 text-left mt-2">
                                                <button id="upload" type="submit" class="btn btn-success">
                                                    {{ empty($pageContent) ? 'Create' : 'Update' }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: General Report -->
    </div>
</div>
</div>
<!-- END: Content -->

@endsection

@section('js')
<script src="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        //override required method
        $.validator.methods.required = function(value, element, param) {

            return (value == undefined) ? false : (value.trim() != '');
        }

        $('.summernote').summernote({
            height: 300, // set editor height
            disableDragAndDrop:true,
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true, // set focus to editable area after initializing summernote
            toolbar: [
                [ 'style', [ 'style' ] ],
                  [ 'font', [ 'bold'] ],
                  [ 'insert', [ 'link'] ],
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


        $(".form").validate({
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
                name: {
                    required: 'Title must be required',
                },
                content: {
                    required: 'Content must be required',
                }
            },
            submitHandler: function(form) {

                var isEmpty = $('#content').summernote('isEmpty');

                if (requiredDescription()) {
                    alert("form save");
                    form.submit();
                }

            },
            invalidHandler: function(form, validator) {

                showContent();
            }
        });

        function requiredDescription() {
            var content = $('.summernote').summernote('isEmpty');
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
