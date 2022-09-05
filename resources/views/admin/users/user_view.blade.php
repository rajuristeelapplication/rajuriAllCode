@extends('admin.layout.index')
@section('title') {{ $userDetail->roleName ?? '' }} Details @endsection
@section('css')
<link href="{{ url('css/app-all.css') }}">
<style type="text/css">


</style>
@endsection
@section('content')
@php
$admin = \Auth::guard('admin')->user();
$adminImage = !empty($admin->profileImage) ? url('images/profiles/' . $admin->profileImage) : url('images/default.jpg');
@endphp


<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('users.index',['parameter' => $roleName])}}">
            <span class="breadcrumb">
                @if($roleName == 'all')
                {{__('labels.users')}}
                @elseif($roleName == 'hr')
                {{__('labels.users_hr')}}
                @elseif($roleName == 'ma')
                {{__('labels.users_ma')}}
            @endif

            </span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active"> {{ $userDetail->roleName ?? '' }} Details</span>
    </div>
    @endsection
    @include('admin.common.notification')

    <div class="intro-y d-flex align-items-center mt-8">
        <h2 class="text-lg font-medium me-auto">
            {{ $userDetail->roleName ?? '' }} Details
        </h2>
    </div>

    @include('admin.common.flash')
    <br><br>

    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-12  grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-4">

                <div class="intro-y align-items-center mt-8">
                    <div class=" grid grid-cols-12 gap-6 mt-4">
                        <div class="col-span-12 d-flex d-lg-block">
                            <div class="intro-y box w-100">
                                <div class="position-relative d-flex align-items-center p-5">
                                    <div class="w-12 h-12 image-fit profileClass">
                                        <input type="hidden" value="{{$userDetail->id}}" name="userId" id="userId">

                                            <a class="fancybox" href="{{ $userDetail->profileImage ?? '' }}" >
                                                <img alt="{{ config('app.name') }} User Image"
                                                src="{{ $userDetail->profileImage ?? '' }}" height="50">
                                            </a>

                                            @php
                                              $redirectRoute1 = route('deleteImage',['id'=>$userDetail->id,'type' => 'profileImage' ])
                                            @endphp
{{--
                                            <a href="javascript:;"   onclick="deleteDataImage('{{ $redirectRoute1 }}');"  class="close-gen-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                            </a> --}}


                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium text-base p-5">

                                            {{ $userDetail->firstName ?? '' }}
                                            {{ $userDetail->lastName ?? '-' }}
                                        </div>

                                        <div class="text-gray-600">
                                        </div>
                                    </div>

                                    <div class="p-5 border-l border-gray-200">
                                        <div
                                            class="d-flex align-items-strat flex-column justify-content-center text-gray-600">
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center"> <i
                                                    class="feather-mail w-4 h-4 mr-2"></i> {{ $userDetail->email ?? '' }}
                                            </div>
                                            <div class="truncate sm:whitespace-normal d-flex align-items-center mt-3"> <i
                                                    class="feather-smartphone w-4 h-4 mr-2"></i>
                                                {{ $userDetail->mobileNumber ?? '-' }} </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!-- END: Profile Menu -->
                        <div class="col-span-12">
                            <div class="intro-y box col-span-12 mt-2">
                                <nav>
                                    <div class="nav nav-tabs d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start px-4"
                                        id="nav-tab" role="tablist">

                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center active"
                                            id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button"
                                            role="tab" aria-controls="posts" aria-selected="true"> <i
                                                class="feather-user w-4 h-4 mr-2" data-feather=""></i> {{__('labels.users_details')}}
                                        </button>

                                        @if($roleName == "all")
                                        <button class="nav-link py-3 px-0 sm:mr-8 d-flex align-items-center "
                                            id="pay-tab" data-bs-toggle="tab" data-bs-target="#pay" type="button"
                                            role="tab" aria-controls="pay" aria-selected="true"> <i
                                                class="fa fa-file-pdf-o w-4 h-4 mr-2" data-feather=""></i> {{__('labels.pay_slip')}}
                                        </button>
                                        @endif

                                    </div>
                                </nav>
                            </div>
                            <div class="tab-content mt-5" id="nav-tabContent">

                                <div class="tab-pane fade show active" id="posts" role="tabpanel"
                                    aria-labelledby="posts-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div class="p-5">
                                                <div class="container mt-5 mb-5">
                                                    <div class="row">


                                                            <div class="col-sm-6 d-flex align-items-center mb-2">
                                                                <label>{{__('labels.users_type')}} :</label>
                                                                <div class="ml-4 mr-auto">
                                                                    <div class="font-medium text-base">{{ $userDetail->roleName ?? '' }}</div>
                                                                </div>
                                                            </div>


                                                            @if($roleName == 'all')


                                                            <div class="col-sm-6 d-flex align-items-center mb-2">
                                                                <label>{{__('labels.dob')}} :</label>
                                                                <div class="ml-4 mr-auto">
                                                                    <div class="font-medium text-base">{{
                                                                        Carbon\Carbon::parse($userDetail->dob)->format(config('constant.admin_dob_format'))
                                                                        ?? "-" }}</div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 d-flex align-items-center mb-2">
                                                                <label>{{__('labels.address')}} :</label>
                                                                <div class="ml-4 mr-auto">
                                                                    <div class="font-medium text-base">{{
                                                                        $userDetail->address ?? '-' }}</div>
                                                                </div>
                                                            </div>


                                                            <div class="col-sm-6 d-flex align-items-center mb-2">
                                                                <label>{{__('labels.city')}} :</label>
                                                                <div class="ml-4 mr-auto">
                                                                    <div class="font-medium text-base">{{
                                                                        $userDetail->cName ?? '-' }}</div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6 d-flex align-items-center mb-2">
                                                                <label>{{__('labels.zip_code')}} :</label>
                                                                <div class="ml-4 mr-auto">
                                                                    <div class="font-medium text-base">{{
                                                                        $userDetail->zipCode ?? '-' }}</div>
                                                                </div>
                                                            </div>

                                                            @php

                                                                $userStatusRoute = route('users.userStatus', $params = ['users' => $userDetail->id]);

                                                                if( $userDetail->userStatus == 'Pending')
                                                                {
                                                                    $type = 'info';
                                                                    $status = "Pending";
                                                                }else{
                                                                    $type = 'success';
                                                                    $status = "Approved";
                                                                }
                                                             @endphp

                                                            <div class="col-sm-6 d-flex align-items-center mb-2">
                                                                <label>{{__('labels.status')}} : </label>
                                                                <div class="ml-4 mr-auto">
                                                                    <div class="font-medium text-base">
                                                                        <span  data-st="{{ $status }}" data-id="{{ $userDetail->id}}" data-url="{{ $userStatusRoute }}"
                                                                        class="badge badge-{{$type}} btnChangeUserStatus">{{$status
                                                                        }}</span></div>
                                                                </div>

                                                            </div>

                                                                @if(!empty($userDetail->photoIdProof))

                                                                    <div class="col-sm-6 d-flex align-items-center mb-2 photoIdClass">
                                                                        <label>{{__('labels.photo_id_proof')}} :</label>


                                                                        <div class="ml-4 mr-auto position-rel">
                                                                            <a class="fancybox" href="{{ $userDetail->photoIdProof ?? '' }}" ><img alt="{{ config('app.name') }} Photo Id Proof"
                                                                                src="{{ $userDetail->photoIdProof ?? '' }}"
                                                                                height="50"></a>

                                                                                    @php
                                                                                        $redirectRoute = route('deleteImage',['id'=>$userDetail->id,'type' => 'photoid' ])
                                                                                    @endphp

                                                                                    <a href="javascript:;"   onclick="deleteDataImage('{{ $redirectRoute }}');"  class="close-gen-btn">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                                    </a>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                            @endif

                                                            {{--
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        {{-- PAY SLIP --}}

                        <div class="tab-pane fade" id="pay" role="tabpanel"
                                    aria-labelledby="pay-tab">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="intro-y box col-span-12 lg:col-span-12">
                                            <div class="p-5">
                                                <div class="container mt-5 mb-5">
                                                    <div class="row">
                                                            <div class="table-responsive">
                                                                <table id="pdf"
                                                                    class="nowrap table table-hover table-striped table-bordered"
                                                                    cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{ __('labels.no') }}</th>
                                                                            <th>Month Year</th>
                                                                            <th>PDF</th>
                                                                            <th>{{ __('labels.action') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- END: Content -->

@include('admin.users.modal')

@endsection

@section('js')
<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>
<script>
    var table;
    $(function() {
        table = $('#pdf').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": '{{ url(route('users.pdfSearch')) }}',
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "async": false,
                "data": function ( d ) {
                    d.userId = $('#userId').val();
                }
            },
            aaSorting: true,
            searching: false,
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no',
                    orderable: false
                },
                {
                    data: 'month',
                    name: 'month',
                },
                {
                    data: 'payPdf',
                    name: 'payPdf',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "aaSorting": [
                [1, 'desc']
            ],
            "pageLength": 10
        });

        // filter after reload table

    });

    $(document).ready(function(){
        $('#userId').val();
    });




    $(document).on("click",'.btnChangeUserStatus',function(event) {
        event.preventDefault();
        var statusValue = $(this).attr('data-st');

            if(statusValue == "Pending"){
            $('#userStatusChange-modal').modal('show');
            }

            var url = $(this).data('url');
            form = $('#updateUserStatus');
            form.attr('action', url);

            $('#leaveId').val($(this).attr('data-id'));
            $('#typeValue').val($(this).attr('data-type'));
            $('#statusValue').val($(this).attr('data-st'));
            type = $(this).attr('data-type');
    });

</script>
@endsection
