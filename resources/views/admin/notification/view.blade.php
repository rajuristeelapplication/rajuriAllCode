@extends('admin.layout.index')
@section('title') {{ __('labels.notification_list_detail') }} @endsection
@section('css')
<style>
    .close {
        position: absolute;
    }

    label {
        flex: 1 100px;
        max-width: 100px;
        min-width: 100px;
    }
</style>
@endsection
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" media="screen">

<div class="content">
    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="___class_+?2___">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('notifications.index') }}"><span class="breadcrumb">{{ __('labels.notifications') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.notification_list_detail') }}</span>
    </div>
    @endsection
    @include('admin.common.notification')
    <div class="intro-y d-flex align-items-center mt-8">
        <h2 class="text-lg font-medium me-auto">
            {{ __('labels.notification_list_detail') }}
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-4">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12  d-flex d-lg-block">
            <div class="intro-y box w-100">


                {{-- <div class="position-relative d-flex align-items-center p-5">
                    <label>{{ __('labels.notification_user_name') }} :</label>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{ $query->userName ?? '' }}</div>
                    </div>
                </div> --}}

                <div class="position-relative d-flex align-items-center p-5">
                    <label>{{ __('labels.notification_title') }} :</label>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{!! $query->title ?? '' !!}</div>
                    </div>
                </div>

                <div class="position-relative d-flex align-items-center p-5">
                    <label>{{ __('labels.notification_sender_name') }} :</label>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{ $query->senderName ?? '' }}</div>
                    </div>
                </div>

                <div class="position-relative d-flex align-items-center p-5">
                    <label>{{ __('labels.notification_module') }} :</label>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{ $query->module ?? '' }}</div>
                    </div>
                </div>

                <div class="position-relative d-flex align-items-center p-5">
                    <label>Created On :</label>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{ $query->createdOn ?? '' }}</div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


@endsection
