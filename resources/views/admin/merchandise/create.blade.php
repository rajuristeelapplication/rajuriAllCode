@extends('admin.layout.index')
@section('title') {{ empty($merchandiseDetail) ? 'Create' : 'Edit' }} Merchandise @endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"
    integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

<style type="text/css">


    .hideClass {
        display: none;
    }

    .select2-container {
        width: 100% !important;
    }

    .mtypeClass {
            background-color: red;
    }

    .disableClass{
        pointer-events:none;
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
        <a href="{{ route('merchandise.index',['type' => $type]) }}"><span class="breadcrumb">Manage Merchandise</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ empty($merchandiseDetail) ? 'Create' : 'Edit' }} Merchandise  {{ ucfirst($type) }}
        </span>
    </div>
    @endsection

    @include('admin.common.notification')
    @include('admin.common.flash')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ empty($merchandiseDetail) ? 'Create' : 'Edit' }} Merchandise  {{ ucfirst($type) }} </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="preview">

                            @if (empty($merchandiseDetail))
                            <form class="createSchedule" name="scheduleForm" id="scheduleForm" method="post"
                                enctype="multipart/form-data" action="{{route('merchandise.store')}}">
                                @else
                                <form class="createSchedule" name="scheduleForm" id="scheduleForm" method="post"
                                    enctype="multipart/form-data" action="{{route('merchandise.store')}}">

                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id"  value="{{ !empty($merchandiseDetail) ? $merchandiseDetail->id : '' }}" />

                                    <input type="hidden" name="itemTypeArray" id="itemTypeArray"  value="{{ !empty($merchandiseDetail) ? $merchandiseDetail->itemNames : '' }}" />

                                    <div class="row">

                                        {{-- {{ !empty($merchandiseDetail->mDate) ? $merchandiseDetail->mDate : date('Y-m-d') }} --}}
                                        {{-- {{  $merchandiseDetail->mDate  ?? '' }} --}}
                                        <input type="hidden" name="mType" value="{{ $type }}" />

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.date')}} </label>
                                                <input type="text" name="mDate"  id="mDate"
                                                    class="form-control input-valid w-100 mt-2 mb-2 datepickerdate"
                                                    value="{{ !empty($merchandiseDetail->mDate) ? $merchandiseDetail->mDate : date('Y-m-d') }}"
                                                    maxlength="40" placeholder="Please Select {{__('labels.date')}}"
                                                    aria-required="true" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{__('labels.time')}} </label>
                                                <input type="text" name="mTime"
                                                    id="mTime"
                                                    class="form-control input-valid w-100 mt-2 mb-2 timePic"
                                                    value="{{ !empty($merchandiseDetail->mTime) ? Carbon\Carbon::parse($merchandiseDetail->mTime)->format(config('constant.admin_dealer_time_format_create')) : Carbon\Carbon::parse(date('H:i'))->format(config('constant.admin_dealer_time_format_create')) }}"
                                                    minlength="4" maxlength="40"
                                                    placeholder="Please Select {{__('labels.time')}}"
                                                    aria-required="true" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{
                                                    __('labels.created_by') }}<span class="text-danger">*</span>
                                                </label>

                                                @if($type == "gift" || $type == "Gift" )
                                                <select id="userId" name="userId" onchange="window.location.assign('{{ url()->current().'?userId=' }}'+this.value)"  class="form-select form-select w-100 mt-2 mb-2 ">
                                                   @else
                                                   <select id="userId" name="userId"  class="form-select form-select w-100 mt-2 mb-2 userId">
                                                   @endif
                                                   <option value="0" selected disabled>{{__('labels.created_by_select') }}</option>

                                                   @foreach ($userDetail as $key => $userInfo)
                                                    <option value="{{ $userInfo->id }}" {{ !empty($userId) && ($userId == $userInfo->id)? ' selected' : ''}}> {{ $userInfo->fullName }}</option>
                                                    @endforeach

                                                </select>
                                                <label id="userId-error" class="error" for="userId" style="display: none;">Please select user name</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">Actor<span
                                                        class="text-danger">*</span> </label>
                                                <select id="userType" name="userType"
                                                    class="form-select form-select w-100 mt-2 mb-2 userType">
                                                    <option value="" selected disabled>Select Actor</option>
                                                    <option value="{{__('labels.dealer_filter')}}" {{
                                                        !empty($merchandiseDetail->
                                                        fType) && ($merchandiseDetail->fType ==
                                                        __('labels.dealer_filter'))
                                                        ? ' selected' : ''}}>{{ __('labels.dealer_filter') }}</option>
                                                    <option value="{{__('labels.engineer_filter')}}" {{
                                                        !empty($merchandiseDetail->
                                                        fType) && ($merchandiseDetail->fType ==
                                                        __('labels.engineer_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.engineer_filter') }}</option>
                                                    <option value="{{__('labels.architect_filter')}}" {{
                                                        !empty($merchandiseDetail->
                                                        fType) && ($merchandiseDetail->fType ==
                                                        __('labels.architect_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.architect_filter') }}</option>
                                                    <option value="{{__('labels.mason_filter')}}" {{
                                                        !empty($merchandiseDetail->
                                                        fType) && ($merchandiseDetail->fType ==
                                                        __('labels.mason_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.mason_filter') }}</option>
                                                    <option value="{{__('labels.contractor_filter')}}" {{
                                                        !empty($merchandiseDetail->
                                                        fType) && ($merchandiseDetail->fType ==
                                                        __('labels.contractor_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.contractor_filter') }}</option>
                                                    <option value="{{__('labels.construction_filter')}}" {{
                                                        !empty($merchandiseDetail->
                                                        fType) && ($merchandiseDetail->fType ==
                                                        __('labels.construction_filter'))
                                                        ? ' selected' : ''}}>{{
                                                        __('labels.construction_filter') }}</option>
                                                </select>
                                                <label id="userType-error" class="error" for="userType" style="display: none;">Please select form type</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{__('labels.dealer_name') }}<span
                                                        class="text-danger">*</span></label>
                                                <select id="rjDealerId"  name="rjDealerId"
                                                    class="form-control form-select mt-2 mb-2 search_place"
                                                    aria-invalid="false">
                                                    <option value="" selected >Select Dealer</option>

                                                    @if(!empty($merchandiseDetail))
                                                    @foreach ($dealerDetail as $key=>$values)
                                                         <option value="{{$values->id}}"
                                                         {{!empty($merchandiseDetail->rjDealerId) && ($merchandiseDetail->rjDealerId == $values->id) ? 'selected' : ''}}>{{$values->name}}</option>
                                                    @endforeach

                                                    @endif
                                                </select>
                                                <label id="rjDealerId-error" class="error" for="rjDealerId" style="display: none;">Please select dealer name</label>
                                            </div>
                                        </div>

                                        @if(empty($merchandiseDetail))
                                        <div class="col-md-6 dTypeDiv hideClass">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row dealerIdText"> </label>
                                                <input type="text" name="{{__('labels.id')}}" id="{{__('labels.id')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $merchandiseDetail->dealerId  ?? '' }}" minlength="4"
                                                    maxlength="40" aria-required="true" readonly>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-6 dTypeDiv {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row dealerIdText">{{$merchandiseDetail->fType}} ID</label>
                                                <label class="d-flex flex-column flex-sm-row dealerIdText"> </label>
                                                <input type="text" name="{{__('labels.id')}}" id="{{__('labels.id')}}"
                                                    class="form-control input-valid w-100 mt-2 mb-2"
                                                    value="{{  $merchandiseDetail->dealerId  ?? '' }}" minlength="4"
                                                    maxlength="40" aria-required="true" readonly>
                                            </div>
                                        </div>

                                        @endif


                                        <div class="col-md-12 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">

                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> Address  </label>
                                                <input type="text" name="mAddress" id="mAddress"
                                                    class="form-control input w-100 mt-2 mb-2"
                                                    value="{{  $merchandiseDetail->mAddress  ?? '' }}"
                                                    maxlength="255" placeholder="Please enter address "
                                                    aria-required="true">
                                            </div>

                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label
                                                    class="d-flex flex-column flex-sm-row">{{__('labels.state')}}</label>

                                                    <input type="hidden" name="mStateId" id="stateId" value="{{  $merchandiseDetail->mStateId  ?? '' }}" />

                                                    <input type="text" name="mSName" id="mSName"
                                                    class="form-control input w-100 mt-2 mb-2"
                                                    value="{{  $merchandiseDetail->mSName  ?? '' }}"
                                                    maxlength="255"
                                                    aria-required="true" readonly>


                                                {{-- <select id="stateId" name="mStateId"
                                                    class="form-control  mt-2 mb-2"
                                                    aria-invalid="false">
                                                    <option value="" >Select {{__('labels.state')}}
                                                    </option>
                                                    @foreach ($states as $key => $statesInfo)
                                                    <option value="{{ $statesInfo->id }}" {{
                                                        !empty($merchandiseDetail->mStateId) &&
                                                        ($merchandiseDetail->mStateId == $statesInfo->id)
                                                        ? ' selected' : ''}}> {{ $statesInfo->sName }}</option>
                                                    @endforeach
                                                </select>


                                                <label id="stateId-error" class="error" for="stateId"
                                                    style="display: none;">This field is required.</label> --}}
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">District</label>

                                                <input type="hidden" name="mCityId" id="mCityId" value="{{  $merchandiseDetail->mCityId  ?? '' }}"/>

                                                <input type="text" name="mCName" id="mCName"
                                                class="form-control input w-100 mt-2 mb-2"
                                                value="{{  $merchandiseDetail->mCName  ?? '' }}"
                                                maxlength="255"
                                                aria-required="true" readonly>


                                                {{-- <select id="cityId" name="mCityId"
                                                    class="form-control form-select mt-2 mb-2 select2Class"
                                                    aria-invalid="false">
                                                    <option value="" selected>Select District</option>

                                                    @if(isset($city))
                                                    @forelse ($city as $key => $values)

                                                    <option value="{{ $values->id }}" {{ !empty($merchandiseDetail->mCityId) &&
                                                        ($merchandiseDetail->mCityId == $values->id)
                                                        ? ' selected' : ''}}> {{ $values->cName }}</option>

                                                    @empty
                                                    @endforelse
                                                    @endif
                                                </select>
                                                <label id="cityId-error" class="error" for="cityId"
                                                    style="display: none;">This field is required.</label> --}}
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row">{{__('labels.taluka')}} </label>

                                                <input type="hidden" name="mTalukaId" id="mTalukaId"  value="{{  $merchandiseDetail->mTalukaId  ?? '' }}"/>

                                                <input type="text" name="mTName" id="mTName"
                                                class="form-control input w-100 mt-2 mb-2"
                                                value="{{  $merchandiseDetail->mTName  ?? '' }}"
                                                maxlength="255"
                                                aria-required="true" readonly>


                                                {{-- <select id="talukaId" name="mTalukaId"   class="form-control form-select mt-2 mb-2 select2Class"
                                                    aria-invalid="false">
                                                    <option value="" selected>Select {{__('labels.taluka')}}
                                                    </option>
                                                    @if(isset($talukas))
                                                    @forelse ($talukas as $key => $values)

                                                    <option value="{{ $values->id }}" {{ !empty($merchandiseDetail->mTalukaId) &&
                                                        ($merchandiseDetail->mTalukaId == $values->id)
                                                        ? ' selected' : ''}}> {{ $values->tName }}</option>
                                                    @empty
                                                    @endforelse
                                                    @endif

                                                </select>

                                                <label id="talukaId-error" class="error" for="talukaId"
                                                    style="display: none;">This field is required.</label> --}}
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> Pin Code  </label>
                                                <input type="text" name="mPinCode" id="mPinCode"
                                                    class="form-control input w-100 mt-2 mb-2 numeric"
                                                    value="{{  $merchandiseDetail->mPinCode  ?? '' }}" maxlength="20"
                                                    placeholder="Please enter pin code" aria-required="true" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row ml-1 mb-2">
                                                    Photo
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9 ml-1">
                                                    <input class="form-control dropify" type="file" value=""
                                                        id="mPhoto" name="mPhoto" tabindex="5"
                                                        placeholder="Please upload photo"
                                                        data-show-remove="false"
                                                        accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                                        data-allowed-file-extensions="jpg png jpeg gif"
                                                        data-default-file="{{ !empty($merchandiseDetail->mPhoto) ? $merchandiseDetail->mPhoto : ''  }}">
                                                    <label id="mPhoto-error" class="error" for="mPhoto"
                                                        style="display: none;">Please upload photo</label>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">

                                            <div class="col-md-12 mt-2 {{ !empty($merchandiseDetail) ? 'disableClass' : '' }}">
                                                <div class="input-form">
                                                    <label class="d-flex flex-column flex-sm-row"> {{ ucfirst($type) }} Item<span
                                                            class="text-danger">
                                                            *</span> </label>
                                                    <input type="text" name="itemNames" id="itemNames"
                                                        class="form-control input w-100 mt-2 mb-2 itemNames"
                                                        value="{{ !empty($merchandiseDetail->itemNames) ? $merchandiseDetail->itemNames : ''  }}" readonly
                                                        placeholder="Please give  {{ $type }}"
                                                        aria-required="true">
                                                </div>
                                            </div>

                                            {{-- Start Product Section --}}

                                            <div class="col-md-12 mt-2">

                                            @forelse ($getProducts as $key => $getProduct )

                                                @if($getProduct->isProductOption == 0 &&  !empty($getProduct->productOptions[0]))

                                                <table class="table table-reponsive table-bordered">
                                                    @php
                                                        // $totalQty = $getProduct->productOptions[0]['totalQty'] +  ($orderProduct[$getProduct->productOptions[0]['id']]['orderQty'] ?? 0);
                                                        $totalQty = $getProduct->productOptions[0]['totalQty'];
                                                    @endphp

                                                    <tbody>
                                                        <tr>
                                                            <td width="30%">
                                                                {{$getProduct['pName'] ?? ''}} {{ ' ('. $totalQty .')' ?? 0}}
                                                            </td>
                                                            <td width="70%">
                                                                <div class="d-flex flex-1">
                                                                    {{-- <button type="button" class="btn btn-secondary w-12 mr-1 minusBtn" data-id="{{$key.'_0'}}" data-product-array="{{$key}}">-</button> --}}
                                                                <div>
                                                                    <input id="pos-form-{{$key}}" type="text" class="form-control w-24 text-center inputOrder productClass_{{$key.'_0'}}  productClassArray_{{$key}} numeric"
                                                                    data-productOptionId="{{$getProduct->productOptions[0]['id']}}" data-productId="{{$getProduct->productOptions[0]['productId']}}"
                                                                    data-maxQty="{{ ($type == 'gift') ? $totalQty : 5000000 }}"
                                                                    max="{{ ($type == 'gift') ? $totalQty : 5000000 }}"
                                                                    maxlength="7"
                                                                    data-productName = "{{$getProduct['pName'] ?? ''}}"
                                                                    {{-- readonly --}}
                                                                    value="{{!empty($merchandiseDetail) ? $orderProduct[$getProduct->productOptions[0]['id']]['orderQty'] ?? 0 : 0 }}"
                                                                    name="productOptions[{{$getProduct->productOptions[0]['id']}}]"
                                                                    placeholder="Item quantity" value="0" data-id="{{$key.'_0'}}" data-product-array="{{$key}}">

                                                                    {{-- <button type="button" class="btn btn-secondary w-12 ml-1 plusBtn" data-id="{{$key.'_0'}}" data-product-array="{{$key}}">+</button> --}}
                                                                </div>

                                                                @if( $getProduct->productOptions[0]['isDescription'] == 1)
                                                                {{-- {{!empty($merchandiseDetail) ? $orderProduct[$getProduct->productOptions[0]['id']]['orderDesc'] ?? $orderProduct[$getProduct->productOptions[0]['id']]['orderDesc'] : '' }} --}}
                                                                @php

                                                                     $desc = "";
                                                                     if(!empty($merchandiseDetail) && isset($orderProduct[$getProduct->productOptions[0]['id']]['orderDesc']))
                                                                     {
                                                                        $desc = $orderProduct[$getProduct->productOptions[0]['id']]['orderDesc'];
                                                                     }

                                                                    // !empty($merchandiseDetail) ? $orderProduct[$getProduct->productOptions[0]['id']]['orderDesc'] ?? $orderProduct[$getProduct->productOptions[0]['id']]['orderDesc'] : '' }}
                                                                @endphp

                                                                    <div class="flex-1 ml-5">
                                                                        <input class="form-control input w-100" type="text" name="desc[{{$getProduct->productOptions[0]['id']}}]" maxlength="100"
                                                                        value="{{ $desc }}"

                                                                        placeholder="Please enter description" style="border: 1px solid black"/>
                                                                    </div>
                                                                    @endif

                                                                </div>

                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                                @endif

                                                @if($getProduct->isProductOption == 1 && !empty($getProduct->productOptions[0]))
                                                <table class="table table-reponsive table-bordered">
                                                    <tr>
                                                        <td colspan="2">
                                                            {{$getProduct['pName'] ?? ''}}
                                                        </td>
                                                    </tr>

                                                    @forelse ($getProduct->productOptions  as  $key1 => $productOption)

                                                    @php
                                                    // $totalQty1 = $productOption['totalQty'] +  ($orderProduct[$productOption['id']]['orderQty'] ?? 0);
                                                    $totalQty1 = $productOption['totalQty'] ;
                                                @endphp

                                                    <tr>
                                                        <td width="30%">
                                                            {{$productOption['productOptionName'] ?? ''}} {{ ' ('. $totalQty1 .')' ?? 0}}
                                                        </td>
                                                        <td width="70%">
                                                            <div class="d-flex flex-1">
                                                                {{-- <button type="button" class="btn btn-secondary w-12 mr-1 minusBtn" data-id="{{$key.$key1}}"  data-product-array="{{$key}}">-</button> --}}
                                                                <div>
                                                                    <input id="pos1-form-{{$key1}}" type="text" class="form-control w-24 inputOrder text-center productClass_{{$key.$key1}} productClassArray_{{$key}} numeric"
                                                                    data-productOptionId="{{$productOption['id']}}" data-productId="{{$productOption['productId']}}"
                                                                    data-maxQty="{{ ($type == 'gift') ? $totalQty1 : 5000000 }} "
                                                                    max="{{ ($type == 'gift') ? $totalQty1 : 5000000 }}"
                                                                    maxlength="7"
                                                                    data-productName = "{{$getProduct['pName'] ?? ''}}"
                                                                    name="productOptions[{{$productOption['id']}}]"
                                                                    {{-- readonly --}}
                                                                    value="{{!empty($merchandiseDetail) ? $orderProduct[$productOption['id']]['orderQty'] ?? 0 : 0 }}"
                                                                    data-id="{{$key.$key1}}" data-product-array="{{$key}}"
                                                                    placeholder="Item quantity" value="0">
                                                                    {{-- <button type="button" class="btn btn-secondary w-12 ml-1 plusBtn" data-id="{{$key.$key1}}" data-product-array="{{$key}}">+</button> --}}
                                                                </div>
                                                                @if($productOption['isDescription'] == 1)
                                                                <div class="flex-1 ml-5">
                                                                    <input class="form-control input w-100" type="text" name="desc[{{$productOption['id']}}]" maxlength="100" value="{{!empty($merchandiseDetail) ? $orderProduct[$productOption['id']]['orderDesc'] ?? '' : "" }}"  placeholder="Please enter description" style="border: 1px solid black"/>
                                                                </div>
                                                                    @endif
                                                            </div>

                                                        </td>
                                                    </tr>

                                                    @empty
                                                    @endforelse
                                                </table>
                                                @endif
                                            @empty
                                                <h6>Product Not Available</h6>
                                            @endforelse

                                            </div>
                                            {{-- End Product Section --}}

                                        </div>

                                    </div>

                                    <div class="text-right">
                                        <button type="{{__('labels.submit')}}"
                                            class="submit btn btn-primary text-white mt-5">{{__('labels.submit')}}</button>
                                        <a href="{{ route('merchandise.index',['type' => $type]) }}" class="btn btn-danger text-white mt-5">
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
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {


    var productNameArray = [];
    var itemTypeArray = $('#itemTypeArray').val();


    if(itemTypeArray)
    {
        productNameArray = itemTypeArray.split(",");
    }

        Array.prototype.remove = function() {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };

        function getItemText()
        {

            if(productNameArray.length > 0)
            {
                $('.itemNames').val(productNameArray.toString());

            }else{
                $('.itemNames').val('');
            }
        }

        function getNum(val) {
        if (isNaN(val)) {
            return 0;
        }
        return val;
        }




        $(document).on('keyup','.inputOrder',function(){

            let keyId =  $(this).attr('data-id');
            let getProductQty = parseInt($(`.productClass_${keyId}`).val());


            let getMaxQty = $(`.productClass_${keyId}`).attr('data-maxQty');


            let productName = $(`.productClass_${keyId}`).attr('data-productName');

            let mainProductKey =  $(this).attr('data-product-array');

            // console.log(mainProductKey);

            if(getProductQty > 0)
            {
                productNameArray.push(productName);
                productNameArray = [...new Set(productNameArray)];
            }else{
                // productNameArray.remove(productName);

                 var sum = 0;
                 var sumNum = 0;
                 var sumarray = [];

                    $(`.productClassArray_${mainProductKey}`).each(function() {
                            sumNum = getNum($(this).val());
                            sumarray.push(parseInt(sumNum));
                            // sum += parseInt($(this).val());
                    });

                    const sumarray1 = sumarray.filter(function (value) {
                        return !Number.isNaN(value);
                    });

                    if(sumarray1.length > 0)
                    {
                        sum = sumarray1.reduce((v, i) => (v + i));
                    }

                    if(sum == 0)
                    {
                        productNameArray.remove(productName);
                    }
            }

            getItemText();

            });

        $(document).on('click','.minusBtn',function(){

                let keyId =  $(this).attr('data-id');
                let getProductQty = parseInt($(`.productClass_${keyId}`).val());
                let getMaxQty = $(`.productClass_${keyId}`).attr('data-maxQty');
                let productName = $(`.productClass_${keyId}`).attr('data-productName');
                let mainProductKey =  $(this).attr('data-product-array');


                if(getProductQty != 0)
                {
                    $(`.productClass_${keyId}`).val(parseInt(getProductQty) - 1);


                    var sum = 0;
                    $(`.productClassArray_${mainProductKey}`).each(function() {
                        sum += parseInt($(this).val());
                        console.log(sum);
                    });

                    if(sum == 0)
                    {
                        productNameArray.remove(productName);
                    }
                    // console.log(productNameArray);
                }
                getItemText();
            });

        $(document).on('click','.plusBtn',function(){

                let keyId =  $(this).attr('data-id');
                let getProductQty = parseInt($(`.productClass_${keyId}`).val());
                let getMaxQty = $(`.productClass_${keyId}`).attr('data-maxQty');
                let productName = $(`.productClass_${keyId}`).attr('data-productName');

                let mainProductKey =  $(this).attr('data-product-array');

                if(getMaxQty > 0)
                {
                    productNameArray.push(productName);
                    productNameArray = [...new Set(productNameArray)];
                }

                // console.log(productNameArray);

                if(getProductQty < getMaxQty)
                {
                    $(`.productClass_${keyId}`).val(parseInt(getProductQty) + 1);
                }
                getItemText();

        });


        $(".createSchedule").validate({
            rules: {
                'userId': {
                    required: true,
                },
                'userType': {
                    required: true,
                },
                'rjDealerId': {
                    required: true,
                },
                // 'mPhoto': {
                //     required: true,
                // },

                // @if(empty($merchandiseDetail->id))
                // 'mPhoto': {
                //     required: true,
                // },
                // @endif

                'itemNames': {
                    required: true,
                },
            },

            messages: {

                'userId': {
                    required: 'Please select executive',
                },
                'userType': {
                    required: 'Please select actor',
                },
                'rjDealerId': {
                    required: 'Please select dealer name',
                },
                'mPhoto': {
                    required: 'Please upload photo',
                },

                "itemNames": {
                    required: 'Please give {{ $type }} ',
                },

            },
            submitHandler: function(form) {
                form.submit();
            }
        });


        $( ".datepickerdate" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

        $('.timePic').timepicker();

        $("#rjDealerId").attr("disabled", true);

        $('.dropify').dropify();

        // $('#userType').on('change', function() {

            $(document).on('change', '.userType,.userId', function() {

                var userType = $('#userType').val();
                var userId = $('#userId').val();



            if(userType && userId)
             {
                $("#rjDealerId").html('');
                var ajaxUrl = '{{ route('getDealerByType') }}';
                $.ajax({
                    url: ajaxUrl,
                    type: "POST",
                    data: {
                    userType: userType,
                    userId: userId,
                    _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result){
                        $("#rjDealerId").attr("disabled", false);
                        $('#rjDealerId').html('<option value="">Select Dealer Name</option>');
                        $.each(result.dName,function(key,value){
                        $("#rjDealerId").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
             }

        });

        $('#rjDealerId').on('change', function() {
            var dealerIdVal = this.value;

            var text = $( "#userType option:selected" ).text();

            $('.dTypeDiv').removeClass('hideClass');
            $('.dealerIdText').text(`${text} ID`);

            var ajaxUrl = '{{ route('getDealerDetail') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                dealerIdVal: dealerIdVal,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){

                    console.log(result);

                    document.getElementById("ID").value = result.dealerId;

                    $('#stateId').val(result.stateId);
                    $('#mSName').val(result.sName);

                    $('#mCityId').val(result.cityId);
                    $('#mCName').val(result.cName);

                    $('#mTalukaId').val(result.talukaId);
                    $('#mTName').val(result.tName);

                    $('#mPinCode').val(result.pinCode);


                    // document.getElementById("stateId").value = result.stateId;

                    // document.getElementById("mStateId").text = result.sName;

                }
            });
        });

        //  $('#stateId').on('change', function() {
        //         var s_name = this.value;
        //         $("#cityId").html('');
        //         $("#talukaId").html('');
        //         var ajaxUrl = '{{ route('get-city') }}';
        //         $.ajax({
        //             url: ajaxUrl,
        //             type: "POST",
        //             data: {
        //             s_name: s_name,
        //             _token: '{{csrf_token()}}'
        //             },
        //             dataType : 'json',
        //             success: function(result){
        //                 $("#cityId").attr("disabled", false);
        //                 $('#cityId').html('<option value="">Select City</option>');
        //                 $('#talukaId').html('<option value="">Select Taluka</option>');
        //                 $.each(result.city,function(key,value){
        //                 $("#cityId").append('<option value="'+value.id+'">'+value.cName+'</option>');
        //                 });
        //             }
        //         });
        // });

        // $('#cityId').on('change', function() {

        //     var cityId = this.value;

        //     $("#talukaId").html('');
        //     var ajaxUrl = '{{ route('get-taluka') }}';
        //     $.ajax({
        //         url: ajaxUrl,
        //         type: "POST",
        //         data: {
        //          cityId: cityId,
        //         _token: '{{csrf_token()}}'
        //         },
        //         dataType : 'json',
        //         success: function(result){
        //             $("#talukaId").attr("disabled", false);
        //             $('#talukaId').html('<option value="">Select Taluka</option>');
        //             $.each(result.taluka,function(key,value){
        //             $("#talukaId").append('<option value="'+value.id+'">'+value.tName+'</option>');
        //             });
        //         }
        //     });
        // });

    });

</script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $( function() {
    $("select").select2({});
    });
</script>

@endsection
