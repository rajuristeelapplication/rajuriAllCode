@extends('admin.layout.index')
@section('css')
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">


<style>
    .hideClass {
        display: none;
    }

    .select2-container {
        width: 100% !important;
    }

</style>

@endsection
@section('title') {{__('labels.dealer') }} {{ $dealerDetail ? 'Edit' : 'Create' }} @endsection
@section('content')
<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation')}}</span></a>
        <i class="
                    feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('dealers.index',['type' => $type]) }}"><span class="">{{__('labels.dealer') }}</span></a>
        <i class="
                            feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active"> {{__('labels.dealer') }} {{ empty($dealerDetail) ? 'Create' : 'Edit' }}
        </span>
    </div>
    @endsection
    @include('admin.common.notification')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid grid-cols-12 gap-6">
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{__('labels.dealer') }} {{ $dealerDetail ? 'Edit' : 'Create' }} </h2>
                </div>
                <div class="mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: Form Validation -->
                        <div class="intro-y box">

                            <div class="p-5">
                                <div class="preview">
                                    @include('admin.common.flash')

                                    @if (empty($dealerDetail))
                                    <form class="form" name="glossaryForm" id="glossaryForm" method="post"
                                        enctype="multipart/form-data" action="{{ route('dealers.store') }}">
                                        @else
                                        <form class="form" name="glossaryForm" id="glossaryForm" method="post"
                                            enctype="multipart/form-data" action="{{ route('dealers.store') }}">
                                            @endif
                                            {{ csrf_field() }}

                                            <input type="hidden" name="id" id="id"
                                                value="{{ !empty($dealerDetail)? $dealerDetail->id : '' }}" />

                                                <input type="hidden" name="type" value="{{ $type }}" >

                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{ __('labels.executive') }}
                                                    <span class="text-danger">*</span> </label>
                                                <select id="userId" name="userId"
                                                    class="form-select form-select mt-2 userId select2Class">
                                                    <option value="">Select User</option>
                                                    @forelse ($users as $key=>$user)
                                                    <option value="{{ $user->id}}" {{ !empty($dealerDetail->userId) &&
                                                        ($dealerDetail->userId == $user->id)
                                                        ? ' selected' : ''}}
                                                        >{{ $user->fullName }}</option>
                                                    @empty

                                                    @endforelse
                                                </select>

                                                <label id="userId-error" class="error" for="userId"
                                                    style="display: none">This field is required.</label>
                                            </div>

                                            <br>

                                            <div class="row">

                                                @if($type == 2)

                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.form_type')}} <span class="text-danger">*</span>
                                                        </label>
                                                        <select id="fType" name="fType"
                                                            class="form-select form-select mt-2 fType select2Class">
                                                            <option value="">{{__('labels.select_form')}}</option>


                                                            {{-- <option value="{{__('labels.dealer_filter')}}"
                                                                {{!empty($dealerDetail->fType) && ($dealerDetail->fType
                                                                == __('labels.dealer_filter')) ? ' selected' : ''}} >
                                                                {{__('labels.dealer_filter') }}</option> --}}

                                                            <option value="{{__('labels.engineer_filter')}}"
                                                                {{!empty($dealerDetail->fType) && ($dealerDetail->fType
                                                                == __('labels.engineer_filter')) ? ' selected' : ''}}>
                                                                {{__('labels.engineer_filter') }}</option>
                                                            <option value="{{__('labels.architect_filter')}}"
                                                                {{!empty($dealerDetail->fType) && ($dealerDetail->fType
                                                                == __('labels.architect_filter')) ? ' selected' : ''}}>
                                                                {{__('labels.architect_filter') }}</option>
                                                            <option value="{{__('labels.mason_filter')}}"
                                                                {{!empty($dealerDetail->fType) && ($dealerDetail->fType
                                                                == __('labels.mason_filter')) ? ' selected' : ''}}>
                                                                {{__('labels.mason_filter') }}</option>
                                                            <option value="{{__('labels.contractor_filter')}}"
                                                                {{!empty($dealerDetail->fType) && ($dealerDetail->fType
                                                                == __('labels.contractor_filter')) ? ' selected' : ''}}>
                                                                {{__('labels.contractor_filter') }}</option>
                                                            <option value="{{__('labels.construction_filter')}}"
                                                                {{!empty($dealerDetail->fType) && ($dealerDetail->fType
                                                                == __('labels.construction_filter')) ? ' selected' :
                                                                ''}}>
                                                                {{__('labels.construction_filter') }}</option>


                                                        </select>
                                                        <label id="fType-error" class="error" for="fType"
                                                            style="display: none">This field is required.</label>
                                                    </div>
                                                </div>

                                                @else
                                                    <input type="hidden" name="fType" value="{{__('labels.dealer_filter')}}" />
                                                @endif

                                                <br>

                                                @if($type == 1)
                                                <div class="col-md-6 dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}">
                                                    <div class="input-form ">
                                                        <label class="d-flex flex-column flex-sm-row">
                                                            Actor Type <span
                                                                class="text-danger">*</span> </label>
                                                        <select id="dType" name="dType"
                                                            class="form-select form-select mt-2 dType select2Class">
                                                            <option value="">{{__('labels.select_dealer_type') }}</option>
                                                            <option value="Main Dealer" {{!empty($dealerDetail->dType) && ($dealerDetail->dType == "Main Dealer") ? ' selected' : ''}}>
                                                                {{__('labels.main_dealer') }}</option>
                                                            <option value="Sub Dealer" {{!empty($dealerDetail->dType) && ($dealerDetail->dType == "Sub Dealer") ? ' selected' : ''}}>
                                                                {{__('labels.sub_dealer') }}</option>
                                                        </select>
                                                        <label id="dType-error" class="error" for="dType"
                                                            style="display: none">This field is required.</label>
                                                    </div>
                                                </div>
                                                @endif

                                                <br>
                                                @if($type == 2)
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.date') }} </label>
                                                        <input type="text" name="date" id="date"
                                                            class="form-control input-valid w-100 mt-2 mb-2 datepickerdate1"
                                                            value="{{  $dealerDetail->date  ?? '' }}" minlength="4"
                                                            maxlength="40" placeholder="please select date "
                                                            aria-required="true" readonly>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.time')}}
                                                            </label>
                                                        <input type="text" name="time"
                                                            id="time"
                                                            class="form-control input-valid w-100 mt-2 mb-2 timePic"
                                                            value="{{ !empty($dealerDetail->
                                                                time) ? Carbon\Carbon::parse($dealerDetail->time)->format(config('constant.admin_dealer_time_format_create')) : '' }}"
                                                            minlength="4" maxlength="40"
                                                            placeholder="Please Select {{__('labels.time')}}"
                                                            aria-required="true" readonly>
                                                    </div>
                                                </div>

                                                @endif


                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> Name  <span
                                                                class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" name="name" id="name"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->name  ?? '' }}" maxlength="100"
                                                            placeholder="Please enter  name" aria-required="true">
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row dealerIdText">
                                                            {{!empty($dealerDetail->fType) ? $dealerDetail->fType . ' ID' : 'ID'}}
                                                             </label>
                                                        <input type="text" name="dealerId" id="dealerId"
                                                            class="form-control input w-100 mt-2 mb-2" readonly
                                                            value="{{  !empty($dealerDetail->dealerId) ? $dealerDetail->dealerId : $randomNumber    }}"
                                                            maxlength="100" placeholder="Please enter dealer id"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.firm_name')}}  <span
                                                                class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" name="firmName" id="firmName"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->firmName  ?? '' }}"
                                                            maxlength="100" placeholder="Please enter firm name"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.location')}}  <span
                                                                class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" name="location" id="location"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->location  ?? '' }}"
                                                            maxlength="255" placeholder="Please enter location"
                                                            aria-required="true">
                                                    </div>

                                                    <input type="hidden" id="latitude" name="latitude"   value="{{  $dealerDetail->latitude  ?? '' }}" class="form-control">
                                                    <input type="hidden" name="longitude" id="longitude"   value="{{  $dealerDetail->longitude  ?? '' }}" class="form-control">
                                                </div>

                                                <div class="col-md-12">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row">  {{__('labels.address1')}}  <span
                                                                class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" name="address1" id="address1"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->address1  ?? '' }}"
                                                            maxlength="100" placeholder="Please enter address 1"
                                                            aria-required="true">
                                                    </div>

                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.address2')}}  <span
                                                                class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" name="address2" id="address2"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->address2  ?? '' }}"
                                                            maxlength="100" placeholder="Please enter address 2"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label
                                                            class="d-flex flex-column flex-sm-row">{{__('labels.state')}}<span
                                                                class="text-danger">*</span></label>
                                                        <select id="stateId" name="stateId"
                                                            class="form-control form-select mt-2 mb-2 select2Class"
                                                            aria-invalid="false">
                                                            <option value="" selected>Select {{__('labels.state')}}
                                                            </option>
                                                            @foreach ($states as $key => $statesInfo)
                                                            <option value="{{ $statesInfo->id }}" {{
                                                                !empty($dealerDetail->stateId) &&
                                                                ($dealerDetail->stateId == $statesInfo->id)
                                                                ? ' selected' : ''}}> {{ $statesInfo->sName }}</option>
                                                            @endforeach
                                                        </select>


                                                        <label id="stateId-error" class="error" for="stateId"
                                                            style="display: none;">This field is required.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row">{{__('labels.district_name')}} <span
                                                                class="text-danger">*</span></label>
                                                        <select id="cityId" name="cityId"
                                                            class="form-control form-select mt-2 mb-2 select2Class"
                                                            aria-invalid="false">
                                                            <option value="" selected>Select District</option>

                                                            @if(isset($city))
                                                            @forelse ($city as $key => $values)

                                                            <option value="{{ $values->id }}" {{ !empty($dealerDetail->
                                                                cityId) &&
                                                                ($dealerDetail->cityId == $values->id)
                                                                ? ' selected' : ''}}> {{ $values->cName }}</option>

                                                            @empty

                                                            @endforelse

                                                            @endif




                                                        </select>

                                                        <label id="cityId-error" class="error" for="cityId"
                                                            style="display: none;">This field is required.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label
                                                            class="d-flex flex-column flex-sm-row">{{__('labels.taluka')}}<span
                                                                class="text-danger">*</span></label>
                                                        <select id="talukaId" name="talukaId"
                                                            class="form-control form-select mt-2 mb-2 select2Class"
                                                            aria-invalid="false">
                                                            <option value="" selected>Select {{__('labels.taluka')}}
                                                            </option>
                                                            @if(isset($talukas))
                                                            @forelse ($talukas as $key => $values)

                                                            <option value="{{ $values->id }}" {{ !empty($dealerDetail->
                                                                talukaId) &&
                                                                ($dealerDetail->talukaId == $values->id)
                                                                ? ' selected' : ''}}> {{ $values->tName }}</option>

                                                            @empty

                                                            @endforelse
                                                            @endif

                                                        </select>

                                                        <label id="talukaId-error" class="error" for="talukaId"
                                                            style="display: none;">This field is required.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.pin_code')}} <span
                                                                class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" name="pinCode" id="pinCode"
                                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                                            value="{{  $dealerDetail->pinCode  ?? '' }}" maxlength="6"
                                                            placeholder="Please enter pin code" aria-required="true">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="input-form">
                                                <label class="d-flex flex-column flex-sm-row"> {{ __('labels.region') }}
                                                    <span class="text-danger">
                                                        *</span> </label>
                                                <select id="regionId" name="regionId"
                                                    class="form-select form-select mt-2 dType select2Class">
                                                    <option value="">Select {{__('labels.region')}}</option>

                                                    @forelse ($regions as $key=> $region)
                                                    <option value="{{ $region->id }}" {{ !empty($dealerDetail->regionId)
                                                        &&
                                                        ($dealerDetail->regionId == $region->id)
                                                        ? ' selected' : ''}}> {{ $region->rName }}</option>

                                                    @empty

                                                    @endforelse
                                                </select>
                                                <label id="regionId-error" class="error" for="regionId"
                                                    style="display: none;">This field is required.</label>
                                            </div>

                                            <br>


                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.wt_mobile_no')}}  <span class="text-danger">
                                                                *</span> </label>
                                                        <input type="text" name="wpMobileNumber" id="wpMobileNumber"
                                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                                            minlength="10" maxlength="10"
                                                            value="{{  $dealerDetail->wpMobileNumber  ?? '' }}"
                                                            placeholder="Please enter whatsapp mobile number"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.mobile_no')}}
                                                        </label>
                                                        <input type="text" name="mobileNumber" id="mobileNumber"
                                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                                            minlength="10" maxlength="10"
                                                            value="{{  $dealerDetail->mobileNumber  ?? '' }}"
                                                            placeholder="Please enter mobile number"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row">{{__('labels.email')}}   </label>
                                                        <input type="text" name="email" id="email"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->email  ?? '' }}" maxlength="100"
                                                            placeholder="Please enter email" aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.dob')}}
                                                        @if(!empty($dealerDetail->fType) &&  $dealerDetail->fType == "Dealer")
                                                        <span class="text-danger photoString"> * </span>
                                                        @endif
                                                        @if(empty($dealerDetail->fType))
                                                        <span class="text-danger photoString"> * </span>
                                                        @endif
                                                    </label>
                                                        <input type="text" name="dob" id="dob"
                                                            class="form-control input-valid w-100 mt-2 mb-2 datepickerdate dob"
                                                            value="{{  $dealerDetail->dob  ?? '' }}" minlength="4"
                                                            maxlength="40" placeholder="please select Date Of Birth"
                                                            aria-required="true" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row ml-1 mb-2"> {{__('labels.dealer_photo')}}

                                                            @if(!empty($dealerDetail->fType) &&  $dealerDetail->fType == "Dealer")
                                                            <span class="text-danger photoString"> * </span>
                                                            @endif
                                                            @if(empty($dealerDetail->fType))
                                                            <span class="text-danger photoString"> * </span>
                                                            @endif
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9 ml-1">
                                                            <input class="form-control dropify" type="file" value=""
                                                                id="photo" name="photo" tabindex="5"
                                                                placeholder="Please upload photo"
                                                                data-show-remove="false"
                                                                accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                                                data-allowed-file-extensions="jpg png jpeg gif"
                                                                data-default-file="{{ !empty($dealerDetail->photo) ? $dealerDetail->photo : ''  }}">
                                                            <label id="photo-error" class="error" for="photo"
                                                                style="display: none;">Please upload photo</label>

                                                        </div>
                                                    </div>
                                                </div>

                                                @if($type == 1)

                                                <div class="col-md-6 dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row ml-1 mb-2">{{__('labels.shop_photo')}} <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9 ml-1">
                                                            <input class="form-control dropify" type="file" value=""
                                                                id="shopPhoto" name="shopPhoto" tabindex="5"
                                                                placeholder="Please upload photo"
                                                                data-show-remove="false"
                                                                accept="image/x-png,image/jpg,image/gif,image/jpeg"
                                                                data-allowed-file-extensions="jpg png jpeg gif"
                                                                data-default-file="{{ !empty($dealerDetail->shopPhoto) ? $dealerDetail->shopPhoto : ''  }}">
                                                            <label id="shopPhoto-error" class="error" for="shopPhoto"
                                                                style="display: none;">Please upload shop photo</label>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6 dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}" style="margin-top: 10px;">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row">{{__('labels.year_of_inco')}}</label>
                                                        <input type="text" name="yearOfIncorporation"
                                                            id="yearOfIncorporation"
                                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                                            value="{{  $dealerDetail->yearOfIncorporation  ?? '' }}"
                                                            maxlength="4"
                                                            placeholder="Please enter year of incorporation"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}" style="margin-top: 10px;">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.aadhar_no')}}
                                                        </label>
                                                        <input type="text" name="aadharNo" id="aadharNo"
                                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                                            value="{{  $dealerDetail->aadharNo  ?? '' }}"
                                                            maxlength="12" placeholder="Please enter aadhar no"
                                                            aria-required="true">
                                                            <p style="color: gray;">Format :- (999966660000)</p>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="input-form dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}">
                                                <label class="d-flex flex-column flex-sm-row">{{__('labels.prof_family_detail')}}</label>
                                                <textarea type="text" name="familyDetails" maxlength="250" id="familyDetails" class="form-control input w-100 mt-2 mb-2" rows="5" value="{{  $dealerDetail->familyDetails  ?? '' }}"placeholder="Please enter family details" aria-required="true"> {{  $dealerDetail->familyDetails  ?? '' }}</textarea>
                                            </div>

                                            @endif


                                            <h4 class="mt-8 mb-3"> {{__('labels.material_status') }} </h4>

                                            <div class="row">

                                                @if($type == 2)

                                                <div class="col-md-6 oTypeDiv">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.use_till_now') }}</label>
                                                        <input type="text" name="msUtillNow"
                                                            id="msUtillNow"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->msUtillNow  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter material used till now"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.ms_requried_material') }} </label>
                                                        <input type="text" name="msRequriedMaterial"
                                                            id="msRequriedMaterial"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->msRequriedMaterial  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter required material"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                @if($type == 2)

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.completed_project') }} </label>
                                                        <input type="text" name="msCompletedProject"
                                                            id="msCompletedProject"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->msCompletedProject  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter material completed project"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.ongoing_project') }}   </label>
                                                        <input type="text" name="msOnGoingProject"
                                                            id="msOnGoingProject"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->msOnGoingProject  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter material ongoing project"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.ms_year_of_inco') }}
                                                        </label>
                                                        <input type="text" name="msYearOfIncorporation"
                                                            id="msYearOfIncorporation"
                                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                                            value="{{  $dealerDetail->msYearOfIncorporation  ?? '' }}"
                                                            maxlength="4" placeholder="Please enter material year of incorporation"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                @endif

                                            </div>


                                            <h4 class="mt-8 mb-3"> {{__('labels.project_info') }} </h4>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.type_of_site') }}
                                                        </label>
                                                        <input type="text" name="piTypeOfSite" id="piTypeOfSite"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->piTypeOfSite  ?? '' }}"
                                                            maxlength="50"
                                                            placeholder="Please enter project type of site"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.status_of_site') }}
                                                        </label>
                                                        <input type="text" name="piStatusOfSite" id="piStatusOfSite"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->piStatusOfSite  ?? '' }}"
                                                            maxlength="50"
                                                            placeholder="Please enter project status of site"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.area') }} </label>
                                                        <input type="text" name="piArea" id="piArea"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->piArea  ?? '' }}" maxlength="50"
                                                            placeholder="Please enter project area"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.estimated_cost') }}
                                                        </label>
                                                        <input type="text" name="piEstimateCost" id="piEstimateCost"
                                                            class="form-control input w-100 mt-2 mb-2 decimalOnly numeric"
                                                            value="{{  $dealerDetail->piEstimateCost  ?? '' }}"
                                                            maxlength="9"
                                                            placeholder="Please enter project estimated cost"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.project_engineer') }}
                                                        </label>
                                                        <input type="text" name="piProjectEngineer"
                                                            id="piProjectEngineer"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->piProjectEngineer  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter project engineer"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.architect') }}
                                                        </label>
                                                        <input type="text" name="piArchitect" id="piArchitect"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->piArchitect  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter project architect"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.executor') }}  </label>
                                                        <input type="text" name="piExecutor" id="piExecutor"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->piExecutor  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter project executor"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Company Details --}}

                                            <h4 class="mt-8 mb-3 dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}"> Company Details </h4>

                                            <div class="row dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}">
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.firm_reg_no') }}

                                                        </label>
                                                        <input type="text" name="cdFirmRegistrationNumber"
                                                            id="cdFirmRegistrationNumber"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->cdFirmRegistrationNumber  ?? '' }}"
                                                            maxlength="50"
                                                            placeholder="Please enter company firm registration number"
                                                            aria-required="true">
                                                            <p style="color: gray;">Format :- (U72200MH2009PLC123456)</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.shop_act_no') }}
                                                        </label>
                                                        <input type="text" name="cdShopActLicenceNumber"
                                                            id="cdShopActLicenceNumber"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->cdShopActLicenceNumber  ?? '' }}"
                                                            maxlength="50"
                                                            placeholder="Please enter company licence number"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.gst_no') }}
                                                        </label>
                                                        <input type="text" name="cdGstNumber" id="cdGstNumber"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->cdGstNumber  ?? '' }}"
                                                            maxlength="50"
                                                            placeholder="Please enter company gst number"
                                                            aria-required="true">
                                                            <p style="color: gray;">Format :- (27AAPFU0939F1ZV)</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.pan') }}  </label>
                                                        <input type="text" name="cdPan" id="cdPan"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->cdPan  ?? '' }}" maxlength="50"
                                                            placeholder="Please enter pan"
                                                            aria-required="true">
                                                            <p style="color: gray;">Format :- (ABCTY1234D)</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row">{{__('labels.firm_type') }}
                                                        </label>
                                                        <input type="text" name="cdFirmType" id="cdFirmType"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->cdFirmType  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter firm type"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.cin') }}  </label>
                                                        <input type="text" name="cdCin" id="cdCin"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->cdCin  ?? '' }}" maxlength="50"
                                                            placeholder="Please enter cin"
                                                            aria-required="true">
                                                            <p style="color: gray;">Format :- (L21091KA2019OPC413311)</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.shop_warehouse_area') }}
                                                        </label>
                                                        <input type="text" name="cdShopWarehouseArea"
                                                            id="cdShopWarehouseArea"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->cdShopWarehouseArea  ?? '' }}"
                                                            maxlength="255" placeholder="Please enter shop & warehouse area"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                            </div>


                                            <h4 class="mt-8 mb-3 dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}"> Bank Details </h4>

                                            <div class="row dTypeDiv {{!empty($dealerDetail->fType) && ($dealerDetail->fType != __('labels.dealer_filter')) ? 'hideClass' : ''}}">
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.mode_of_pay') }}
                                                        </label>
                                                        <input type="text" name="bdModeOfPayment" id="bdModeOfPayment"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->bdModeOfPayment  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter mode of payment"
                                                            aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.bank_name') }}
                                                        </label>
                                                        <input type="text" name="bdBankName" id="bdBankName"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->bdBankName  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter bank name"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.address_of_bank') }}
                                                        </label>
                                                        <input type="text" name="bdBankAddress" id="bdBankAddress"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->bdBankAddress  ?? '' }}"
                                                            maxlength="255" placeholder="Please enter address of bank"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row"> {{__('labels.acc_no') }}
                                                        </label>
                                                        <input type="text" name="bdAccountNumber" id="bdAccountNumber"
                                                            class="form-control input w-100 mt-2 mb-2 numeric"
                                                            value="{{  $dealerDetail->bdAccountNumber  ?? '' }}"
                                                            maxlength="50"
                                                            placeholder="Please enter bank account number"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row">  {{__('labels.ifsc_code') }}
                                                        </label>
                                                        <input type="text" name="bdIfscCode" id="bdIfscCode"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->bdIfscCode  ?? '' }}"
                                                            maxlength="50" placeholder="Please enter bank ifsc code"
                                                            aria-required="true">

                                                            <p style="color: gray;">Format :- (XXXX0YYYYYY)</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="input-form">
                                                        <label class="d-flex flex-column flex-sm-row">  {{__('labels.nature_of_account') }}
                                                        </label>
                                                        <input type="text" name="bdNatureOfAccount"
                                                            id="bdNatureOfAccount"
                                                            class="form-control input w-100 mt-2 mb-2"
                                                            value="{{  $dealerDetail->bdNatureOfAccount  ?? '' }}"
                                                            maxlength="50"
                                                            placeholder="Please enter bank nature of account"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="text-right">
                                                <button type="submit"
                                                    class="submit btn btn-primary text-white mt-5">{{__('labels.submit') }}</button>
                                                <a href="{{ route('dealers.index',['type' => $type]) }}"
                                                    class="btn btn-danger text-white mt-5"> {{__('labels.back_info') }}</a>
                                            </div>
                                        </form>
                                </div>
                            </div>
                        </div>
                        <!-- END: Form Validation -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content -->
