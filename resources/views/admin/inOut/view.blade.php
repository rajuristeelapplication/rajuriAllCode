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
                                            <i class="feather-file-text w-4 h-4 mr-2" data-feather=""></i>
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
                                                <div class="d-flex align-items-strat flex-column justify-content-center text-gray-600">

                                                    <br><br>

                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-6 b-r">
                                                            <label class="font-medium ">  IN Location  :</label>
                                                            <br><br>
                                                            <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                                {{__('labels.inAddress')}} :
                                                                <span class=" mr-2 ml-2">{{$inOutDetail->inAddress?? "-"
                                                                    }}</span>
                                                            </div>

                                                            <br><br>

                                                            <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                                {{__('labels.inDateTime')}} :
                                                                <span class=" mr-2 ml-2">{{$inOutDetail->inDateTime?? "-"
                                                                    }}</span>
                                                            </div>
                                                            <br>

                                                            @if(!empty($inOutDetail->inReadingPhoto))

                                                             @php
                                                               $redirectRoute = route('deleteImage',['id'=>$inOutDetail->id,'type' => 'inAttendande' ])
                                                             @endphp

                                                            <div class="col-6 mb-1 mt-1 mr-2 ml-1 inAttendande">
                                                                {{__('labels.inReadingPhoto')}} :
                                                                <span class=" mr-2 ml-2 position-rel" style="display: inline-block"><a class="fancybox"
                                                                        href="{{ $inOutDetail->inReadingPhoto ?? '' }}">
                                                                        <img alt="{{ config('app.name') }} In Reading Photo"
                                                                            src="{{ $inOutDetail->inReadingPhoto ?? '' }}"
                                                                            height="50"></a>

                                                                            <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute }}');"  class="close-gen-btn">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                            </a>

                                                                        </span>
                                                            </div>



                                                            @endif


                                                            <br><br>
                                                            @if(!empty($inOutDetail->inLatitude))

                                                            <div id="inmap" style="width: 100%; height: 400px; background-color: grey;"></div>
                                                            @else

                                                            <p>No Location Found</p>
                                                            @endIf
                                                        </div>

                                                        @if(!empty($inOutDetail->outAddress))

                                                        <div class="col-md-6 col-xs-6 b-r">
                                                            <label class="font-medium ">  OUT Location  :</label>
                                                            <br><br>
                                                            <div class="col-12 mb-1 mt-1 mr-2 ml-1">
                                                                {{__('labels.outAddress')}} :
                                                                <span class=" mr-2 ml-2">{{$inOutDetail->outAddress?? "-"
                                                                    }}</span>
                                                            </div>
                                                            <br><br>

                                                            <div class="col-6 mb-1 mt-1 mr-2 ml-1">
                                                                {{__('labels.outDateTime')}} :
                                                                <span class=" mr-2 ml-2">{{$inOutDetail->outDateTime?? "-"
                                                                    }}</span>
                                                            </div>
                                                            <br>

                                                            @if($inOutDetail->outReadingPhoto)


                                                            @php
                                                              $redirectRoute1 = route('deleteImage',['id'=>$inOutDetail->id,'type' => 'outAttendande' ])
                                                            @endphp

                                                            <div class="col-6 mb-1 mt-1 mr-2 ml-1 outAttendande">
                                                                {{__('labels.outReadingPhoto')}} :
                                                                <span class=" mr-2 ml-2 position-rel" style="display: inline-block"><a class="fancybox"
                                                                        href="{{ $inOutDetail->outReadingPhoto ?? '' }}"><img
                                                                            alt="{{ config('app.name') }} Out Reading Photo"
                                                                            src="{{ $inOutDetail->outReadingPhoto ?? '' }}"
                                                                            height="50"></a>

                                                                            <a href="javascript:;" onclick="deleteDataImage('{{ $redirectRoute1 }}');"  class="close-gen-btn">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8.65 17.2 12 13.85 15.35 17.2 17.2 15.35 13.85 12 17.2 8.65 15.35 6.8 12 10.15 8.65 6.8 6.8 8.65 10.15 12 6.8 15.35ZM12 23.15Q9.675 23.15 7.638 22.288Q5.6 21.425 4.088 19.913Q2.575 18.4 1.713 16.362Q0.85 14.325 0.85 12Q0.85 9.675 1.713 7.637Q2.575 5.6 4.088 4.087Q5.6 2.575 7.638 1.712Q9.675 0.85 12 0.85Q14.325 0.85 16.363 1.712Q18.4 2.575 19.913 4.087Q21.425 5.6 22.288 7.637Q23.15 9.675 23.15 12Q23.15 14.325 22.288 16.362Q21.425 18.4 19.913 19.913Q18.4 21.425 16.363 22.288Q14.325 23.15 12 23.15ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 19.75Q15.225 19.75 17.488 17.5Q19.75 15.25 19.75 12Q19.75 8.75 17.488 6.5Q15.225 4.25 12 4.25Q8.775 4.25 6.513 6.5Q4.25 8.75 4.25 12Q4.25 15.25 6.513 17.5Q8.775 19.75 12 19.75Z"/></svg>
                                                                            </a>


                                                                        </span>
                                                            </div>
                                                            @endif
                                                            <br><br>

                                                            @if(!empty($inOutDetail->outLongitude))

                                                            <div id="outmap" style="width: 100%; height: 400px; background-color: grey;"></div>
                                                            @else
                                                              <p>No Location Found</p>
                                                            @endIf
                                                        </div>

                                                        @endif


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
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>


