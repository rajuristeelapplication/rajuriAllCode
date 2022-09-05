@extends('admin.layout.index')
@section('title') {{ empty($brochureDetail) ? 'Create' : 'Edit' }} {{__('labels.brochure_flag')}} @endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"
    integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">

<style type="text/css">
    .previewImage {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }

    .close-btn {
        background: transparent;
        padding: 0;
    }

    .close-btn:focus {
        border: none;
        outline: none;
    }

    .pointer-class {
        cursor: pointer;
    }
</style>

@endsection
@section('content')
<div class="content">
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="___class_+?2___">{{ __('labels.dashboard_navigation')
                }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('brochures.index') }}"><span class="breadcrumb">{{ __('labels.brochures') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($brochureDetail) ? 'Create' : 'Edit' }} {{ __('labels.brochure_flag')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($brochureDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.brochure_flag') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($brochureDetail))
                            <form class="createBrochure" name="brochureForm" id="brochureForm" method="post"
                                enctype="multipart/form-data" action="{{route('brochures.store')}}">
                                @else
                                <form class="createBrochure" name="brochureForm" id="brochureForm" method="post"
                                    enctype="multipart/form-data" action="{{route('brochures.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($brochureDetail) ? $brochureDetail->id : '' }}" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.brochure')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="{{__('labels.brochure_name')}}"
                                                    id="{{__('labels.brochure_name')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $brochureDetail->title  ?? '' }}" minlength="4"
                                                    maxlength="40" placeholder="{{__('labels.enter_brochure')}}"
                                                    aria-required="true">
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-8">

                                                <label for="vertical-form-1" class="form-label mt-2">
                                                    {{__('labels.brochure_pdf_title')}} <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9">
                                                    <input class="form-control dropify mt-2 mb-2" type="file" value=""
                                                        id="{{__('labels.brochure_pdf')}}"

                                                        {{-- data-max-width="998" data-max-height="998" --}}
                                                        name="{{__('labels.brochure_pdf')}}" tabindex="5"
                                                        placeholder="Please upload photo" data-show-remove="false"
                                                        accept="application/pdf" data-allowed-file-extensions="pdf"
                                                        data-default-file="{{$brochureDetail->pdf ?? ""}}">
                                                    <label id="image-error" class="error"
                                                        for="{{__('labels.brochure_pdf')}}"
                                                        style="display: none;">Please select pdf</label>
                                                    {{-- <small class="form-control-feedback"> Please upload image size
                                                        minimum
                                                        (width) 100 x
                                                        100 (Height) and maximum (width) 1000 x 1000 (Height) </small>
                                                    --}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">

                                                <label for="vertical-form-1" class="form-label mt-2">
                                                    {{__('labels.image')}} <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9">
                                                    <input class="form-control dropify mt-2 mb-2" type="file" value=""
                                                        id="{{__('labels.brochure_image')}}"
                                                        {{-- data-min-width="169" data-min-height="199"
                                                        data-max-width="201" data-max-height="251" --}}
                                                        name="{{__('labels.brochure_image')}}" tabindex="5"
                                                        placeholder="Please upload photo" data-show-remove="false"
                                                        accept="image/x-png,image/jpg,image/gif,image/jpeg" data-allowed-file-extensions="jpg png jpeg"
                                                        data-default-file="{{$brochureDetail->image ?? ""}}">
                                                    <label id="image-error" class="error"
                                                        for="{{__('labels.brochure_image')}}"
                                                        style="display: none;">Please select image</label>
                                                    <small class="form-control-feedback"> Please upload image size
                                                        minimum (width) 170 x 200 (Height)  and maximum (width) 200 x 250 (Height) </small>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('brochures.index') }}"
                                                class="btn btn-danger text-white mt-5">
                                                {{__('labels.cancel')}}</a>
                                        </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"
    integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(".createBrochure").validate({
        rules: {
            {{__('labels.brochure_name')}}: {
                required: true,
            },
            @if(empty($brochureDetail->id))
            {{__('labels.brochure_image')}}: {
                required: true,
            },
            @endif

            @if(empty($brochureDetail->id))
            {{__('labels.brochure_pdf')}}: {
                required: true,
            },
            @endif

        },
        messages: {
            {{__('labels.brochure_name')}}: {
                required: '{{__('labels.brochure_valid')}}',
            },
            @if(empty($brochureDetail->id))
            {{__('labels.brochure_image')}}: {
                required: '{{__('labels.brochure_image_valid')}}',
            },
            @endif

            @if(empty($brochureDetail->id))
            {{__('labels.brochure_pdf')}}: {
                required: '{{__('labels.brochure_pdf_valid')}}',
            },
            @endif

        },
        submitHandler: function(form) {

            save(form);
        }
    });
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script>
    // image dropify
      var $j = jQuery.noConflict();
      $j('.dropify').dropify({
            error: {
                // 'minWidth': 'The profile image width must be greater than 170px',
                // 'maxWidth': 'The profile image width must be less than 200px',
                // 'minHeight': 'The profile image height must be greater than 200px',
                // 'maxHeight': 'The profile image height must be less than 250px',
                // 'imageFormat': 'The profile image format is not allowed jpg png gif jpeg only.'
            }
        });
</script>
@endsection
