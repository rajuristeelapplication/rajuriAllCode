@extends('admin.layout.index')
@section('title') {{ empty($productDetail) ? 'Create' : 'Edit' }} {{__('labels.product')}} @endsection
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
        <a href="{{ route('products.index') }}"><span class="breadcrumb">{{ __('labels.products') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($productDetail) ? 'Create' : 'Edit' }} {{ __('labels.product')
            }}</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($productDetail) ? 'Create' : 'Edit' }} {{
                    __('labels.product') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($productDetail))
                            <form class="createProduct" name="productForm" id="productForm" method="post"
                                enctype="multipart/form-data" action="{{route('products.store')}}">
                                @else
                                <form class="createProduct" name="productForm" id="productForm" method="post"
                                    enctype="multipart/form-data" action="{{route('products.store')}}">

                                    @endif
                                    {{ csrf_field() }}
                                    <input type="hidden" name="removeProductAttribute" id="removeArr" value="">
                                    <input type="hidden" name="id" id="id"
                                        value="{{ !empty($productDetail) ? $productDetail->id : '' }}" />
                                    <div class="row align-items-end">
                                            <div class="col-md-6">
                                                <label for="vertical-form-1"
                                                    class="form-label mt-2">{{__('labels.product_name')}}<span
                                                        class="text-danger">*</span></label>
                                                <input id="{{__('labels.product_name_db')}}"
                                                    value="{{  $productDetail->pName  ?? '' }}" minlength="1"
                                                    maxlength="40" type="text" class="form-control"
                                                    name="{{__('labels.product_name_db')}}"
                                                    placeholder="Enter {{__('labels.product_name')}}">
                                            </div>

                                            <input type="hidden" name="pType" value="Order" />

                                            <div class="col-md-6">
                                                <label for="vertical-form-1"
                                                    class="form-label mt-2">{{__('labels.is_product_have_option')}}<span
                                                        class="text-danger">*</span></label><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="radio" id="radio"
                                                        value="1" {{ !empty($productDetail) && ($productDetail->isProductOption == 1)
                                                    ? ' checked' : ''}}>
                                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="radio" id="radio"
                                                        value="0" {{  !empty($productDetail) && ($productDetail->isProductOption == 0)
                                                    ? ' checked' : ''}}>
                                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                                </div>
                                                <div>
                                                    <span id="radio-error"></span>
                                                </div>
                                            </div>

                                            {{-- @if(empty($productDetail) || (empty($productDetail->productOptionName) &&
                                            $productDetail->isProductOption == 0)) --}}
                                            <div class="col-md-6 totalQtyProduct">
                                                <label for="vertical-form-1"
                                                    class="form-label mt-2 ">Price<span
                                                        class="text-danger">*</span></label>
                                                <input id="{{__('labels.totalQty_db')}}"
                                                    value="{{ !empty($productDetail) && ($productDetail->isProductOption == 0) ? $productAttributeDetail[0]->totalQty  : '' }}"
                                                    minlength="1" maxlength="6" type="text" class="form-control numeric"
                                                    name="{{__('labels.totalQty_db')}}"
                                                    placeholder="Please Enter Price">



                                            </div>
                                            <div class="col-md-6 totalQtyProduct">
                                                <label for="vertical-form-1"
                                                    class="form-label mt-2 d-block">Description</label>
                                                <input type="checkbox"  title="Description" name="isDescription" style="width: 2em;height: 2em;"
                                                    {{
                                                        (!empty($productDetail) && $productAttributeDetail[0]->isDescription )  ? 'checked' : ''
                                                    }}
                                                    />
                                            </div>



                                            {{-- @endif --}}
                                    </div>
                                    <div class="row addProductQty">
                                        <div class="input-form" style="margin-top: 20px;">
                                            <label for="example-text-input"
                                                class="col-2 col-form-label">{{__('labels.product_option_name')}}<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-12">
                                                <table class="table table-borderless" id="dynamic_field">
                                                    @if(!empty($productAttributeDetail) &&
                                                    !empty($productAttributeDetail) && $productDetail->isProductOption
                                                    == 1)
                                                    <b><strong>
                                                            @foreach ($productAttributeDetail as $key => $value)
                                                            @if($key == 0)
                                                            <tr>
                                                                <td width="31%">
                                                                    <input type="text" id="content{{$key}}"
                                                                        name="productOptionName[{{$value->id}}]"
                                                                        placeholder="{{__('labels.product_option_placeholder')}}"
                                                                        class="form-control name_list"
                                                                        value="{{$value->productOptionName}}"
                                                                        minlength="1" maxlength="40"
                                                                        required="required" />
                                                                    <label id="content{{$value->id}}-error"
                                                                        class="error" style="display:none;"
                                                                        for="content{{$value->id}}">Please Enter Product
                                                                        Option Name</label>
                                                                </td>
                                                                <td width="31%">
                                                                    <input type="text" id="content{{$value->id}}"
                                                                        name="totalQty[{{$value->id}}]"
                                                                        placeholder="{{__('labels.total_quantity_placeholder')}}"
                                                                        class="form-control qty_list numeric"
                                                                        value="{{$value->totalQty}}" minlength="1"
                                                                        maxlength="6" required />

                                                                    <label id="content{{$value->id}}-error"
                                                                        class="error" style="display:none;"
                                                                        for="content{{$value->id}}">Please Enter Total
                                                                        Quantity</label>
                                                                </td>
                                                                <td width="10%">
                                                                    <input style="width: 2em;height: 2em;"
                                                                        type="checkbox" id="content{{$value->id}}"
                                                                        title="Description"
                                                                        name="isDescription[{{$value->id}}]"
                                                                        value="{{$value->isDescription}}" {{
                                                                        ($value->isDescription == 1) ? 'checked' : ''
                                                                    }}>
                                                                </td>
                                                                {{-- <td width="10%">
                                                                    <input style="width: 2em;height: 2em;"
                                                                        type="checkbox" id="content{{$value->id}}"
                                                                        title="Attachment"
                                                                        name="isAttachment[{{$value->id}}]"
                                                                        value="{{$value->isAttachment}}" {{
                                                                        ($value->isAttachment == 1) ? 'checked' : '' }}>
                                                                </td>
                                                                <td style="width: 10px;"></td> --}}
                                                            </tr>
                                                            @else
                                                            <tr id="row{{$value->id}}" class="dynamic-added">

                                                                <td width="31%">
                                                                    <input type="text" id="content{{$value->id}}"
                                                                        name="productOptionName[{{$value->id}}]"
                                                                        placeholder="{{__('labels.product_option_placeholder')}}"
                                                                        class="form-control name_list"
                                                                        value="{{$value->productOptionName}}"
                                                                        minlength="1" maxlength="40" required />

                                                                    <label id="content{{$value->id}}-error"
                                                                        class="error" style="display:none;"
                                                                        for="content{{$value->id}}">Please Enter Product
                                                                        Option Name</label>
                                                                </td>

                                                                <td width="31%">
                                                                    <input type="text" id="content{{$value->id}}"
                                                                        name="totalQty[{{$value->id}}]"
                                                                        placeholder="{{__('labels.total_quantity_placeholder')}}"
                                                                        class="form-control qty_list"
                                                                        value="{{$value->totalQty}}" minlength="1"
                                                                        maxlength="6" required />

                                                                    <label id="content{{$value->id}}-error"
                                                                        class="error" style="display:none;"
                                                                        for="content{{$value->id}}">Price</label>
                                                                </td>
                                                                <td width="10%">
                                                                    <input
                                                                        style="width: 2em;height: 2em; vertical-align: top;"
                                                                        type="checkbox" id="content{{$value->id}}"
                                                                        title="Description"
                                                                        name="isDescription[{{$value->id}}]"
                                                                        value="{{$value->isDescription}}" {{
                                                                        ($value->isDescription == 1) ? 'checked' : ''
                                                                    }}>
                                                                </td>
                                                                {{-- <td width="10%">
                                                                    <input
                                                                        style="width: 2em;height: 2em; vertical-align: top;"
                                                                        type="checkbox" id="content{{$value->id}}"
                                                                        title="Attachment"
                                                                        name="isAttachment[{{$value->id}}]"
                                                                        value="{{$value->isAttachment}}" {{
                                                                        ($value->isAttachment == 1) ? 'checked' : '' }}>
                                                                </td> --}}
                                                                <td style="width: 10px;">
                                                                    <button type="button" name="remove"
                                                                        id="{{$value->id}}"
                                                                        class="btn btn-danger btn_remove">X</button>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                            @endforeach
                                                            @else

                                                            <tr>

                                                                <td width="31%">
                                                                    <input type="text" id="content1"
                                                                        name="productOptionName[1]"
                                                                        placeholder="{{__('labels.product_option_placeholder')}}"
                                                                        class="form-control name_list" value=""
                                                                        required="required" />
                                                                    <label id="content1-error" class="error"
                                                                        style="display:none;" for="content1">Please
                                                                        Enter Product Option Name</label>
                                                                </td>
                                                                <td width="31%">
                                                                    <input type="text" id="content1" name="totalQty[1]"
                                                                        placeholder="Please Enter Price"
                                                                        class="form-control name_list numeric" value=""
                                                                        required="required" />
                                                                    <label id="content1-error" class="error"
                                                                        style="display:none;" for="content1">Price</label>
                                                                </td>

                                                                <td width="10%">
                                                                    <input
                                                                        style="width: 2em;height: 2em; vertical-align: top;"
                                                                        type="checkbox" id="content1"
                                                                        title="Description" name="isDescription[1]"
                                                                        value="1">
                                                                </td>
                                                                {{-- <td width="10%">
                                                                    <input
                                                                        style="width: 2em;height: 2em; vertical-align: top;"
                                                                        type="checkbox" id="content1" title="Attachment"
                                                                        name="isAttachment[1]" value="1">
                                                                </td>

                                                                <td style="width: 10px;"></td> --}}
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
                                        <a href="{{ route('products.index') }}" class="btn btn-danger text-white mt-5">
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
    $(".createProduct").validate({
        rules: {
            {{__('labels.product_name_db')}}: {
                required: true,
            },
            {{__('labels.totalQty_db')}}:{
                required: true,
            },
            {{__('labels.pType_db')}}:{
                required: true,
            },
            'radio':{
                required: true,
            },
        },
        errorPlacement: function (error, element) {
          console.log(error.text());

             if (error.text() == "Please Select Option") {
                 error.appendTo("#radio-error");
             }
             else{
              error.insertAfter($(element));
             }
          },
        messages: {
            {{__('labels.product_name_db')}}: {
                required: '{{__('labels.product_valid')}}',
            },
            {{__('labels.totalQty_db')}}: {
                required: '{{__('labels.quantity_valid')}}',
            },
            {{__('labels.pType_db')}}: {
                required: '{{__('labels.pType_select')}}',
            },
            'radio': {
                required: 'Please Select Option',
            },
        },
        submitHandler: function(form) {

            save(form);
        }
    });
</script>

<script>
    $(document).ready(function() {

    // $('.totalQtyProduct').show();

    var i = 2;
    var removeArr = [];
    $('#add1').click(function(){
        $('.totalQtyProduct').hide();
        i++;
        $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td width="31%"><input type="text" "minlength="1" maxlength="40" name="productOptionName['+i+']" id="content'+i+'" placeholder="{{__('labels.product_option_placeholder')}}" class="form-control name_list" required/><label id="content'+i+'-error" class="error" style="display:none;" for="content'+i+'">Please enter department head</label></td><td width="31%"><input type="text" name="totalQty['+i+']" id="content'+i+'" placeholder="Please Enter Price" minlength="1" maxlength="7" type="text" class="form-control numeric qty_list" required/><label id="content'+i+'-error" class="error" style="display:none;" for="content'+i+'">Price</label></td><td width="10%" class="middle" style="vertical-align: top;"><input title="Description" style="width: 2em;height: 2em;" class="form-check-input" type="checkbox" id="content'+i+'" name="isDescription['+i+']" value="1"></td><td style="width: 10px;vertical-align: top;"><button type="button" name="remove" id='+i+' class="btn btn-danger btn_remove">X</button></td></tr>');
    });


    $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr("id");

        removeArr.push(button_id);
        $('#removeArr').val(removeArr);
        $('#row'+button_id+'').remove();

        var checkLastRow = $('#dynamic_field tr:last').attr('id') ?? 0;



        // if (checkLastRow == 0) {
        //     $('.totalQtyProduct').show();
        // }else{
        //     $('.totalQtyProduct').hide();
        // }
    });

});

$(document).ready(function() {
    var t = $("#radio").val();
        @if (!empty($productDetail) && $productDetail->isProductOption == 1)
            $(".addProductQty").show();
            $(".totalQtyProduct").hide();
        @elseif (!empty($productDetail) && $productDetail->isProductOption == 0)
        // alert('yes')
            $(".totalQtyProduct").show();
            $(".addProductQty").hide();
        @else
            $(".totalQtyProduct").hide();
            $(".addProductQty").hide();
        @endif

    $("input[name$='radio']").click(function() {
        var test = $(this).val();
// alert(test)
        if (test == 1) {
            $(".totalQtyProduct").hide();
            $(".addProductQty").show();
        }
        if (test == 0) {
            // alert('a')
            $(".totalQtyProduct").show();
            $(".addProductQty").hide();
        }



    });
});
</script>

@endsection
