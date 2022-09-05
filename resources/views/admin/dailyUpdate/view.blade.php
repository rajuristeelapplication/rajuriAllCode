@extends('admin.layout.index')
@section('title') {{ __('labels.daily_update_details') }} @endsection
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
        <a href="{{ route('AdminDashboard') }}"><span class="___class_+?2___">{{ __('labels.dashboard_navigation')
                }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('dailyUpdates.index') }}"><span class="breadcrumb">{{__('labels.manage_daily_update')
                }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.daily_update_details') }}</span>
    </div>
    @endsection
    @include('admin.common.notification')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center justify-content-between h-10">
                    <h2 class="text-lg font-medium truncate me-4 mb-0">
                        {{ __('labels.daily_update_details') }}
                    </h2>
                    {{-- <div class="d-flex ">
                        <a href="{{ route('dealers.edit', ['dealers' => $scheduleInfo->id ]) }}"
                            class="ms-auto d-flex btn btn-success"> Edit </a>
                        <a href="{{ route('schedules.index') }}" class="ms-3 d-flex btn btn-danger"> Back </a>
                    </div> --}}

                </div>


                <div class="intro-y align-items-center mt-8">
                    <div class="gap-6 mt-4">
                        <!-- BEGIN: Profile Menu -->

                        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 d-flex d-lg-block">
                            <div class="intro-y box w-100">
                                <div class="position-relative d-flex align-items-center p-5">

                                    <h5 class="user-detail-head p-5">
                                        {{-- <img class="w-10" src="{{$scheduleInfo->photo }}"
                                            onerror="this.onerror=null;this.src='{{ url('images/default_admin.jpg') }}';" />
                                        --}}
                                        {{__('labels.dealer_name')}} : {{ $scheduleInfo->name ?? '-' }}
                                    </h5>

                                    <div class="p-5 border-l border-gray-200">
                                        <div
                                            class="d-flex align-items-strat flex-column justify-content-center text-gray-600">
                                            {{-- <div class="truncate sm:whitespace-normal d-flex align-items-center">
                                                <i class="feather-mail w-4 h-4 mr-2"></i> {{ $scheduleInfo->email ??
                                                '-'
                                                }}
                                            </div> --}}
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                <i class="feather-smartphone w-4 h-4 mr-2"></i>
                                                {{ $scheduleInfo->mobileNumber ?? '-' }}
                                            </div>

                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                <i class="fa fa-whatsapp w-4 h-4 mr-2"></i>
                                                {{ $scheduleInfo->wpMobileNumber ?? '-' }}
                                            </div>
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
                                            <i class="fa fa-clock-o w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.daily_update_details')}}
                                        </button>
                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="location-info-tab" data-bs-toggle="tab" data-bs-target="#location-info"
                                            type="button" role="tab" aria-controls="location-info"
                                            aria-selected="false">
                                            <i class="feather-map-pin w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.location_details')}}
                                        </button>

                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="material-info-tab" data-bs-toggle="tab" data-bs-target="#material-info"
                                            type="button" role="tab" aria-controls="material-info"
                                            aria-selected="false">
                                            <i class="fa fa-shopping-bag w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.material_details')}}

                                        </button>


                                        @if($scheduleInfo->sType == 'Influencer Visit')

                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="visit-info-tab" data-bs-toggle="tab" data-bs-target="#visit-info"
                                            type="button" role="tab" aria-controls="visit-info" aria-selected="false">
                                            <i class="fa fa-file-text-o w-4 h-4 mr-2"
                                                data-feather=""></i>{{__('labels.project_influencer_visit')}}

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
                                                {{__('labels.daily_update_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div class="row">
                                                        <div class="col-4 mb-2 mt-2">
                                                            {{__('labels.schedule_visit')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->sType }}</span>
                                                        </div>
                                                        <div class="col-4 mb-2 mt-2">
                                                            {{__('labels.created_by')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->createdBy ?? '-'
                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.user_type')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->fType ?? '-'
                                                                }}</span>
                                                        </div>


                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.date')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->sDateFormate??
                                                                "-" }}</span>
                                                        </div>
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.time')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->sTimeFormate??
                                                                "-" }}</span>
                                                        </div>

                                                        @php
                                                        if ($scheduleInfo->fType == __('labels.dealer_filter') ) {
                                                        $type = __('labels.dealer_filter') ;
                                                        }elseif ($scheduleInfo->fType == __('labels.engineer_filter')) {
                                                        $type = __('labels.engineer_filter');
                                                        }elseif ($scheduleInfo->fType == __('labels.architect_filter'))
                                                        {
                                                        $type = __('labels.architect_filter');
                                                        }elseif ($scheduleInfo->fType == __('labels.mason_filter')) {
                                                        $type = __('labels.mason_filter');
                                                        }elseif ($scheduleInfo->fType == __('labels.contractor_filter'))
                                                        {
                                                        $type = __('labels.contractor_filter');
                                                        }elseif ($scheduleInfo->fType ==
                                                        __('labels.construction_filter')) {
                                                        $type = __('labels.construction_filter');
                                                        }
                                                        @endphp

                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{ $type }} {{__('labels.id')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->dealerId ?? '-'
                                                                }}</span>
                                                        </div>



                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.firm_name')}} :
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->sFirmName ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.purpose')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->purpose ??
                                                                '-'}}</span>
                                                        </div>

                                                        @php
                                                        if( $scheduleInfo->isActive == '1')
                                                        {
                                                        $type = 'success';
                                                        $isActive = "Yes";
                                                        }else{
                                                        $type = 'danger';
                                                        $isActive = "No";
                                                        }
                                                        @endphp

                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.isActive')}} :
                                                            <span class=" mr-2 ml-2"><span
                                                                    class="badge badge-{{$type}}">{{$isActive
                                                                    }}</span></span>
                                                        </div>




                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.address1')}} :
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->dAddress1 ??
                                                                "-"}}</span>
                                                        </div>


                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.address2')}} :
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->dAddress2 ??
                                                                "-"}}</span>
                                                        </div>


                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.state')}} :
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->dSName ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.district_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->dCName ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.taluka_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->dTName ??
                                                                '-'}}</span>
                                                        </div>

                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.pincode')}} :
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->dPinCode ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.region_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->dRName ??
                                                                '-'}}</span>
                                                        </div>


                                                        @if($scheduleInfo->sType != "Dealer Visit")
                                                        <div class="col-4 mb-2 mt-2 ">
                                                           Construction(Sq.ft) :
                                                            <span class=" mr-2 ml-2">{{
                                                                $scheduleInfo->construction ??
                                                                '-'}}</span>
                                                        </div>
                                                        @endif

                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.material_available')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $scheduleInfo->ourMaterialsAvailable ??
                                                                '-'}}</span>
                                                        </div>



                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.other_brand_name')}} :
                                                            <span class=" mr-2 ml-2">{{$brandName ?? "-"}}</span>
                                                        </div>

                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.quantity_in_tons')}} :
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->dTotalQty ??
                                                                "-"}}</span>
                                                        </div>

                                                        @if(!empty($scheduleInfo->dvrDate))
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            Report Date
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->dvrFormate ?? "-"}}</span>
                                                        </div>
                                                        @endif




                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.competitor_activities')}} :
                                                            <span
                                                                class=" mr-2 ml-2">{{$scheduleInfo->competitorActivitiesText
                                                                ?? "-"}}</span>
                                                        </div>


                                                        @if(!empty($scheduleInfo->competitorActivitiesImage))

                                                        <div class="col-6 mb-2 mt-2  competitorActivitiesImage">
                                                            {{__('labels.competitor_activities_attach')}} :
                                                            <span class=" mr-2 ml-2 position-rel" style="display: inline-block;"><a class="fancybox"
                                                                    href="{{ $scheduleInfo->competitorActivitiesImage ?? '' }}"><img
                                                                        alt="{{ config('app.name') }}"
                                                                        src="{{$scheduleInfo->competitorActivitiesImage ?? '' }}"
                                                                        height="50"></a>

                                                                        @php
                                                            $redirectRoute = route('deleteImage',['id'=>$scheduleInfo->id,'type' => 'competitorActivitiesImage' ])
                                                            @endphp


                                                        <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');" style="transform:none !important"  class="close-gen-btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                        </a>

                                                                    </span>
                                                        </div>

                                                        @endif


                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <!-- END: Basic Info -->
                                    </div>
                                </div>

                                {{-- Material Status --}}
                                <div class="tab-pane fade" id="location-info" role="tabpanel"
                                    aria-labelledby="location-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: location Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.location_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 ">
                                                            {{__('labels.location')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->slocation ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.start_location')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->startLocation ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.end_location')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->endLocation ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.image')}} :
                                                            <span class=" mr-2 ml-2"><a class="fancybox"
                                                                    href="{{ $scheduleInfo->uploadPhoto ?? '' }}"><img
                                                                        alt="{{ config('app.name') }}"
                                                                        src="{{$scheduleInfo->uploadPhoto ?? '' }}"
                                                                        height="50"></a></span>
                                                        </div>
                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            Watermark Image :
                                                            <span class=" mr-2 ml-2"><a class="fancybox"
                                                                    href="{{ $scheduleInfo->watermarkImage ?? '' }}"><img
                                                                        alt="{{ config('app.name') }}"
                                                                        src="{{$scheduleInfo->watermarkImage ?? '' }}"
                                                                        height="50"></a></span>
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: location Info -->
                                    </div>
                                </div>



                                <div class="tab-pane fade" id="visit-info" role="tabpanel"
                                    aria-labelledby="visit-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: location Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.project_influencer_visit')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.type_of_site')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->typeOfSite ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.status_of_site')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->statusOfSite ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.area')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->area ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.estimated_cost')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->estimatedCost ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.project_engineer')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->projectEngineer ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.architect')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->architect ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.executor')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->executive ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: location Info -->
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="material-info" role="tabpanel"
                                    aria-labelledby="material-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: location Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.material_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.use_till_now')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->usedTillNow ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.ms_requried_material')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->tentativeSchedule
                                                                ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.completed_project')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->completedProject
                                                                ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.ongoing_project')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->ongoingProject ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.any_lead_given_by_influencer')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->anyLead ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.feedback_remark')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->feedback ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.additional_visit')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->additionVisit ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            {{__('labels.voice_recording')}} :
                                                            <span
                                                                class=" mr-2 ml-2">@if(!empty($scheduleInfo->voiceRecording))<audio
                                                                    controls="" style="vertical-align: middle"
                                                                    src="{{ $scheduleInfo->voiceRecording ?? '-'}}"
                                                                    type="audio/m4a" controlslist="nodownload">
                                                                    Your browser does not support the audio element.
                                                                </audio>@else - @endif</span>
                                                        </div>
                                                    </div>


                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-2 mt-2 mr-2 ml-1">
                                                            Material Photo :
                                                            <span class=" mr-2 ml-2"><a class="fancybox"
                                                                    href="{{ $scheduleInfo->materialPhoto ?? '' }}"><img
                                                                        alt="Material Photo"
                                                                        src="{{$scheduleInfo->materialPhoto ?? '' }}"
                                                                        height="50"></a></span>
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: location Info -->
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
@endsection

@section('js')

@endsection
