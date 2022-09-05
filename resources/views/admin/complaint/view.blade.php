@extends('admin.layout.index')
@section('title') {{ __('labels.manage_complaints_details') }} @endsection
@section('css')
<link href="{{ url('css/app-all.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
<style>
    .badge {
        cursor: context-menu !important;
    }
    .hideClass{
        display:none;
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
        <a href="{{ route('complaints.index') }}"><span class="breadcrumb">{{__('labels.manage_complaints') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.manage_complaints_details') }}</span>
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
                        {{ __('labels.manage_complaints_details') }}
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
                                       {{__('labels.c_type')}} : {{ $complaintDetail->cType ?? '-' }}
                                    </h5>

                                    {{-- <div class="p-5 border-l border-gray-200">
                                        <div
                                            class="d-flex align-items-strat flex-column justify-content-center text-gray-600">
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center"> <i
                                                    class="feather-mail w-4 h-4 mr-2"></i> {{ $complaintDetail->lEmail ?? '-'
                                                }}
                                            </div>
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                <i class="feather-smartphone w-4 h-4 mr-2"></i>
                                                {{ $complaintDetail->lMobileNumber ?? '-' }} </div>

                                        </div>
                                    </div> --}}

                                    {{-- <div class="ml-4 mr-auto">
                                        <div class="font-medium text-base"></div>
                                        <div class="text-gray-600">

                                        </div>
                                    </div> --}}
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
                                            <i class="feather-alert-octagon w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.manage_complaints_details')}}
                                        </button>

                                        @if($complaintDetail->cType == "Quality Complaint")

                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="user-contact-info-tab" data-bs-toggle="tab"
                                            data-bs-target="#user-contact-info" type="button" role="tab"
                                            aria-controls="user-contact-info" aria-selected="false">
                                            <i class="fa fa-file-text-o w-4 h-4 mr-2"
                                                data-feather=""></i>{{__('labels.project_info')}}

                                        </button>


                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="material_details-info-tab" data-bs-toggle="tab"
                                            data-bs-target="#material_details-info" type="button" role="tab"
                                            aria-controls="material_details-info" aria-selected="false">
                                            <i class="fa fa-file-text w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.material_details')}}

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
                                                {{__('labels.manage_complaints_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.date_time')}} :
                                                            <span class=" mr-2 ml-2">{{$complaintDetail->cDateFormate.' '.$complaintDetail->cTimeFormate?? "-" }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.complaint_user_name')}} :
                                                            <span class=" mr-2 ml-2">{{$complaintDetail->fullName?? "-" }}</span>
                                                        </div>

                                                    </div>
                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.complaint_dealer_name')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->name }}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.dealer_id')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->dealerId ?? '-'
                                                                }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.email')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cEmail ?? "-" }}</span>
                                                        </div>
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.user_type')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->fType ?? "-" }}</span>
                                                        </div>

                                                    </div>

                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.site_location')}} :
                                                            <span class=" mr-2 ml-2">{{$complaintDetail->cSiteLocation ?? "-" }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.address')}} :
                                                            <span class=" mr-2 ml-2">{{$complaintDetail->cAddress ?? "-" }}</span>
                                                        </div>

                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                           {{__('labels.state')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cSName ?? '-'
                                                                }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.city')}} :
                                                            <span class=" mr-2 ml-2">{{$complaintDetail->cCName ?? "-" }}</span>
                                                        </div>

                                                    </div>



                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.taluka')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cTName ?? '-'
                                                                }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.pincode')}} :
                                                            <span class="mr-2 ml-2">{{ $complaintDetail->cPinCode ?? '-'
                                                                }}</span>
                                                        </div>

                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.mobile_no')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cMobileNumber ?? '-'
                                                                }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.wp_mobile_no')}} :
                                                            <span class="mr-2 ml-2">{{ $complaintDetail->cWpMobileNumber ?? '-'
                                                                }}</span>
                                                        </div>

                                                    </div>
                                                    {{-- ---------------- --}}

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.comments')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cComments ?? '-'}}</span>
                                                        </div>

                                                        @php

                                                            $complaintStatusRoute = route('complaints.userStatus',  $params = ['complaints' => $complaintDetail->id]);

                                                        if($complaintDetail->cStatus == 'Pending')
                                                        {
                                                        $type = 'info';
                                                        $status = "Pending";
                                                        }else{
                                                        $type = 'success';
                                                        $status = "Solved";
                                                        }
                                                        @endphp

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.status')}} :
                                                            <span data-st="{{ $complaintDetail->cStatus }}"  data-id="{{ $complaintDetail->id }}" data-url="{{ $complaintStatusRoute }}"
                                                                class="btnChangeUserStatus mr-2 ml-2 badge badge-{{$type}}">{{$status
                                                                }}</span>
                                                        </div>



                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        @if($complaintDetail->cType == "General Complaint")
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            General Complaint Type :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->complaintType ?? '-'}}</span>
                                                        </div>
                                                        @endif

                                                        @if(!empty($complaintDetail->otherComplaint))
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            Other Complaint Text :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->otherComplaint ?? '-'}}</span>
                                                        </div>
                                                        @endif


                                                        @if($complaintDetail->cType == "Quality Complaint")
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.remarks')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cRemarks ?? '-'}}</span>
                                                        </div>
                                                        @endif



                                                    </div>

                                                    @if(!empty($complaintDetail->cPhotoVideo))
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1 complain">
                                                            {{__('labels.photo_video')}} :
                                                            <span class="mr-2 ml-2 position-rel" style="display: inline-block"><a class="fancybox" href="{{ $complaintDetail->cPhotoVideo ?? '' }}" >

                                                            <img alt="{{ config('app.name') }}" src="{{ $complaintDetail->cPhotoVideo ?? '-'
                                                            }}" height="50"></a>


                                                                @php
                                                                $redirectRoute1 = route('deleteImage',['id'=>$complaintDetail->id,'type' => 'complain' ])
                                                                @endphp

                                                                <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute1 }}');"  class="close-gen-btn">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                </a>

                                                                </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- END: Basic Info -->
                                    </div>
                                </div>

                                {{-- Material Status --}}

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
                                                <div class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.project_name_billing_detail')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cProductNameBillingDetails ??
                                                                '-' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.total_q_required')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cTotalQty ??
                                                                '-' }}</span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.lot_no')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cLotNumber ??
                                                                '-' }}</span>
                                                        </div>

                                                    </div>
                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.truck_no')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->cTruckNumber ?? '-'}}</span>
                                                        </div>
                                                    </div>


                                                    {{-- <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.pan')}} :
                                                            <span class=" mr-2 ml-2"><a class="fancybox" href="{{ $complaintDetail->panImage ?? '' }}" ><img alt="{{ config('app.name') }}" src="{{ $complaintDetail->panImage ?? '-'
                                                            }}" height="50"></a></span>
                                                        </div>

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.shopWarehouseArea')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->shopWarehouseArea ?? '-'
                                                                }}</span>
                                                        </div>

                                                    </div>

                                                    <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            {{__('labels.modeOfPayment')}} :
                                                            <span class=" mr-2 ml-2">{{ $complaintDetail->modeOfPayment ?? '-'
                                                                }}</span>
                                                        </div>

                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: user-contacts Info -->
                                    </div>
                                </div>

                                {{-- Material Details --}}
                                <div class="tab-pane fade" id="material_details-info" role="tabpanel"
                                    aria-labelledby="material_detailss-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: user-contacts Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.material_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div class="row truncate sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-md-10 offset-md-1 mb-1 mt-1">
                                                            <span class=" mr-2 ml-2">

                                                                @foreach ($materialTypeData as $key => $value)
                                                                   @if ($value->isParent == 1)

                                                                   @php
                                                                            $materialId = $value->materialId;
                                                                            $collection = collect($materialTypeData);
                                                                            $result = $collection->where('materialTypeId', $materialId );
                                                                   @endphp

                                                                   <div class="table-responsive text-center">
                                                                        <table class="table table-bordered">
                                                                            <thead class="table-secondary">
                                                                                <tr>
                                                                                    <th class="text-center">Material Name</th>
                                                                                    <th class="text-center">Total Quantity</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="text-center">{{ $value->mName }}</td>
                                                                                    <td class="text-center">{{$value->totalQty}}</td>
                                                                                </tr>
                                                                                @if(count($result) > 0)
                                                                                <tr>
                                                                                    <td colspan="2" class="p-0">
                                                                                        <table class="table mb-0 table-bordered">
                                                                                            <thead class="table-light">
                                                                                                <tr>
                                                                                                    <th class="text-center">Sub Material Type</th>
                                                                                                    <th class="text-center">Sub Material Name</th>
                                                                                                    <th class="text-center">Total Quantity</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @forelse ($result as  $subData)
                                                                                                <tr>
                                                                                                    <td class="text-center">{{$subData->msType}}</td>
                                                                                                    <td class="text-center">{{$subData->msName}}</td>
                                                                                                    <td class="text-center">{{$subData->totalQty}}</td>
                                                                                                </tr>
                                                                                                @empty

                                                                                                @endforelse
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                @endif
                                                                            </tbody>
                                                                        </table>
                                                                   </div>

                                                                   @endif

                                                                @endforeach

                                                            </span>
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

@include('admin.complaint.modal')

@endsection

@section('js')
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}"></script>
<script>


$(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending"){
            $('#userStatusChange-modal').modal('show');
            }

            var url = $(this).data('url');
            form = $('#updateStatus');
            form.attr('action', url);

            $('#dataId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');
    });


    $(document).on('change', '.requestStatus', function() {
        if($(this).val() == 'Pending')
        {
            $('.remarkTextarea').addClass('hideClass');
        }
        else
        {
            $('.remarkTextarea').removeClass('hideClass');
        }
    });


    $(document).on('click', '.resetForm', function() {

        $("#updateStatus").trigger('reset');
        $('.vehicleNumber').hide(1000);
    });


    $(function() {

        // This Function is used for validation
        $(".updateStatus").validate({
                ignore: ":hidden:not(.my_item)",
                rules: {
                    cDesc:{
                        required:true,
                    },
                },
                messages: {
                    cDesc:{
                        required:'Please enter description',
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                },
                invalidHandler: function(form, validator) {
                }
            });
    });


</script>


@endsection
