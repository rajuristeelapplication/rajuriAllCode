@extends('admin.layout.index')
@section('title') {{ empty($departmentDetail) ? 'Create' : 'Edit' }} {{__('labels.department')}} @endsection
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
        <a href="{{ route('departments.index') }}"><span class="breadcrumb">{{ __('labels.departments') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($departmentDetail) ? 'Create' : 'Edit' }} {{ __('labels.department')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($departmentDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.department') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($departmentDetail))
                            <form class="createDepartment" name="departmentForm" id="departmentForm" method="post"
                                enctype="multipart/form-data" action="{{route('departments.store')}}">
                                @else
                                <form class="createDepartment" name="departmentForm" id="departmentForm" method="post"
                                    enctype="multipart/form-data" action="{{route('departments.store')}}">

                                    @endif
                                    {{ csrf_field() }}
                                    <input type="hidden" name="removeHeadDepartment" id="removeArr" value="">
                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($departmentDetail) ? $departmentDetail->id : '' }}" />
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-6">

                                                <label for="vertical-form-1"
                                                    class="form-label mt-2">{{__('labels.department')}}<span
                                                        class="text-danger">*</span></label>
                                                <input id="{{__('labels.department_name')}}"
                                                    value="{{  $departmentDetail->dName  ?? '' }}" minlength="2"
                                                    maxlength="40" type="text" class="form-control"
                                                    name="{{__('labels.department_name')}}"
                                                    placeholder="{{__('labels.enter_department')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-form" style="margin-top: 20px;">
                                            <label for="example-text-input"
                                                class="col-2 col-form-label">{{__('labels.department_head')}}<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-6">
                                                <table class="table table-borderless" id="dynamic_field">
                                                    @if(!empty($headDepartmentDetail) && !empty($headDepartmentDetail))
                                                    <b><strong>
                                                            @foreach ($headDepartmentDetail as $key => $value)
                                                            @if($key == 0)
                                                            <tr>
                                                                <td>
                                                                    <input type="text" id="content{{$key}}"
                                                                        name="hdName[{{$value->id}}]"
                                                                        placeholder="{{__('labels.department_head_placeholder')}}"
                                                                        class="form-control name_list"
                                                                        value="{{$value->hdName}}"
                                                                        required="required" />
                                                                    <label id="content{{$value->id}}-error" class="error"
                                                                        style="display:none;"
                                                                        for="content{{$value->id}}">Please enter department
                                                                        head.</label>
                                                                </td>
                                                                <td style="width: 10px;"></td>
                                                            </tr>
                                                            @else
                                                            <tr id="row{{$value->id}}" class="dynamic-added">

                                                                <td>
                                                                    <input type="text" id="content{{$value->id}}"
                                                                        name="hdName[{{$value->id}}]"
                                                                        placeholder="{{__('labels.department_head_placeholder')}}"
                                                                        class="form-control name_list"
                                                                        value="{{$value->hdName}}" required />
                                                                    <label id="content{{$value->id}}-error" class="error"
                                                                        style="display:none;"
                                                                        for="content{{$value->id}}">Please enter department
                                                                        head.</label>
                                                                </td>
                                                                <td style="width: 10px;">
                                                                    <button type="button" name="remove" id="{{$value->id}}"
                                                                        class="btn btn-danger btn_remove">X</button>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                            @endforeach
                                                            @else

                                                            <tr>

                                                                <td>
                                                                    <input type="text" id="content0" name="hdName[0]"
                                                                        placeholder="{{__('labels.department_head_placeholder')}}"
                                                                        class="form-control name_list" value=""
                                                                        required="required" />
                                                                    <label id="content0-error" class="error"
                                                                        style="display:none;" for="content0">Please
                                                                        enter department head.</label>
                                                                </td>
                                                                <td style="width: 10px;"></td>
                                                            </tr>

                                                            @endif
                                                </table>
                                                <div class="col-12">
                                                    <button type="button" name="add" id="add1" class="btn btn-success">
                                                        <i class="fa fa-plus-circle"> </i> Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="{{__('labels.submit')}}"
                                            class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                        <a href="{{ route('departments.index') }}"
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
    $(".createDepartment").validate({
        rules: {
            {{__('labels.department_name')}}: {
                required: true,
            }
        },
        messages: {
            {{__('labels.department_name')}}: {
                required: '{{__('labels.department_valid')}}',
            }
        },
        submitHandler: function(form) {

            save(form);
        }
    });
</script>

<script>
$(document).ready(function() {
    var i = 30;
    var removeArr = [];
    $('#add1').click(function(){
        $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" name="hdName['+i+']" id="content'+i+'" placeholder="{{__('labels.department_head_placeholder')}}" class="form-control name_list" required/><label id="content'+i+'-error" class="error" style="display:none;" for="content'+i+'">Please enter department head.</label></td><td style="width: 10px;"><button type="button" name="remove" id='+i+' class="btn btn-danger btn_remove">X</button></td></tr>');
        i++;
    });


    $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr("id");
        removeArr.push(button_id);
        $('#removeArr').val(removeArr);
        $('#row'+button_id+'').remove();
    });

});
</script>

@endsection
