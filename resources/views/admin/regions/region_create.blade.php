@extends('admin.layout.index')
@section('title') {{ empty($regionDetail) ? 'Create' : 'Edit' }} {{__('labels.taluka')}} @endsection
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
        <a href="{{ route('regions.index') }}"><span class="breadcrumb">{{ __('labels.regions') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($regionDetail) ? 'Create' : 'Edit' }} {{ __('labels.region')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($regionDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.region') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($regionDetail))
                            <form class="createRegion" name="regionForm" id="regionForm" method="post"
                                enctype="multipart/form-data" action="{{route('regions.store')}}">
                                @else
                                <form class="createRegion" name="regionForm" id="regionForm" method="post"
                                    enctype="multipart/form-data" action="{{route('regions.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($regionDetail) ? $regionDetail->id : '' }}" />
                                    <div class="row">
                                        {{-- <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.state')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="{{__('labels.state_db')}}"
                                                    id="{{__('labels.state_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $regionDetail->title  ?? '' }}" minlength="4"
                                                    maxlength="40" placeholder="Enter {{__('labels.state')}}"
                                                    aria-required="true">
                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{__('labels.state')}}<span
                                                        class="text-danger">*</span></label>
                                                <select id="{{__('labels.state_db')}}" name="{{__('labels.state_db')}}"
                                                    class="form-control form-select mt-2 mb-2" aria-invalid="false">
                                                    <option value="" selected>Select {{__('labels.state')}}</option>
                                                    @foreach ($states as $key => $statesInfo)
                                                    <option value="{{ $statesInfo->id }}" {{ !empty($regionDetail->
                                                        stateId) && ($regionDetail->stateId == $statesInfo->id)
                                                        ? ' selected' : ''}}> {{ $statesInfo->sName }}</option>
                                                    @endforeach
                                                </select>
                                                <span id="sName-error"></span>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.region')}}
                                                    <span class="text-danger">*</span> </label>
                                                    <input type="text" name="{{__('labels.region_db')}}"
                                                    id="{{__('labels.region_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $regionDetail->rName  ?? '' }}" minlength="4"
                                                    maxlength="40" placeholder="Enter {{__('labels.region')}}"
                                                    aria-required="true">
                                            </div>
                                        </div>

                                        <div class="text-left">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('regions.index') }}"
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
    $(".createRegion").validate({
        rules: {
            'sName': {
                required: true,
            },
            {{__('labels.region_db')}}: {
                required: true,
            },
        },
        errorPlacement: function (error, element) {
          console.log(error.text());

             if (error.text() == "Please select state") {
                 error.appendTo("#sName-error");
             }
             else{
              error.insertAfter($(element));
             }
          },
        messages: {
            'sName': {
                required: 'Please select state',
            },
            {{__('labels.region_db')}}: {
                required: '{{__('labels.region_valid')}}',
            },



        },
        submitHandler: function(form) {

            save(form);
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $("select").select2({
});
});
</script>
@endsection
