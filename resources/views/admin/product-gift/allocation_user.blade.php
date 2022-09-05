@extends('admin.layout.index')
@section('title') Gift Allocation User @endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"
    integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">

<style type="text/css">

</style>

@endsection
@section('content')
<div class="content">
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="___class_+?2___">{{ __('labels.dashboard_navigation')
                }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('products-gift.index') }}"><span class="breadcrumb">Product Gift</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">Gift Allocation User</span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Gift Allocation User</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">


                            <form class="createProduct" name="productForm" id="productForm" method="post"
                                enctype="multipart/form-data" action="{{route('products-gift.allocation_user.store')}}">

                                    {{ csrf_field() }}

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{
                                                    __('labels.created_by') }}<span class="text-danger">*</span>
                                                </label>

                                                <select id="userId" name="userId" onchange="window.location.assign('{{ url()->current().'?userId=' }}'+this.value)"  class="form-select form-select w-100 mt-2 mb-2 ">
                                                   <option value="0" selected disabled>{{
                                                        __('labels.created_by_select') }}</option>
                                                    @foreach ($userDetail as $key => $userInfo)
                                                    <option value="{{ $userInfo->id }}" {{ !empty($userId) && ($userId == $userInfo->id)? ' selected' : ''}}> {{ $userInfo->fullName }}</option>
                                                    @endforeach
                                                </select>
                                                <label id="userId-error" class="error" for="userId" style="display: none;">Please select user name</label>
                                            </div>
                                        </div>


                                        <table class="table table-bordered mt-4">

                                        <thead style="background-color: #b3b7bb;">
                                            <tr>
                                                <th width="50%">Product Name</th>
                                                <th width="50%">Allocation Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($getProducts as $key => $getProduct)

                                                @if($getProduct->isProductOption == 0 && !empty($getProduct->productOptions[0]))

                                                    <tr>
                                                        <th> {{$getProduct->pName ?? ''  }}</th>
                                                        <th>
                                                            <input type="text" class="form-control input-valid w-40 mt-2 mb-2 numeric"  maxlength="7"  name="productAllocation[{{$getProduct->productOptions[0]->id}}]" value="{{$getProduct->productOptions[0]->totalQty ?? 0  }}" />
                                                        </th>
                                                    </tr>
                                                @endif


                                                @if($getProduct->isProductOption == 1)

                                                        @forelse ($getProduct->productOptions as  $productOption)

                                                            <tr>
                                                                <th width="50%"> {{$getProduct->pName.' '."(".$productOption->productOptionName.")" ?? ''  }}</th>
                                                                <th width="50%">
                                                                    <input type="text" class="form-control input-valid w-40 mt-2 mb-2 numeric"  maxlength="7" name="productAllocation[{{$productOption->id}}]" value="{{$productOption->totalQty ?? 0  }}" />
                                                                </th>
                                                            </tr>
                                                        @empty
                                                        @endforelse
                                                @endif

                                            @empty

                                            @endforelse

                                        </tbody>


                                        </table>

                                    </div>

                                    <div class="text-right">
                                        <button type="{{__('labels.submit')}}"
                                            class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                        <a href="{{ route('products-gift.index') }}" class="btn btn-danger text-white mt-5">
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
            userId: {
                required: true,
            },
        },
        messages: {
            userId: {
                required: 'Please select user',
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
    $("select").select2({});
    });
</script>

@endsection
