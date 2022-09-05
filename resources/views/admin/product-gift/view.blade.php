@extends('admin.layout.index')
@section('title') {{__('labels.product_detail')}} @endsection
@section('content')

<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span
                class="___class_+?2___">{{__('labels.dashboard_navigation')}}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('products-gift.index') }}"><span class="breadcrumb">{{__('labels.product')}}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">{{__('labels.product_detail')}}</span>
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
                        {{__('labels.product_detail')}}
                    </h2>
                    <div class="d-flex ">
                        <a href="{{ route('products-gift.edit', ['products_gift' => $productInfo->id ]) }}"
                            class="ms-auto d-flex btn btn-success"> {{__('labels.edit_info')}} </a>
                        <a href="{{ route('products-gift.index') }}" class="ms-3 d-flex btn btn-danger">
                            {{__('labels.back_info')}} </a>
                    </div>
                </div>

                <div class="intro-y align-items-center mt-8">
                    <div class="gap-6 mt-4">
                        <div class="col-span-12 lg:col-span-8  ">
                            <div class="tab-content mt-5" id="nav-tabContent">

                                <div class="tab-pane fade show active" id="personal-info" role="tabpanel"
                                    aria-labelledby="personal-info-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <!-- BEGIN: personal Info -->
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div class="p-5 border-t border-gray-200">
                                                <div
                                                    class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            <b>{{__('labels.product_name')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $productInfo->pName ??
                                                                '-' }}</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="truncate sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                            <b>{{__('labels.product_type')}} :</b>
                                                            <span class=" mr-2 ml-2">{{ $productInfo->pType ??
                                                                '-' }}</span>
                                                        </div>
                                                    </div>



                                                    @if ($productInfo->isProductOption == 1)
                                                    <div class="sm:whitespace-normal d-flex align-items-center mt-3">
                                                        <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                            <div class="table-responsive text-center">
                                                                <table class="table table-bordered">
                                                                    <thead class="table-secondary">
                                                                        <tr>
                                                                            <th colspan="4" class="text-center">Product
                                                                                Option Details</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="text-center">Product Option Name
                                                                            </th>
                                                                            {{-- <th class="text-center">Total Quantity</th> --}}
                                                                            <th class="text-center">Description</th>
                                                                            {{-- <th class="text-center">Attachment</th> --}}
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($productAttributeInfo as $key =>
                                                                        $value)
                                                                        <tr>
                                                                            <td class="text-center">{{
                                                                                $value->productOptionName }}</td>
                                                                            {{-- <td class="text-center">{{$value->totalQty}}
                                                                            </td> --}}
                                                                            <td class="text-center">
                                                                                {{$value->isDescription == 1 ? 'Yes'
                                                                                :'No'}}</td>
                                                                            {{-- <td class="text-center">
                                                                                {{$value->isAttachment == 1 ? 'Yes'
                                                                                :'No'}}</td> --}}
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
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
