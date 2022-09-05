@extends('admin.layout.index')
@section('title') {{ empty($sliderDetail) ? 'Create' : 'Edit' }} {{__('labels.slider_flag')}} @endsection
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
        <a href="{{ route('sliders.index') }}"><span class="breadcrumb">{{ __('labels.sliders') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($sliderDetail) ? 'Create' : 'Edit' }} {{ __('labels.slider_flag')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($sliderDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.slider_flag') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($sliderDetail))
                            <form class="createSlider" name="sliderForm" id="sliderForm" method="post"
                                enctype="multipart/form-data" action="{{route('sliders.store')}}">
                                @else
                                <form class="createSlider" name="sliderForm" id="sliderForm" method="post"
                                    enctype="multipart/form-data" action="{{route('sliders.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($sliderDetail) ? $sliderDetail->id : '' }}" />
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <label for="vertical-form-1" class="form-label mt-2">
                                                    {{__('labels.image')}} <span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9">
                                                    <input class="form-control dropify mt-2 mb-2" type="file" value=""
                                                        id="{{__('labels.slider_image')}}" {{-- data-min-width="98"
                                                        data-min-height="98" data-max-width="998" data-max-height="998"
                                                        --}} name="{{__('labels.slider_image')}}" tabindex="5"
                                                        placeholder="Please upload photo" data-show-remove="false"
                                                        accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                                        data-allowed-file-extensions="jpg png jpeg"
                                                        data-default-file="{{$sliderDetail->photo??""}}">
                                                    <label id="image-error" class="error"
                                                        for="{{__('labels.slider_image')}}"
                                                        style="display: none;">Please select image</label>
                                                    {{-- <small class="form-control-feedback"> Please upload image size
                                                        minimum
                                                        (width) 100 x
                                                        100 (Height) and maximum (width) 1000 x 1000 (Height) </small>
                                                    --}}

                                                </div>
                                            </div>


                                        </div>
                                        <div class="text-left">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('sliders.index') }}"
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
    $(".createSlider").validate({
        rules: {
            @if(empty($sliderDetail->id))
            {{__('labels.slider_image')}}: {
                required: true,
            },
            @endif

        },
        messages: {
            @if(empty($sliderDetail->id))
            {{__('labels.slider_image')}}: {
                required: '{{__('labels.slider_image_valid')}}',
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
                'minWidth': 'The profile image width must be greater than 100px',
                'maxWidth': 'The profile image width must be less than 1000px',
                'minHeight': 'The profile image height must be greater than 100px',
                'maxHeight': 'The profile image height must be less than 1000px',
                'imageFormat': 'The profile image format is not allowed jpg png gif jpeg only.'
            }
        });
</script>
@endsection
