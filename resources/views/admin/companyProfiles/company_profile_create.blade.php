@extends('admin.layout.index')
@section('title') {{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{__('labels.company_profile_flag')}} @endsection
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
        <a href="{{ route('companyProfiles.index') }}"><span class="breadcrumb">{{ __('labels.company_profile_cms') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{ __('labels.company_profile_flag')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($companyProfileDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.company_profile_flag') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($companyProfileDetail))
                            <form class="createCompanyProfile" name="companyProfileForm" id="companyProfileForm" method="post"
                                enctype="multipart/form-data" action="{{route('companyProfiles.store')}}">
                                @else
                                <form class="createCompanyProfile" name="companyProfileForm" id="companyProfileForm" method="post"
                                    enctype="multipart/form-data" action="{{route('companyProfiles.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($companyProfileDetail) ? $companyProfileDetail->id : '' }}" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.company_title_list_page')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="{{__('labels.company_title_db')}}"
                                                    id="{{__('labels.company_title_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $companyProfileDetail->title  ?? '' }}" minlength="4"
                                                    maxlength="40" placeholder="Enter {{__('labels.company_title_list_page')}}"
                                                    aria-required="true">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.company_sub_title_list_page')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="{{__('labels.company_sub_title_db')}}"
                                                    id="{{__('labels.company_sub_title_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $companyProfileDetail->subTitle  ?? '' }}" minlength="4"
                                                    maxlength="40" placeholder="Enter {{__('labels.company_sub_title_list_page')}}"
                                                    aria-required="true">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.company_description_list_page')}}
                                                    <span class="text-danger">*</span> </label>
                                                    <textarea id="{{__('labels.company_description_db')}}" type="text"
                                                        class="form-control input-valid w-100 mt-2 mb-2" name="{{__('labels.company_description_db')}}"
                                                        rows="8" placeholder="Enter {{__('labels.company_description_list_page')}}" aria-required="true"
                                                        aria-invalid="false" minlength="10" maxlength="600">{{  $companyProfileDetail->description  ?? '' }}</textarea>
                                            </div>
                                        </div>



                                        <div class="text-right">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('companyProfiles.index') }}"
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
            {{__('labels.company_title_db')}}: {
                required: true,
            },
            {{__('labels.company_sub_title_db')}}: {
                required: true,
            },
            {{__('labels.company_description_db')}}: {
                required: true,
            },


        },
        messages: {
            {{__('labels.company_title_db')}}: {
                required: '{{__('labels.company_title_valid')}}',
            },
            {{__('labels.company_sub_title_db')}}: {
                required: '{{__('labels.company_sub_title_valid')}}',
            },
            {{__('labels.company_description_db')}}: {
                required: '{{__('labels.company_description_valid')}}',
            },



        },
        submitHandler: function(form) {

            save(form);
        }
    });
</script>
@endsection