<script type="text/javascript">



    //This code is help full for display map
    var latt = {{ !empty($inOutDetail->inLatitude) ? $inOutDetail->inLatitude:-33.8688 }}
    var langg ={{ !empty($inOutDetail->inLongitude) ? $inOutDetail->inLongitude:151.2195 }}

    var lattOut = {{ !empty($inOutDetail->outLatitude)  ? $inOutDetail->outLatitude :-33.8688 }}
    var langgOut ={{ !empty($inOutDetail->outLongitude) ? $inOutDetail->outLongitude :151.2195 }}

    function initInMap() {
        // The location of Uluru
        var location = {lat: latt, lng: langg};

        // The map, centered at Uluru
        var map = new google.maps.Map( document.getElementById('inmap'), {zoom:16, center: location});
        var service = new google.maps.places.PlacesService(map);
        var geocoder = new google.maps.Geocoder();
        // var infowindow = new google.maps.InfoWindow();
        // var infowindow = new google.maps.InfoWindow();

        const contentString = `<div> {{ $inOutDetail->inAddress }}</div>`;
        const infowindow = new google.maps.InfoWindow({
            content: contentString,
        });

        geocoder.geocode(
        {
          location: location
        },
        function(results, status) {
          if (status === "OK") {
            if (results[0]) {


                // console.log(infowindow);

              var marker = new google.maps.Marker({
                position: location,
                map: map,
                zoom:14,
                animation: google.maps.Animation.DROP,
              });

              google.maps.event.addListener(marker, 'click', function(event) {
                        infowindow.open({
                        anchor: marker,
                        map,
                        shouldFocus: false,
                        });
                });
            } else {
              window.alert("No results found");
            }
          } else {
            window.alert("Geocoder failed due to: " + status);
          }
        }
      );
      @if(!empty($inOutDetail->outLatitude))
      {

          initOutMap();
      }
      @endif
    }

    function initOutMap() {
        // The location of Uluru
        var location2 = {lat: lattOut, lng: langgOut};
        // The map, centered at Uluru
        var outMap = new google.maps.Map( document.getElementById('outmap'), {zoom:16, center: location2});
        var service = new google.maps.places.PlacesService(outMap);
        var geocoder = new google.maps.Geocoder();
        // var infowindow = new google.maps.InfoWindow();

        const contentString1 = `<div> {{ $inOutDetail->outAddress }}</div>`;
        const infowindow = new google.maps.InfoWindow({
            content: contentString1,
        });

        geocoder.geocode(
        {
          location: location2
        },
        function(results, status) {
          if (status === "OK") {
            if (results[0]) {

              var marker = new google.maps.Marker({
                position: location2,
                map: outMap,
                zoom:14,
                animation: google.maps.Animation.DROP,
              });


              google.maps.event.addListener(marker, 'click', function(event) {
                        infowindow.open({
                        anchor: marker,
                        outMap,
                        shouldFocus: false,
                        });
                });

            } else {
              window.alert("No results found");
            }
          } else {
            window.alert("Geocoder failed due to: " + status);
          }
        }
      );

    }

    function toggleBounce() {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }


</script>



@if(!empty($inOutDetail->inLatitude))
{{-- AIzaSyCdXBkcV7rut1b3uMqOU7jv5Dq1bFo1r1w --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWF9wFs2DV5_Ywpme7CSJg4F1wWOg0yK4&callback=initInMap&libraries=places&v=weekly" defer></script>
@else
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWF9wFs2DV5_Ywpme7CSJg4F1wWOg0yK4&callback=initOutMap&libraries=places&v=weekly" defer></script>
@endif


@endsection
