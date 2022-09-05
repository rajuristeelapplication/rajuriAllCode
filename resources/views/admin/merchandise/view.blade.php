@extends('admin.layout.index')
@section('title') {{ __('labels.merchandise_details') }} @endsection
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
        <a href="{{ route('merchandise.index',['type' => strtolower($merchandiseDetail->mType) ]) }}"><span class="breadcrumb">{{__('labels.merchandises') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{ __('labels.merchandise_details') }}</span>
    </div>
    @endsection
    @include('admin.common.notification')

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12  grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">
                <div class="intro-y d-flex align-items-center justify-content-between h-10">
                    <h2 class="text-lg font-medium me-4 mb-0">
                        {{ __('labels.merchandise_details') }}
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
                                        {{__('labels.dealer_name')}} : {{ $merchandiseDetail->name ?? '-' }}
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
                                            <i class="fa fa-shopping-bag w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.merchandise_details')}}
                                        </button>
                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center"
                                            id="item-info-tab" data-bs-toggle="tab" data-bs-target="#item-info"
                                            type="button" role="tab" aria-controls="item-info"
                                            aria-selected="false">
                                            <i class="fa fa-cart-arrow-down w-4 h-4 mr-2" data-feather=""></i>
                                            {{__('labels.item_details')}}
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
                                                {{__('labels.merchandise_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.merchandise_type')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $merchandiseDetail->mType }}</span>
                                                        </div>

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.created_by')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $merchandiseDetail->fullName ??
                                                                '-'
                                                                }}</span>
                                                        </div>

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{ __('labels.dealer') }} {{__('labels.id')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $merchandiseDetail->dealerId ??
                                                                '-'
                                                                }}</span>
                                                        </div>

                                                    </div>


                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">


                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.date')}} :</b>
                                                            <span class=" mr-2 ml-2">{{
                                                                $merchandiseDetail->mDateFormate ?? "-" }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.time')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $merchandiseDetail->mTimeFormate
                                                                ?? '-'
                                                                }}</span>
                                                        </div>
                                                        @php

                                                                $params = ['merchandise' => $merchandiseDetail->id];

                                                        $userStatusRoute = route('merchandise.userStatus', $params);

                                                        if( $merchandiseDetail->mStatus == 'Pending')
                                                        {
                                                            $type = 'info';
                                                        }
                                                        else if( $merchandiseDetail->mStatus == 'Approved')
                                                        {
                                                         $type = 'success';
                                                        }else{
                                                         $type = 'warning';
                                                        }
                                                        @endphp


                                                        @if($merchandiseDetail->mType == "Order")
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.status')}} :</b>
                                                           <span class="mr-2 ml-2 btnChangeUserStatus badge badge-{{$type}}"
                                                           data-st="{{ $merchandiseDetail->mStatus }}" data-id="{{ $merchandiseDetail->id }}" data-url="{{ $userStatusRoute }}"
                                                           >{{$merchandiseDetail->mStatus ?? '-' }}</span>
                                                        </div>

                                                        @endif

                                                    </div>



                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                                <b>{{__('labels.state')}} :</b>
                                                                <span class=" mr-2 ml-2">{{ $merchandiseDetail->mSName ?? '-' }}</span>
                                                        </div>

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.city')}} :</b>
                                                            <span class=" mr-2 ml-2">{{
                                                                $merchandiseDetail->mCName ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.taluka')}} :</b>
                                                            <span class=" mr-2 ml-2">{{
                                                                $merchandiseDetail->mTName ?? '-' }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">

                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.pin_code')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $merchandiseDetail->mPinCode
                                                                ?? '-' }}</span>
                                                        </div>

                                                    </div>

                                                    @if(!empty($merchandiseDetail->mPhoto))

                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3 mPhoto">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                        <b>{{__('labels.photo')}} :</b>
                                                            <span class=" mr-2 ml-2 position-rel" style="display: inline-block;margin-top:15px;"><a class="fancybox" href="{{ $merchandiseDetail->mPhoto ?? '' }}" ><img
                                                                    alt="{{ config('app.name') }} Merchandise Photo"
                                                                    src="{{ $merchandiseDetail->mPhoto ?? '' }}"
                                                                    height="50"></a>

                                                                    @php
                                                                    $redirectRoute = route('deleteImage',['id'=>$merchandiseDetail->id,'type' => 'mPhoto' ])
                                                                    @endphp


                                                                <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');"  class="close-gen-btn">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                </a>



                                                            </span>
                                                        </div>
                                                    </div>

                                                    @endif

                                                    <div class="row">
                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                         <b>{{__('labels.address')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $merchandiseDetail->mAddress ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-4 mb-1 mt-1 mr-2 ml-1">
                                                         <b>Current Location :</b>
                                                            <span class=" mr-2 ml-2">{{ $merchandiseDetail->cLocation ?? '-' }}</span>
                                                        </div>
                                                    </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- END: Basic Info -->
                                    </div>
                                </div>

                                {{-- Material Status --}}
                                <div class="tab-pane fade" id="item-info" role="tabpanel"
                                    aria-labelledby="item-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: Item Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div
                                                class="d-flex align-items-center px-4 py-4 py-sm-3 border-b border-gray-200">
                                                {{__('labels.item_details')}}
                                            </div>
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">


                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            <b>{{__('labels.item_name')}} :</b>
                                                            <span class=" mr-2 ml-2">{{
                                                                $merchandiseDetail->itemNames ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            <b>{{__('labels.item_quantity')}} :</b>
                                                            <span class=" mr-2 ml-2">{{
                                                                $merchandiseDetail->itemQty ?? '-' }}</span>
                                                        </div>
                                                    </div>

                                                    @if($merchandiseDetail->mType == "Order")


                                                    <div
                                                    class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                    <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                        <b>Total Price :</b>
                                                        <span class=" mr-2 ml-2">
                                                            @php
                                                                setlocale(LC_MONETARY, 'en_IN');
                                                            @endphp
                                                            &#8377;{{ money_format('%!i',$merchandiseDetail->totalPriceOrder) ?? 0 }}
                                                        </span>
                                                    </div>
                                                </div>


                                                    @endif

                                                    <br><br><br>



                                                    @if(!empty($merchandiseDetail->getProducts))

                                                    @forelse ($merchandiseDetail->getProducts as $key=> $getProduct)

                                                        @if($getProduct['isProductOption'] == 0)


                                                            <table class="table table-reponsive table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <td width="30%" style="white-space:nowrap"><b>
                                                                            {{$getProduct['pName'] ?? ''}}</b>
                                                                        </td>
                                                                        <td width="15%" style="white-space:nowrap">
                                                                            <div class="d-flex flex-1">
                                                                                <b>&nbsp; Qty : </b>  {{$getProduct['totalQty'] ?? ''}}
                                                                            </div>
                                                                        </td>


                                                                        @if($merchandiseDetail->mType == "Order")


                                                                        <td width="15%" style="white-space:nowrap">
                                                                            <div class="d-flex flex-1">
                                                                                <b>&nbsp; Price : </b>
                                                                                &#8377; {{ money_format('%!i',$getProduct['price']) ?? 0 }}

                                                                            </div>
                                                                        </td>

                                                                        <td width="15%" style="white-space:nowrap">
                                                                            <div class="d-flex flex-1">
                                                                                <b>Total Price : </b>
                                                                                &#8377; {{ money_format('%!i',$getProduct['totalPrice']) ?? 0 }}

                                                                            </div>
                                                                        </td>

                                                                        @endif

                                                                        @if(!empty($getProduct['orderDesc']))
                                                                            <td width="25%" style="white-space:nowrap">
                                                                                <div class="d-flex flex-1">
                                                                                 <b>Description: </b>  &nbsp; &nbsp;<span> {{ $getProduct['orderDesc'] }}</span>
                                                                                </div>
                                                                            </td>
                                                                            @else
                                                                            <td></td>
                                                                         @endif
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        @endif


                                                        @if($getProduct['isProductOption'] == 1)

                                                        <table class="table table-reponsive table-bordered">
                                                            <tr>
                                                                <td colspan="5" width="30%" style="white-space:nowrap">
                                                                <b>{{$getProduct['pName'] ?? ''}} {{ ' ('. $getProduct['totalQty'] .')' ?? 0}}</b>
                                                                </td>
                                                            </tr>

                                                            @forelse ($getProduct['productOptions']  as  $key1 => $productOption)
                                                            <tr>
                                                                <td width="30%" style="white-space:nowrap">
                                                                <b>{{$productOption['productOptionName'] ?? ''}}</b>
                                                                </td>
                                                                <td width="10%" style="white-space:nowrap">
                                                                    <div class="row">
                                                                    <div class="d-flex flex-1">
                                                                        <b>&nbsp; Qty : </b> {{$productOption['totalQty'] ?? ''}}
                                                                    </div>
                                                                </td>

                                                                @if($merchandiseDetail->mType == "Order")


                                                                <td width="10%" style="white-space:nowrap">
                                                                    <div class="d-flex flex-1">
                                                                        <b>&nbsp; Price : </b>  {{$getProduct['price'] ?? ''}}
                                                                    </div>
                                                                </td>

                                                                <td width="20%" style="white-space:nowrap">
                                                                    <div class="d-flex flex-1">
                                                                        <b>Total Price : </b>  {{$getProduct['totalPrice'] ?? ''}}
                                                                    </div>
                                                                </td>

                                                                @endif


                                                                    @if(!empty($productOption['orderDesc']))
                                                                        <td style="white-space:nowrap">
                                                                        <div class="d-flex flex-1">
                                                                        <b>Description: </b>  &nbsp; &nbsp;<span> {{$productOption['orderDesc'] ?? ''}}</span>
                                                                        </div>
                                                                        </td>
                                                                     @endif
                                                                    </div>




                                                            </tr>

                                                            @empty

                                                            @endforelse

                                                        </table>


                                                        @endif


                                                    @empty

                                                    @endforelse

                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: Item Info -->
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
@include('admin.merchandise.modal')
@endsection

@section('js')


<script>

$(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending" || statusValue == "Approved"){
            $('#userStatusChange-modal').modal('show');
            }

            //Disabled Value When Status Is Approved
            if (statusValue == "Approved") {
                $("#userStatusChange-modal option[value='Pending']").attr("disabled","disabled");
                $("#userStatusChange-modal option[value='Rejected']").attr("disabled","disabled");
            }

            var url = $(this).data('url');
            form = $('#updateStatus');
            form.attr('action', url);

            $('#dataId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');

            $("#requestStatus").val($(this).attr('data-st')); //Dropdown Value Selected
    });

</script>

@endsection
