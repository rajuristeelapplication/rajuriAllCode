@extends('admin.layout.index')
@section('title') {{ __('labels.leave_details') }} @endsection
@section('css')
<link href="{{ url('css/app-all.css') }}">

<style>
    .badge {
        cursor: context-menu !important;
    }

    .addClass
    {
        display: none;
    }
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
        <a href="{{ route('leaves.index') }}"><span class="breadcrumb">{{__('labels.manage_leave') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.leave_details') }}</span>
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
                        {{ __('labels.leave_details') }}
                    </h2>
                </div>


                @include('admin.common.flash')
                <br><br>


                <div class="intro-y align-items-center mt-8">
                    <div class="gap-6 mt-4">
                        <!-- BEGIN: Profile Menu -->

                        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 d-flex d-lg-block">
                            <div class="intro-y box w-100">
                                <div class="position-relative d-flex align-items-center p-5">

                                    <h5 class="user-detail-head p-5">
                                        {{__('labels.created_by')}} : {{ $leaveDetail->fullName ?? '-' }}
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
                                            <i class="feather-user-check w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.leave_details')}}
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
                                                {{__('labels.leave_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.create_date_time') }} :
                                                            <span class=" mr-2 ml-2">{{ $leaveDetail->createdAtFormate
                                                                }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.leave_type')}} :
                                                            <span class=" mr-2 ml-2">{{ $leaveDetail->lType ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>



                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.fromDate')}} :
                                                            <span class=" mr-2 ml-2">{{ $leaveDetail->fromDateFormate ??
                                                                "-" }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.toDate')}} :
                                                            <span class=" mr-2 ml-2">{{ $leaveDetail->toDateFormate ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.noOfLeave')}} :
                                                            <span class=" mr-2 ml-2">{{ $leaveDetail->noOfLeave ??
                                                                '-'}}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.leave_reason')}} :
                                                            <span class=" mr-2 ml-2">{{ $leaveDetail->reason ??
                                                                '-'}}</span>
                                                        </div>

                                                    </div>


                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.otherReasonText')}} :
                                                            <span class=" mr-2 ml-2">{{ $leaveDetail->otherReasonText ??
                                                                '-'}}</span>
                                                        </div>

                                                        @php

                                                        $userStatusRoute = route('leaves.leaveStatus',  $params = ['leaves' => $leaveDetail->id]);

                                                        if( $leaveDetail->lRStatus == 'Pending')
                                                        {
                                                        $type = 'info';
                                                        }
                                                        else if( $leaveDetail->lRStatus == 'Approved')
                                                        {
                                                        $type = 'success';
                                                        }else{
                                                        $type = 'danger';
                                                        }
                                                        @endphp

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                            <label class="font-medium">{{__('labels.status')}} :</label>
                                                           <span class="mr-2 ml-2 badge badge-{{$type}} btnChangeUserStatus" data-st="{{ $leaveDetail->lRStatus }}" data-id="{{ $leaveDetail->id }}" data-url="{{ $userStatusRoute }}">{{
                                                                    $leaveDetail->lRStatus ?? '-' }}</span>
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

@include('admin.leaves.modal')

@endsection

@section('js')

<script>


$(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending"){
            $('#leaveStatusChange-modal').modal('show');
            }

            var url = $(this).data('url');
            form = $('#updateLeaveStatus');
            form.attr('action', url);

            $('#leaveId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');
    });


    $(document).on('change', '.requestStatus', function() {

var value = $(this).val();

if(value == "Rejected")
{
    $('.adminRejectOtherText').removeClass('addClass');
}else{
    $('.adminRejectOtherText').addClass('addClass');
}

});



</script>

@endsection
