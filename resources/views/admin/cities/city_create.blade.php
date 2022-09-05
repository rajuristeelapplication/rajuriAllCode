@extends('admin.layout.index')
@section('title') {{ empty($cityDetail) ? 'Create' : 'Edit' }} {{__('labels.city')}} @endsection
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
        <a href="{{ route('cities.index') }}"><span class="breadcrumb">{{ __('labels.cities') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($cityDetail) ? 'Create' : 'Edit' }} {{ __('labels.city')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($cityDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.city') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($cityDetail))
                            <form class="createCity" name="cityForm" id="cityForm" method="post"
                                enctype="multipart/form-data" action="{{route('cities.store')}}">
                                @else
                                <form class="createCity" name="cityForm" id="cityForm" method="post"
                                    enctype="multipart/form-data" action="{{route('cities.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($cityDetail) ? $cityDetail->id : '' }}" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{__('labels.state')}}<span
                                                        class="text-danger">*</span></label>
                                                <select id="{{__('labels.state_db')}}" name="{{__('labels.state_db')}}"
                                                    class="form-control form-select mt-2 mb-2" aria-invalid="false">
                                                    <option value="" selected>Select {{__('labels.state')}}</option>
                                                    @foreach ($states as $key => $statesInfo)
                                                    <option value="{{ $statesInfo->id }}" {{ !empty($cityDetail->
                                                        stateId) && ($cityDetail->stateId == $statesInfo->id)
                                                        ? ' selected' : ''}}> {{ $statesInfo->sName }}</option>
                                                    @endforeach
                                                </select>
                                                <span id="sName-error"></span>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.city')}}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <input type="text" name="{{__('labels.city_db')}}"
                                                    id="{{__('labels.city_db')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $cityDetail->cName  ?? '' }}" minlength="3"
                                                    maxlength="25" placeholder="Enter {{__('labels.city')}}"
                                                    aria-required="true">
                                            </div>
                                        </div>


                                        <div class="text-right">
                                            <button type="{{__('labels.submit')}}"
                                                class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                            <a href="{{ route('cities.index') }}"
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

jQuery.validator.addMethod("lettersonly", function(value, element)
        {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "please enter alphabet");

    $(".createCity").validate({
        rules: {
            'sName': {
                required: true,
            },
            {{__('labels.city_db')}}: {
                required: true,
                lettersonly: true
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
            {{__('labels.city_db')}}: {
                required: '{{__('labels.city_valid')}}',
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
