@extends('admin.layout.index')
@section('title') {{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{__('labels.brand')}} @endsection
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
        <a href="{{ route('brands.index') }}"><span class="breadcrumb">{{ __('labels.brands') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{ __('labels.brand')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.brand') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($companyProfileDetail))
                            <form class="createCompanyProfile" name="companyProfileForm" id="companyProfileForm" method="post"
                                enctype="multipart/form-data" action="{{route('brands.store')}}">
                                @else
                                <form class="createCompanyProfile" name="companyProfileForm" id="companyProfileForm" method="post"
                                    enctype="multipart/form-data" action="{{route('brands.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($companyProfileDetail) ? $companyProfileDetail->id : '' }}" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.brand_name')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="eName" id="eName"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $companyProfileDetail->eName  ?? '' }}" minlength="4"
                                                    maxlength="30" placeholder="Enter brand name"
                                                    aria-required="true">
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('brands.index') }}"
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
    $(".createCompanyProfile").validate({
        rules: {
            eName: {
                required: true,
            },
        },
        messages: {
            eName: {
                required: 'please enter brand name',
            }
        },
        submitHandler: function(form) {
            save(form);
        }
    });
</script>
@endsection
