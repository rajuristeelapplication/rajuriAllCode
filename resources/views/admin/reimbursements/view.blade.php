@extends('admin.layout.index')
@section('title') {{ __('labels.reimbursements_details') }} @endsection
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
        <a href="{{ route('reimbursements.index') }}"><span class="breadcrumb">{{__('labels.manage_reimbursements')
                }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.reimbursements_details') }}</span>
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
                        {{ __('labels.reimbursements_details') }}
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
                                        {{__('labels.rName')}} : {{ $reimbursementDetail->rName ?? '-' }}
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
                                            <i class="feather-refresh-cw w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.reimbursements_details')}}
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
                                                {{__('labels.reimbursements_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.create_date') }} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $reimbursementDetail->dateOfCreateAtFormate
                                                                }}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.dateOfTravelling')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $reimbursementDetail->dateOfTravellingFormate ??
                                                                "-" }}</span>
                                                        </div>

                                                    </div>



                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.expenseType')}} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->eName ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.created_by') }} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->fullName ?? "-"
                                                                }}</span>
                                                        </div>

                                                    </div>

                                                    @if ($reimbursementDetail->eName == "Birthday Gift")
                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.dealer_id')}} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->dealerId ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.dealer_name') }} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->name ?? "-"
                                                                }}</span>
                                                        </div>

                                                    </div>
                                                    @endif

                                                    @if ($reimbursementDetail->eName == "Incentive")
                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.project_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->projectName ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.location') }} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->rLocation ?? "-"
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.quantity_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->rQuantity ??
                                                                '-'}}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{ __('labels.payment_status') }} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->paymentStatus ?? "-"
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.invoice_number')}} :
                                                            <span class=" mr-2 ml-2">{{ $reimbursementDetail->invoiceNumber ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>

                                                    @endif

                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.totalAmount')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $reimbursementDetail->totalAmountFormate ??
                                                                '-'}}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.description')}} :
                                                            <span class=" mr-2 ml-2">{{
                                                                $reimbursementDetail->description ??
                                                                '-'}}</span>
                                                        </div>
                                                    </div>


                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        @php

                                                        $userStatusRoute = route('reimbursements.reimbursementsStatus',$params = ['reimbursements' => $reimbursementDetail->id]);

                                                        if( $reimbursementDetail->rStatus == 'Pending')
                                                        {
                                                        $type = 'info';
                                                        }
                                                        else if( $reimbursementDetail->rStatus == 'Approved')
                                                        {
                                                        $type = 'success';
                                                        }else{
                                                        $type = 'danger';
                                                        }
                                                        @endphp

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            <label class="font-medium">{{__('labels.status')}} :</label>
                                                                <span data-st="{{ $reimbursementDetail->rStatus }}" data-id="{{ $reimbursementDetail->id }}" data-url="{{ $userStatusRoute }}"
                                                                    class="btnChangeUserStatus mr-2 ml-2 badge badge-{{$type}}">
                                                                    {{ $reimbursementDetail->rStatus ?? '-'}}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.attach_picture')}} :

                                                            @if (!empty($reimbursementDetail->reimbursementsImage))
                                                            @foreach ($reimbursementDetail->reimbursementsImage as $key => $image)

                                                            <span class=" mr-2 ml-2 position-rel reimbursementsImage_{{$image->id}}" style="display: inline-block">
                                                                <a class="fancybox" href="{{ $image->rAttachment ?? '' }}"><img
                                                                alt="{{ config('app.name') }} Attach Picture"
                                                                src="{{ $image->rAttachment ?? '' }}"
                                                                height="50"></a>

                                                                @php
                                                                $redirectRoute = route('deleteImage',['id'=>$image->id,'type' => 'reimbursementsImage'])
                                                               @endphp


                                                                <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');"  class="close-gen-btn">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                </a>


                                                            </span>

                                                            @endforeach
                                                            @endif
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

@include('admin.reimbursements.modal')

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

</script>

@endsection
