@extends('admin.layout.index')
@section('title') {{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{__('labels.material_product')}} @endsection
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
        <a href="{{ route('material-products.index') }}"><span class="breadcrumb">{{ __('labels.material_product') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{ __('labels.material_product')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.material_product') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($companyProfileDetail))
                            <form class="createCompanyProfile" name="companyProfileForm" id="companyProfileForm" method="post"
                                enctype="multipart/form-data" action="{{route('material-products.store')}}">
                                @else
                                <form class="createCompanyProfile" name="companyProfileForm" id="companyProfileForm" method="post"
                                    enctype="multipart/form-data" action="{{route('material-products.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($companyProfileDetail) ? $companyProfileDetail->id : '' }}" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.material_name')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="mName" id="mName"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $companyProfileDetail->mName  ?? '' }}" minlength="4"
                                                    maxlength="255" placeholder="Enter material product name"
                                                    aria-required="true">
                                            </div>
                                        </div>


                                     <div class="form-group m-t-40 row">
                                        <label for="isActive" class="col-12 col-form-label mb-2">Sub Option</label>
                                        <div class="custom-control custom-radio col-2">
                                            <input type="radio" id="status1" name="isSubOption" value="1"
                                                class="custom-control-input"
                                                {{ !empty($companyProfileDetail) && $companyProfileDetail->isSubOption == 1 ? 'checked' : '' }}
                                                {{ empty($companyProfileDetail) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="status1">Straight Bend</label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio col-2">
                                            <input type="radio" id="status2" name="isSubOption" value="0"
                                                class="custom-control-input"
                                                {{ !empty($companyProfileDetail) && $companyProfileDetail->isSubOption == 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="status2">No Straight Bend</label>
                                        </div>
                                    </div>


                                    </div>

                                        <div class="text-right">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('material-products.index') }}"
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
            mName: {
                required: true,
            },
        },
        messages: {
            mName: {
                required: 'please enter name',
            }
        },
        submitHandler: function(form) {
            save(form);
        }
    });
</script>
@endsection
