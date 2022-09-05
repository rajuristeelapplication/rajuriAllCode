@extends('admin.layout.index')
@section('title') {{ __('labels.in_out_details') }} @endsection
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
        <a href="{{ route('inOuts.index') }}"><span class="breadcrumb">{{__('labels.in_out_flag') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.in_out_details') }}</span>
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
                        {{ __('labels.in_out_details') }}
                    </h2>
                    {{-- <div class="d-flex ">
                        <a href="{{ route('dealers.edit', ['dealers' => $inOutDetail->id ]) }}"
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
                                        {{__('labels.created_by')}} : {{ $inOutDetail->createdBy ?? '-' }}
                                    </h5>

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
                                            {{__('labels.in_out_details')}}
                                        </button>
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
                                                {{__('labels.in_out_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.inAddress')}} :
                                                            <span class=" mr-2 ml-2">{{$inOutDetail->inAddress?? "-"
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.inDateTime')}} :
                                                            <span class=" mr-2 ml-2">{{$inOutDetail->inDateTime?? "-"
                                                                }}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.inReadingPhoto')}} :
                                                            <span class=" mr-2 ml-2"><a class="fancybox"
                                                                    href="{{ $inOutDetail->inReadingPhoto ?? '' }}"><img
                                                                        alt="{{ config('app.name') }} In Reading Photo"
                                                                        src="{{ $inOutDetail->inReadingPhoto ?? '' }}"
                                                                        height="50"></a></span>
                                                        </div>
                                                    </div>


                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.outAddress')}} :
                                                            <span class=" mr-2 ml-2">{{$inOutDetail->outAddress?? "-"
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.outDateTime')}} :
                                                            <span class=" mr-2 ml-2">{{$inOutDetail->outDateTime?? "-"
                                                                }}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.outReadingPhoto')}} :
                                                            <span class=" mr-2 ml-2"><a class="fancybox"
                                                                    href="{{ $inOutDetail->outReadingPhoto ?? '' }}"><img
                                                                        alt="{{ config('app.name') }} Out Reading Photo"
                                                                        src="{{ $inOutDetail->outReadingPhoto ?? '' }}"
                                                                        height="50"></a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- END: Basic Info -->
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