@endsection
@section('js')
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBWF9wFs2DV5_Ywpme7CSJg4F1wWOg0yK4"></script> --}}

<script type="text/javascript">

    // google.maps.event.addDomListener(window, 'load', initialize);
    //     function initialize() {
    //         var input = document.getElementById('location');
    //         var autocomplete = new google.maps.places.Autocomplete(input);
    //         autocomplete.addListener('place_changed', function () {
    //             var place = autocomplete.getPlace();

    //             var address = place.formatted_address;
    //             var area = place.address_components[1].long_name+', '+place.address_components[2].long_name;

    //             $('#latitude').val(place.geometry['location'].lat());
    //             $('#longitude').val(place.geometry['location'].lng());
    //             $('#address1').val(address);
    //             $('#address2').val(area);
    //         });
    //     }

    $( function() {
        $( ".datepickerdate1" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

        $( ".datepickerdate" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            maxDate: "2004-01-01",
        });
    });

    $( function() {
      $('.timePic').timepicker();
    });

    $(document).ready(function() {

        $(".select2Class").select2({});

        $('.dropify').dropify();

        // $("#cityId").attr("disabled", true);

        $('#stateId').on('change', function() {
        var s_name = this.value;

            $("#cityId").html('');
            $("#talukaId").html('');

            var ajaxUrl = '{{ route('get-city') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                s_name: s_name,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    console.log(result);
                    $("#cityId").attr("disabled", false);
                    $('#cityId').html('<option value="">Select City</option>');
                    $('#talukaId').html('<option value="">Select Taluka</option>');
                    $.each(result.city,function(key,value){
                    $("#cityId").append('<option value="'+value.id+'">'+value.cName+'</option>');
                    });
                }
            });
        });


        $('#cityId').on('change', function() {

            var cityId = this.value;

            $("#talukaId").html('');
            var ajaxUrl = '{{ route('get-taluka') }}';
            $.ajax({
                url: ajaxUrl,
                type: "POST",
                data: {
                 cityId: cityId,
                _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    $("#talukaId").attr("disabled", false);
                    $('#talukaId').html('<option value="">Select Taluka</option>');
                    $.each(result.taluka,function(key,value){
                    $("#talukaId").append('<option value="'+value.id+'">'+value.tName+'</option>');
                    });
                }
            });
        });


        $('.fType').on('change', function() {
            var ftype = this.value;

            $('.dealerIdText').text(`${ftype} ID`);

            if(ftype != "Dealer")
            {
                $('#photo').rules('remove',  'required');

                $('.photoString').addClass('hideClass');

                $('.dTypeDiv').addClass('hideClass');
                $('.oTypeDiv').removeClass('hideClass');
            }else{

                var photoImage = "{{ $dealerDetail->photo ?? '' }}";

               if(photoImage == "")
                {
                    $("#photo").rules("add", "required");
                }

                $('.photoString').removeClass('hideClass');

                $('.dTypeDiv').removeClass('hideClass');
                $('.oTypeDiv').addClass('hideClass');
            }

        });

        //override required method
        $.validator.methods.required = function(value, element, param) {
            return (value == undefined) ? false : (value.trim() != '');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // This Function is used for validation
        $(".form").validate({
            ignore: ":hidden",
            rules: {
                userId: {
                    required: true,
                },
                fType: {
                    required: true,
                },
                dType: {
                    required: true,
                },
                name: {
                    required: true,
                    minlength:3,
                },
                firmName:{
                    required: true,
                    // minlength:3,
                },

                location:{
                    required: true,
                    // minlength:3,
                },

                address1:{
                    required: true,
                },

                address2:{
                    required: true,
                },
                stateId:{
                    required: true,
                },
                cityId:{
                    required: true,
                },
                talukaId:{
                    required: true,
                },
                pinCode:{
                    required: true,
                },
                regionId:{
                    required: true,
                },

                email: {
                    // required: true,
                    email: true,
                },
                wpMobileNumber: {
                    required: true,
                },
                // mobileNumber: {
                //     required: true,
                // },
                // dob: {
                //     required: true,
                // },
                // photo: {
                //     required: photo(),
                // },
                shopPhoto: {
                    required: image(),
                }
            },

            messages: {
                userId: {
                    required: 'Please select user',
                },
                fType: {
                    required: 'Please select form type',
                },
                dType: {
                    required: 'Please select dealer type',
                },
                location: {
                    required: 'Please enter location',
                },
                name: {
                    required: 'Please enter name',
                },
                firmName: {
                    required: 'Please enter firm name',
                },

                address1: {
                    required: 'Please enter address 1',
                },

                address2: {
                    required: 'Please enter address 2',
                },

                stateId: {
                    required: 'Please select state',
                },
                cityId: {
                    required: 'Please select city',
                },
                talukaId: {
                    required: 'Please select taluka',
                },
                regionId: {
                    required: 'Please select region',
                },
                pinCode:{
                    required: 'Please enter pin code',
                },
                email: {
                    required: 'Please enter email',
                },
                wpMobileNumber: {
                    required: 'Please enter whatsapp mobile number',
                },

                mobileNumber: {
                    required: 'Please enter mobile number',
                },

                dob:{
                    required: 'Please select date of birth',
                },
                photo:{
                    required: 'Please upload  photo',
                },
                shopPhoto:{
                    required: 'Please upload shop photo',
                },

            },
            submitHandler: function(form) {
                form.submit();
            },
            invalidHandler: function(form, validator) {

            }
        });

        $(".submit").click(function() {
            $(".submit").removeClass("loading");
        });

        $(".submit").addClass("loading");

        function image() {

            var image = "{{ $dealerDetail->id ?? '' }}";
            if (image) {
                return false;
            }
            return true;
        }

        var type = "{{$type}}";

        if(type == 1)
        {
            var photoImage = "{{ $dealerDetail->photo ?? '' }}";

            if(photoImage == "")
            {
                $("#photo").rules("add", "required");
            }

            $("#dob").rules("add", "required");

            $('.photoString').removeClass('hideClass');

            $('.dTypeDiv').removeClass('hideClass');
            $('.oTypeDiv').addClass('hideClass');
        }else{
            $('.photoString').addClass('hideClass');
        }

    });
</script>
@endsection
