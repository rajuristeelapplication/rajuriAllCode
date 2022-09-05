@extends('admin.layout.index')
@section('title') {{ __('labels.schedule_details') }} @endsection
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
        <a href="{{ route('schedules.index') }}"><span class="breadcrumb">{{__('labels.manage_schedules') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.schedule_details') }}</span>
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
                        {{ __('labels.schedule_details') }}
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

                                    {{-- <div class="p-5 border-l border-gray-200">
                                        <div
                                            class="d-flex align-items-strat flex-column justify-content-center text-gray-600">
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center"> <i
                                                    class="feather-mail w-4 h-4 mr-2"></i> {{ $scheduleInfo->email ??
                                                '-'
                                                }}
                                            </div>
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                <i class="feather-smartphone w-4 h-4 mr-2"></i>
                                                {{ $scheduleInfo->mobileNumber ?? '-' }}
                                            </div>

                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                <i class="fa fa-whatsapp w-4 h-4 mr-2"></i>
                                                {{ $scheduleInfo->wpMobileNumber ?? '-' }}
                                            </div>
                                        </div>
                                    </div> --}}

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
                                            <i class="feather-clock w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.schedule_details')}}
                                        </button>
                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="location-info-tab" data-bs-toggle="tab" data-bs-target="#location-info"
                                            type="button" role="tab" aria-controls="location-info"
                                            aria-selected="false">
                                            <i class="feather-map-pin w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.location_details')}}
                                        </button>

                                        {{-- <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="user-contact-info-tab" data-bs-toggle="tab"
                                            data-bs-target="#user-contact-info" type="button" role="tab"
                                            aria-controls="user-contact-info" aria-selected="false">
                                            <i class="fa fa-file-text-o w-4 h-4 mr-2"
                                                data-feather=""></i>{{__('labels.project_info')}}

                                        </button>

                                        @if($scheduleInfo->fType == __('labels.dealer_filter'))
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

                                        @endif --}}
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
                                                {{__('labels.schedule_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.schedule_visit')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->sType }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.created_by')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->createdBy ?? '-'
                                                                }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.user_type')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->fType ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.date')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->sDateFormate??
                                                                "-" }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.time')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->sTimeFormate??
                                                                "-" }}</span>
                                                        </div>

                                                        @php
                                                        $type = '';
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

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{ $type }} {{__('labels.id')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->dealerId ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.firm_name')}} :
                                                            <span class=" mr-2 ml-2">{{$scheduleInfo->sFirmName ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.purpose')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->purpose ??
                                                                '-'}}</span>
                                                        </div>

                                                        @if($scheduleInfo->purpose == "Other")
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            Purpose Text :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->purposeText ??
                                                                '-'}}</span>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    @php
                                                    if( $scheduleInfo->isActive == 1)
                                                    {
                                                    $type = 'success';
                                                    $isActive = "Yes";
                                                    }else{
                                                    $type = 'danger';
                                                    $isActive = "No";
                                                    }
                                                    @endphp

                                                    <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        {{__('labels.isActive')}} :
                                                        <span class=" mr-2 ml-2"><span
                                                                class="badge badge-{{$type}}">{{$isActive
                                                                }}</span></span>
                                                    </div>

                                                    @if(!empty($scheduleInfo->voiceRecording))

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3 voiceRecording">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1" >
                                                            {{__('labels.voice_recording')}} :
                                                            <span class=" mr-2 ml-2 position-rel" style="display: inline-block;"><audio
                                                                    controls="" style="vertical-align: middle"
                                                                    src="{{ $scheduleInfo->voiceRecording ?? '-'}}"
                                                                    type="audio/m4a" controlslist="nodownload">
                                                                    Your browser does not support the audio element.
                                                                </audio>

                                                                @php
                                                            $redirectRoute = route('deleteImage',['id'=>$scheduleInfo->id,'type' => 'voiceRecording' ])
                                                            @endphp


                                                        <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');" style="transform:none !important"  class="close-gen-btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                        </a>

                                                            </span>



                                                        </div>
                                                    </div>

                                                    @endif

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
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.location')}} :
                                                            <span class=" mr-2 ml-2">{{ $scheduleInfo->slocation ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.start_location')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->startLocation ??
                                                                '-'
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.end_location')}} :
                                                            <span class="mr-2 ml-2">{{ $scheduleInfo->endLocation ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    @if(!empty($scheduleInfo->uploadPhoto))
                                                        <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3 uploadPhoto">
                                                            <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                                {{__('labels.image')}} :
                                                                <span class=" mr-2 ml-2 position-rel" style="display: inline-block;margin-top:15px;">

                                                                        <a class="fancybox" href="{{ $scheduleInfo->uploadPhoto ?? '' }}" >
                                                                        <img alt="{{ config('app.name') }}" src="{{$scheduleInfo->uploadPhoto ?? '' }}"height="50">
                                                                        </a>

                                                                        @php
                                                                        $redirectRoute = route('deleteImage',['id'=>$scheduleInfo->id,'type' => 'uploadPhoto' ])
                                                                        @endphp

                                                                            <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');"  class="close-gen-btn">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                            </a>



                                                            </span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <br>
                                                    @if(!empty($scheduleInfo->watermarkImage))
                                                        <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3 watermarkImage">
                                                            <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                                Watermark Image :
                                                                <span class=" mr-2 ml-2 position-rel" style="display: inline-block;margin-top:15px;">

                                                                        <a class="fancybox" href="{{ $scheduleInfo->watermarkImage ?? '' }}" >
                                                                        <img alt="{{ config('app.name') }}" src="{{$scheduleInfo->watermarkImage ?? '' }}"height="50">
                                                                        </a>

                                                                        @php
                                                                        $redirectRoute = route('deleteImage',['id'=>$scheduleInfo->id,'type' => 'watermarkImage' ])
                                                                        @endphp

                                                                        <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');"  class="close-gen-btn">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                        </a>

                                                            </span>
                                                            </div>
                                                        </div>
                                                    @endif


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
