@extends('admin.layout.index')
@section('title') {{ __('labels.page_content_details') }} @endsection
@section('css')
<link href="{{ url('css/app-all.css') }}">

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
        <a href="{{ route('page-contents.index') }}"><span class="breadcrumb">{{__('labels.page_contents') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.page_content_details') }}</span>
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
                        {{ __('labels.page_content_details') }}
                    </h2>
                </div>


                <div class="intro-y align-items-center mt-8">
                    <div class="gap-6 mt-4">
                        <!-- BEGIN: Profile Menu -->


                        <!-- END: Profile Menu -->
                        <div class="col-span-12 lg:col-span-8  ">

                            <div class="tab-content mt-5" id="nav-tabContent">

                                <div class="tab-pane fade show active" id="personal-info" role="tabpanel"
                                    aria-labelledby="personal-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: personal Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            {{-- <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.page_content_details')}}
                                            </div> --}}
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.page_name') }} :
                                                            <span class=" mr-2 ml-2">{{ $contentDetail->name
                                                                }}</span>
                                                        </div>
                                                    </div>



                                                    <div class=" sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.page_content_name')}} :
                                                            <span class=" mr-2 ml-2 mt-2">{!! html_entity_decode($contentDetail->content) !!}</span>
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
