@extends('admin.layout.index')
@section('title') {{ __('labels.dealer_details') }} @endsection
@section('css')
<link href="{{ url('css/app-all.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">


</style>
@endsection
@section('content')

<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="___class_+?2___">{{ __('labels.dashboard_navigation')}}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('dealers.index',['type' => $type]) }}"><span class="breadcrumb">{{__('labels.manage_dealer') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.dealer_details') }}</span>
    </div>
    @endsection
    @include('admin.common.notification')

    @include('admin.common.flash')
    <br><br>

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center justify-content-between h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{ __('labels.dealer_details') }}
                    </h2>
                    {{-- <div class="d-flex ">
                        <a href="{{ route('dealers.edit', ['dealers' => $dealerDetail->id ]) }}"
                            class="ms-auto d-flex btn btn-success"> Edit </a>
                        <a href="{{ route('dealers.index') }}" class="ms-3 d-flex btn btn-danger"> Back </a>
                    </div> --}}
                </div>

                <div class="intro-y align-items-center mt-8">
                    <div class="gap-6 mt-4">
                        <!-- BEGIN: Profile Menu -->

                        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 d-flex d-lg-block">
                            <div class="intro-y box w-100">
                                <div class="position-relative d-flex align-items-center p-5">

                                    <h5 class="user-detail-head p-5">

                                        {{__('labels.dealer_name')}} : {{ $dealerDetail->name ?? '-' }}
                                    </h5>

                                    <div class="p-5 border-l border-gray-200">
                                        <div
                                            class="d-flex align-items-strat flex-column justify-content-center text-gray-600">
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center"> <i
                                                    class="feather-mail w-4 h-4 mr-2"></i> {{ $dealerDetail->email ?? '-'
                                                }}
                                            </div>
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                <i class="feather-smartphone w-4 h-4 mr-2"></i>
                                                {{ $dealerDetail->mobileNumber ?? '-' }} </div>

                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                <i class="fa fa-whatsapp w-4 h-4 mr-2"></i>
                                                {{ $dealerDetail->wpMobileNumber ?? '-' }} </div>
                                        </div>
                                    </div>

                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium text-base"></div>
                                        <div class="text-gray-600">

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- END: Profile Menu -->
                        <div class="col-span-12 lg:col-span-8  ">
                            <div class="intro-y box col-span-12">
                                <nav>
                                    <div class="nav nav-tabs d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start px-4 mt-2"
                                        id="nav-tab" role="tablist">
                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center active"
                                            id="personal-info-tab" data-bs-toggle="tab" data-bs-target="#personal-info"
                                            type="button" role="tab" aria-controls="personal-info" aria-selected="true">
                                            <i class="feather-user w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.dealer_details')}}
                                        </button>
                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="professional-info-tab" data-bs-toggle="tab"
                                            data-bs-target="#professional-info" type="button" role="tab"
                                            aria-controls="professional-info" aria-selected="false">
                                            <i class="feather-info w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.material_status')}}
                                        </button>

                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="user-contact-info-tab" data-bs-toggle="tab"
                                            data-bs-target="#user-contact-info" type="button" role="tab"
                                            aria-controls="user-contact-info" aria-selected="false">
                                            <i class="fa fa-file-text-o w-4 h-4 mr-2"
                                                data-feather=""></i>{{__('labels.project_info')}}

                                        </button>

                                        @if($dealerDetail->fType == __('labels.dealer_filter'))
                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="company_details-info-tab" data-bs-toggle="tab"
                                            data-bs-target="#company_details-info" type="button" role="tab"
                                            aria-controls="company_details-info" aria-selected="false">
                                            <i class="fa fa-file-text w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.company_details')}}

                                        </button>

                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="bank_details-info-tab" data-bs-toggle="tab"
                                            data-bs-target="#bank_details-info" type="button" role="tab"
                                            aria-controls="bank_details-info" aria-selected="false">
                                            <i class="fa fa-university w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.bank_details')}}

                                        </button>

                                        @endif
                                    </div>
                                </nav>
                            </div>
                            <div class="tab-content mt-5" id="nav-tabContent">

                                <div class="tab-pane fade show active" id="personal-info" role="tabpanel"
                                    aria-labelledby="personal-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: personal Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.dealer_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">



                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.form_type')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->fType }}</span>
                                                        </div>
                                                        @if (!empty($dealerDetail->dType))
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            Actor Type :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->dType ?? '-'
                                                                }}</span>
                                                        </div>
                                                        @endif
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.created_by')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->createdBy ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    @if ($dealerDetail->fType != __('labels.dealer_filter') )
                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.date')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                !empty($dealerDetail->date) ? Carbon\Carbon::parse($dealerDetail->date)->format(config('constant.admin_dob_format')) : "-"
                                                                         }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.time')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                !empty($dealerDetail->time) ? Carbon\Carbon::parse($dealerDetail->time)->format(config('constant.admin_dealer_time_format')) : "-"
                                                                 }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.location')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->location ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    {{-- @php
                                                    if ($dealerDetail->fType == __('labels.dealer_filter') ) {
                                                    $type = __('labels.dealer_filter') ;
                                                    }elseif ($dealerDetail->fType == __('labels.engineer_filter')) {
                                                    $type = __('labels.engineer_filter');
                                                    }elseif ($dealerDetail->fType == __('labels.architect_filter')) {
                                                    $type = __('labels.architect_filter');
                                                    }elseif ($dealerDetail->fType == __('labels.mason_filter')) {
                                                    $type = __('labels.mason_filter');
                                                    }elseif ($dealerDetail->fType == __('labels.contractor_filter')) {
                                                    $type = __('labels.contractor_filter');
                                                    }elseif ($dealerDetail->fType == __('labels.construction_filter')) {
                                                    $type = __('labels.construction_filter');
                                                    }
                                                    @endphp --}}

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.dealer') }} {{__('labels.id')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->dealerId ?? '-'
                                                                }}</span>
                                                        </div>

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.dob')}} :
                                                            <span class=" mr-2 ml-2">
                                                                {{

                                                                    !empty($dealerDetail->dob) ?  Carbon\Carbon::parse($dealerDetail->dob)->format(config('constant.admin_dob_format')) : "- "

                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.firm_name')}} :
                                                            <span class=" mr-2 ml-2">
                                                            {{ !empty($dealerDetail->firmName) ?  $dealerDetail->firmName  : '-' }}
                                                            </span>
                                                        </div>
                                                    </div>



                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.address1')}} :
                                                            <span class="mr-2 ml-2">
                                                            {{ !empty($dealerDetail->address1) ?  $dealerDetail->address1  : '-' }}
                                                            </span>
                                                        </div>

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.address2')}} :
                                                            <span class="mr-2 ml-2">
                                                                {{ !empty($dealerDetail->address2) ?  $dealerDetail->address2  : '-' }}
                                                             </span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.state_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->sName ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.district_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->cName ?? '-'
                                                                }}</span>

                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.taluka_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->tName ?? '-'
                                                                }}</span>

                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.pincode')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->pinCode ?? '-'
                                                                }}</span>

                                                        </div>


                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.region_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->rName ?? '-'
                                                                }}</span>
                                                        </div>
                                                        @if($dealerDetail->fType == __('labels.dealer_filter'))
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.year_of_inco')}} :
                                                            <span class=" mr-2 ml-2">
                                                                {{ !empty($dealerDetail->yearOfIncorporation) ?  $dealerDetail->yearOfIncorporation  : '-' }}
                                                            </span>
                                                        </div>

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.aadhar_no')}} :
                                                            <span class=" mr-2 ml-2">
                                                                {{ !empty($dealerDetail->aadharNo) ?  $dealerDetail->aadharNo  : '-' }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <div class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        @if($dealerDetail->fType == __('labels.dealer_filter'))
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.prof_family_detail')}} :
                                                            <span class=" mr-2 ml-2">
                                                                {{ !empty($dealerDetail->familyDetails) ?  $dealerDetail->familyDetails  : '-' }}
                                                            </span>
                                                        </div>
                                                        @endif

                                                        @if(!empty($dealerDetail->shopPhoto))

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1 dealerShopePhoto">
                                                            <label>{{__('labels.shop_photo')}} :</label>
                                                            <div class=" mr-2 ml-2 position-rel" style="display: inline-block">
                                                                <a class="fancybox" href="{{ $dealerDetail->shopPhoto ?? '' }}" >
                                                                <img alt="{{ config('app.name') }} Shop Photo"
                                                                    src="{{ $dealerDetail->shopPhoto ?? '-' }}"
                                                                    height="50">
                                                                </a>

                                                                @php
                                                                   $redirectRoute = route('deleteImage',['id'=>$dealerDetail->id,'type' => 'dealerShopePhoto' ])
                                                                @endphp

                                                            <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');"  class="close-gen-btn">
                                                                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                            </a>

                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if(!empty($dealerDetail->photo))

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1 dealerPhoto">
                                                            <label>Actor Image :</label>
                                                            <div class=" mr-2 ml-2 position-rel" style="display: inline-block">
                                                                <a class="fancybox" href="{{ $dealerDetail->photo ?? '' }}" >
                                                                    <img alt="{{ config('app.name') }} Dealer Image"
                                                                        src="{{ $dealerDetail->photo ?? '-' }}"
                                                                        height="50">
                                                                </a>

                                                                @php
                                                                    $redirectRoute1 = route('deleteImage',['id'=>$dealerDetail->id,'type' => 'dealerPhoto' ])
                                                                @endphp

                                                                <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute1 }}');"  class="close-gen-btn">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                </a>

                                                            </div>
                                                        </div>

                                                        @endif

                                                    </div>
                                                        @php
                                                        if ($dealerDetail->statusDealers == __('labels.pending')){
                                                            $badge = 'info';
                                                        }elseif($dealerDetail->statusDealers == __('labels.approved')){
                                                            $badge = 'success';
                                                        }elseif($dealerDetail->statusDealers == __('labels.rejected')){
                                                            $badge = 'danger';
                                                        }
                                                        elseif($dealerDetail->statusDealers == "InActive"){
                                                            $badge = 'warning';
                                                        }
                                                        $userStatusRoute = route('dealers.userStatus', ['dealers' => $dealerDetail->id]);
                                                        @endphp

                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            <label>Status :</label>
                                                                <span class="badge badge-{{ $badge }} mr-2 ml-2 btnChangeUserStatus"
                                                                 data-id="{{ $dealerDetail->id }}" data-url=" {{ $userStatusRoute }}"
                                                                 data-st="{{ $dealerDetail->statusDealers }}">
                                                                 {{ $dealerDetail->statusDealers }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- END: Basic Info -->
                                    </div>
                                </div>

                                {{-- Material Status --}}
                                <div class="tab-pane fade" id="professional-info" role="tabpanel"
                                    aria-labelledby="professional-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: professional Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.material_status')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">


                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.ms_requried_material')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $dealerDetail->msRequriedMaterial ?? '-' }}</span>
                                                        </div>
                                                    </div>


                                                    @if($dealerDetail->fType != __('labels.dealer_filter'))
                                                    {{-- @if(!empty($dealerDetail->msOnGoingProject)) --}}
                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.use_till_now')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->msUtillNow ?? '-'
                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.completed_project')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $dealerDetail->msCompletedProject ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.ongoing_project')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->msOnGoingProject
                                                                ?? '-' }}</span>
                                                        </div>

                                                    </div>

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.ms_year_of_inco')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $dealerDetail->msYearOfIncorporation ?? '-' }}</span>
                                                        </div>
                                                    </div>

                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: professional Info -->
                                    </div>
                                </div>

                                {{-- Project Information --}}
                                <div class="tab-pane fade" id="user-contact-info" role="tabpanel"
                                    aria-labelledby="user-contacts-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: user-contacts Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.project_info')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.type_of_site')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->piTypeOfSite ??
                                                                '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.status_of_site')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->piStatusOfSite ??
                                                                '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.area')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->piArea ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.estimated_cost')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->piEstimateCost ??
                                                                '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.project_engineer')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->piProjectEngineer
                                                                ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.architect')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->piArchitect ??
                                                                '-'}}</span>
                                                        </div>

                                                    </div>

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.executor')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->piExecutor ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: user-contacts Info -->
                                    </div>
                                </div>



                                {{-- Company Details --}}
                                <div class="tab-pane fade" id="company_details-info" role="tabpanel"
                                    aria-labelledby="company_details-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: user-contacts Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.company_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.firm_reg_no')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $dealerDetail->cdFirmRegistrationNumber ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.shop_act_no')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $dealerDetail->cdShopActLicenceNumber ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.gst_no')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->cdGstNumber ??
                                                                '-' }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.pan')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->cdPan ?? '-'
                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.firm_type')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->cdFirmType ?? '-'
                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.cin')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->cdCin ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.shop_warehouse_area')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $dealerDetail->cdShopWarehouseArea ?? '-' }}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: user-contacts Info -->
                                    </div>
                                </div>


                                {{-- Bank Details --}}
                                <div class="tab-pane fade" id="bank_details-info" role="tabpanel"
                                    aria-labelledby="bank_details-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: user-contacts Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.bank_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.mode_of_pay')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->bdModeOfPayment
                                                                ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.bank_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->bdBankName ?? '-'
                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.address_of_bank')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->bdBankAddress ??
                                                                '-' }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.acc_no')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->bdAccountNumber
                                                                ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.ifsc_code')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->bdIfscCode ?? '-'
                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.nature_of_account')}} :
                                                            <span class=" mr-2 ml-2">{{ $dealerDetail->bdNatureOfAccount
                                                                ?? '-' }}</span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: user-contacts Info -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- END: General Report -->
        </div>
    </div>
</div>
<!-- END: Content -->

@include('admin.manageDealer.modal')


@endsection

@section('js')

<script>


$(document).on("click",'.btnChangeUserStatus',function(event) {

            event.preventDefault();
            var statusValue = $(this).attr('data-st');

            $("#requestStatus").val(statusValue);

            // if(statusValue == "Pending"){
            $('#userStatusChange-modal').modal('show');
            // }

            var url = $(this).data('url');
            form = $('#updateUserStatus');
            form.attr('action', url);

            $('#leaveId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');
    });


</script>

@endsection
