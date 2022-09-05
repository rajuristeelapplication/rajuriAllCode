@extends('admin.layout.index')
@section('css')

<style>
    #map-canvas {
        /* height: calc(100vh - 150px); */
        height: calc(100% - 50px);
        margin: 0px;
        padding: 0px
    }

    .right-popup {
        width: 395px;
        position: absolute;
        top: 110px;
        z-index: 9;
        right: 70px;
        transition: all 0.3s;
    }

    #dismiss {
        color: #ffffff;
        width: 30px;
        height: 30px;
        line-height: 32px;
        text-align: center;
        background: #ea7b91;
        position: absolute;
        top: 1px;
        cursor: pointer;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
        outline: none !important;
        border-radius: 50%;
        left: 4px;
    }

    .popup-main {
        background-color: #ededed;
        width: 360px;
        position: relative;
        z-index: 9992;
        float: right;
        -webkit-box-shadow: 0 0 20px rgb(0 0 0 / 20%);
        box-shadow: 0 0 20px rgb(0 0 0 / 20%);
        border: 1px solid #ddd;
        clear: both;
        padding: 0;
    }

    .popup-inner-top {
        background-color: #ea7b91;
        padding: 16px 16px 0 16px;
        width: 358px;
    }

    .popup-inner-top-title {
        height: 64px;
        border-bottom: 1px solid #741628;
        background-color: transparent;
        padding: 0px;
    }

    .popup-inner-top-title-left {
        height: 40px;
        width: 300px;
        display: table;
        float: left;
    }

    .avatar,
    .info {
        display: table-cell;
        vertical-align: middle;
        float: left;
    }

    .avatar i {
        font-size: 26px;
        color: #FFF;
        margin-right: 10px;
    }

    .info {
        display: table-cell;
        vertical-align: middle;
    }

    .popup-inner-top .caption {
        color: #fff;
        font-size: 15px;
        line-height: 15px;
        font-weight: 400;
        display: block;
        padding-bottom: 8px;
    }

    .caption2 {
        color: #fff;
        font-size: 11px;
        line-height: 4px;
        font-weight: 400;
        display: block;
    }

    .location {
        color: #fff !important;
        font-size: 11px;
        line-height: 23px;
        font-weight: 400;
        display: block;
    }

    .popup-inner-top-title-bottom {
        padding-top: 10px;
        height: 104px;

    }

    .popup-inner-top-title-bottom-main {
        display: table;
    }

    .popup-inner-top-title-bottom-main-left {
        float: left;
        width: 95px;
        display: table-cell;
        font-size: 13px;
        text-align: center;
        color: #fff;
        border-right: 1px solid #741628;
        margin-right: 8px;
        height: 68px;
    }

    .arc {
        padding: 1px 0 4px 0;
        margin-top: -4px;
    }

    .GaugeMeter {
        position: relative;
        text-align: center;
        overflow: hidden;
        cursor: default;
        margin-bottom: -5px;
        margin-left: 16px;
    }

    .GaugeMeter span,
    .GaugeMeter b {
        margin: 0 23%;
        width: 54%;
        position: absolute;
        text-align: center;
        display: inline-block;
        color: rgba(0, 0, 0, .8);
        font-weight: 500;
        font-family: 'Open Sans', sans-serif;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .GaugeMeter span {
        font-size: 14.76px !important;
    }

    .GaugeMeter b {
        color: black;
        font-weight: 200;
        font-size: 0.85em;
        opacity: .8;
    }

    .popup-inner-top-title-bottom-main-right {
        float: left;
        width: 74px;
        display: table-cell;
        font-size: 13px;
        text-align: center;
        color: #fff;
    }

    .arc2 {
        padding: 1px 0 5px 0;
    }

    .arc2 i {
        font-size: 24px;
    }

    .count {
        font-size: 16px;
        font-weight: 400;
    }

    .arc_time {
        padding: 1px 0 4px 0;
        font-size: 11px;
    }

    .timeline_title {
        height: 35px;
        padding-left: 16px;
        line-height: 35px;
        font-size: 14px;
        font-weight: 400;
        text-align: left;
        color: #333111;
        background-color: #ffffff;
    }

    .date_block {
        float: right;
        border: 0px;
        padding: 7px 16px 0 0;
    }

    .date_block .input-small {
        width: 120px;
        padding-left: 11px;
    }

    .date_block .form-control {
        background-color: transparent;
        font-size: 13px;
        border: 0px;
        height: auto;
        padding: 0px 0px;
        color: #333333;
    }

    .date_block span.input-group-btn {
        width: 12px;
    }

    .date_block .btn {
        padding: 4px 10px;
        border: 0px;
        color: #741628;
        font-size: 11px;
        line-height: 11px;
        text-align: right;
        background-color: transparent;
    }

    .form-control:focus {
        box-shadow: none;
        outline: none;
    }

    .scroller {
        padding: 0px;
        margin: 0px;
        overflow: hidden;
    }

    .popup-inner-body .scroller {
        max-height: 390px;
        height: 390px;
        overflow-y: auto !important;
    }

    .popup-inner-body .scroller::-webkit-scrollbar {
        width: 6px;
        background-color: transparent;
    }

    .popup-inner-body .scroller::-webkit-scrollbar-thumb {
        background: #aaa;
        border-radius: 5px;
    }

    .timeline {
        position: relative;
        max-width: 358px;
        margin: 0 auto;
        background-color: #ededed;
    }

    .container_time {
        padding: 10px 30px;
        position: relative;
        width: 340px;
    }

    .timeline .right,
    .timeline .travel_arrow {
        left: 8%;
    }

    .timeline:nth-child(1) .right::before {
        content: " ";
        height: 0;
        position: absolute;
        top: 18px;
        width: 0;
        z-index: 1;
        left: 20px;
        border: medium solid white;
        border-width: 10px 10px 10px 0;
        border-color: transparent white transparent transparent;
    }

    .timeline:nth-child(1) .content_time::after {
        content: "";
        position: absolute;
        width: 3px;
        background-color: #ffffff;
        display: block;
        overflow: auto;
        top: 0px;
        bottom: 1px;
        left: -32px;
        float: left;
    }

    .content_time,
    .content_time_start,
    .content_time_end {
        background-color: white;
        position: relative;
        border-radius: 3px;
    }

    .cnt_dtl {
        padding: 3px 15px 2px 15px;
    }

    .content_time h5,
    .travel_time h5,
    .content_time_end h5,
    .content_time_start h5 {
        color: #333;
        font-weight: 600;
        font-size: 13px;
    }

    .content_time h5 span,
    .travel_time h5 span,
    .content_time_end h5 span,
    .content_time_start h5 span {
        float: right;
        text-align: right;
        color: #333;
        font-weight: 500;
        font-size: 13px;
    }

    .content_time p,
    .content_time_end p {
        color: #16a6df;
        font-weight: 600;
        font-size: 13px;
    }

    .cnt_dtl p {
        margin-top: 20px;
    }

    .timeline:nth-child(1) .content_time:first-child::after {
        content: "";
        position: absolute;
        width: 3px;
        background-color: #ffffff;
        display: block;
        overflow: auto;
        top: 12px;
        bottom: -50px;
        left: -32px;
        float: left;
    }

    .punchin:after {
        content: '';
        position: absolute;
        width: 31px;
        height: 31px;
        right: -17px;
        background-repeat: no-repeat;
        background-image: url(http://14.99.147.156:8888/rajuri-steel/public/images/punch-in.png);
        top: 12px;
        z-index: 1;
    }

    .timeline .right::after {
        left: -16px;
    }

    .container_travel {
        padding: 10px 30px;
        position: relative;
        width: 340px;
    }

    .travel_time {
        padding: 3px 15px 2px 15px;
        background-color: transparent;
        position: relative;
        border-radius: 3px;
    }

    .timeline:nth-child(1) .travel_time::after {
        content: "";
        position: absolute;
        width: 3px;
        background-color: #ffffff;
        display: block;
        overflow: auto;
        top: -21px;
        bottom: -22px;
        left: -32px;
        float: left;
    }

    .container_travel::after {
        content: '';
        position: absolute;
        width: 31px;
        height: 31px;
        right: -17px;
        background-repeat: no-repeat;
        background-image: url(http://14.99.147.156:8888/rajuri-steel/public/images/travel.png);
        top: 12px;
        z-index: 1;
    }

    .travel_arrow::after {
        left: -16px;
    }

    .unplan_comp:after {
        content: '';
        position: absolute;
        width: 31px;
        height: 31px;
        right: -17px;
        background-repeat: no-repeat;
        background-image: url(http://14.99.147.156:8888/rajuri-steel/public/images/unplan_comp.png);
        top: 12px;
        z-index: 1;
    }

    .unknown:after {
        content: '';
        position: absolute;
        width: 31px;
        height: 31px;
        right: -17px;
        background-repeat: no-repeat;
        background-image: url(http://14.99.147.156:8888/rajuri-steel/public/images/unknown.png);
        top: 12px;
        z-index: 1;
    }


    .dropdown-menu.other {
        padding: 0 0 4px 0;
        border-radius: 4px;
        min-width: 300px px;
        max-width: 300px;
        width: 300px;
        background-color: #ffffff;
    }

    .container_time .dropdown-menu {
        left: -40px !important;
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        color: #ccc;
        display: block;
        position: absolute;
        margin: 11px 2px 4px 10px;
        width: 16px;
        height: 16px;
        font-size: 16px;
        text-align: center;
    }

    .popup-inner-body .dropdown-menu .slimScrollDiv {
        margin: 4px 0 !important;
    }

    .popup-inner-body .dropdown-menu .scroller {
        max-height: 120px;
        height: 120px;
    }

    .container_time .dropdown-menu li>a {
        color: #333;
        text-decoration: none;
        float: left;
        clear: both;
        font-weight: normal;
        line-height: 18px;
        white-space: nowrap;
        padding-left: 9px;
        width: 298px;
        border-bottom: 1px solid #ddd;
    }

    .map_block {
        height: 30px;
        width: 269px;
        display: table;
        float: left;
    }

    .map,
    .company {
        display: table-cell;
        vertical-align: middle;
        float: left;
    }

    .company {
        margin-left: 7px;
    }

    .cname {
        color: #333 !important;
        font-size: 13px;
        line-height: 14px;
        font-weight: 600;
        display: block;
    }

    .address {
        color: #aaa !important;
        font-size: 11px;
        font-weight: 400;
        width: 243px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .or {
        text-align: center;
        display: block;
        border-top: 1px solid #dddddd;
        border-bottom: 1px solid #dddddd;
        background-color: #eee;
    }
</style>

@endsection
@section('title') Track Map @endsection
@section('content')

<!-- BEGIN: Content -->
<div class="content">
    <!-- BEGIN: Top Bar -->

    @section('navigation')
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <a href="{{ route('AdminDashboard') }}"><span class="">{{ __('labels.dashboard_navigation') }}</span></a>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('schedules.index') }}"><span
                class="breadcrumb--active">{{__('labels.manage_schedules')}}</span></a>
    </div>
    @endsection
    @include('admin.common.notification')
    @include('admin.common.flash')


    <!-- END: Top Bar -->

    <div id="map-canvas"></div>


    <div class="right-popup">
        <div id="dismiss" class="hideTimeLineClass">
            <i class="feather-x"></i>
        </div>
        <div class="popup-main">
            <div class="popup-inner">
                <div class="popup-inner-top">
                    <div class="popup-inner-top-title">
                        <div class="popup-inner-top-title-left">
                            <div class="avatar">
                                <i class="feather-user"></i>
                            </div>
                            <div class="info">
                                <div class="caption">{{ $userDetails->fullName ?? ''}}</div>
                                <div class="caption2">Punched-in @ {{ date('h:i A', strtotime($tlEvents[0]['createdAt'])) ?? '' }}</div>

                                @if(!empty($tlEvents[$totalRecordtlEvent]['createdAt']))
                                <div class="location"><i class="feather-map-pin"></i>
                                    {{ $tlEvents[$totalRecordtlEvent]['title']  ?? ''}} @ {{ date('h:i A', strtotime($tlEvents[$totalRecordtlEvent]['createdAt'])) ?? '' }}
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="popup-inner-top-title-bottom">
                        <div class="popup-inner-top-title-bottom-main">
                            {{-- <div class="popup-inner-top-title-bottom-main-left">
                                <div class="arc">
                                    <div class="GaugeMeter gaugeMeter" id="PreviewGaugeMeter_2" data-percent="" data-append=" %" data-size="58" data-theme="White" data-back="RGBa(0,0,0,.2)" data-animate_gauge_colors="true" data-animate_text_colors="true" data-width="2" data-label="" data-label_color="#FFF" data-id="PreviewGaugeMeter_2" style="width: 58px;">
                                        <span style="line-height: 58px; font-size: 12.76px; color: rgb(255, 255, 255);">100<u> %</u></span>
                                        <b style="line-height: 80.3077px; color: rgb(255, 255, 255);"></b>
                                        <canvas width="58" height="58"></canvas>
                                    </div>
                                </div>
                                <span>FTR&nbsp;<i class="feather-info" aria-hidden="true" title="Field Time Ratio (FTR) is a percentage of the total time spent on field to the total working hours."></i></span>
                            </div> --}}
                            {{-- <div class="popup-inner-top-title-bottom-main-right">
                                <div class="arc2"><i class="feather-briefcase"></i></div>
                                <span class="count">0</span>
                                <span>Visits</span>
                                <div class="arc_time">0 min</div>
                            </div> --}}
                            <div class="popup-inner-top-title-bottom-main-right">
                                <div class="arc2"><i class="feather-trending-up"></i></div>
                                <span class="count">{{ $travelKm ?? '' }}  kms</span>
                                {{-- <span></span> --}}
                                <div class="arc_time">{{ $dynamicHoursAndMin ?? '' }}</div>
                            </div>


                            {{-- <div class="popup-inner-top-title-bottom-main-right">
                                <div class="arc2"><i class="feather-map-pin"></i></div>
                                <span class="count"></span>
                                <span>Office</span>
                                <div class="arc_time">0 min</div>
                            </div> --}}
                        </div>

                    </div>
                </div>
                <div class="popup-inner-body">
                    {{-- <div class="date_block">
                        <div class="input-group date date-picker input-small" data-date-format="dd M yyyy">
                            <input type="date" class="form-control" value="" id="id_activityForDate">
                        </div>
                    </div> --}}
                    <div class="timeline_title">TIMELINE</div>
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                        <div class="scroller" data-always-visible="1" data-rail-visible1="1" style="overflow: hidden; width: auto;">
                            <div class="timeline">


                                @forelse ($tlEvents as $key=>$tlEvent)

                                    @if($tlEvent->event == "PUNCH_IN" || $tlEvent->event == "PUNCH_OUT")
                                        <div class="container_time right punchin">
                                            <div class="content_time">
                                                <div class="cnt_dtl">
                                                    <h5>
                                                        @if($tlEvent->event == "PUNCH_IN")
                                                        PUNCH IN
                                                        @endif
                                                        @if($tlEvent->event == "PUNCH_OUT")
                                                        PUNCH OUT
                                                        @endif

                                                        <span>{{ date('h:i A', strtotime($tlEvent->startDateTime)) ?? '' }}</span></h5>
                                                    <p>{{ $tlEvent->address ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($tlEvent->event == "TRAVEL")
                                        <div class="container_travel travel_arrow">
                                            <div class="travel_time">
                                                <h5>Travel ({{ !empty($tlEvent->distanceKm) ? $tlEvent->distanceKm : 0 }} km)

                                                    <span>

                                                    @if(!empty($tlEvent->startDateTime))
                                                        {{ date('h:i A', strtotime($tlEvent->startDateTime)) ?? '' }}
                                                    @endif

                                                    @if(!empty($tlEvent->endDateTime))
                                                     - {{ date('h:i A', strtotime($tlEvent->endDateTime)) ?? '' }}

                                                     <br>
                                                     @php
                                                            $start  = new \Carbon\Carbon($tlEvent->startDateTime);
                                                            $end    = new \Carbon\Carbon($tlEvent->endDateTime);

                                                            $timeString =  $start->diff($end)->format('(%H hr %I min)');

                                                            echo $timeString;
                                                     @endphp

                                                    @endif

                                                </span></h5>
                                            </div>
                                        </div>
                                    @endif

                                    @if($tlEvent->event == "UNPLANNED_STOP" && $tlEvent->diffMinutes > 3)
                                    <div class="container_time right unplan_comp">
                                        <div class="content_time">
                                            <div class="cnt_dtl">

                                                <h5>
                                                    UNPLANNED STOP
                                                    <span>
                                                 @if(!empty($tlEvent->startDateTime))
                                                        {{ date('h:i A', strtotime($tlEvent->startDateTime)) ?? '' }}
                                                @endif

                                             @if(!empty($tlEvent->endDateTime))
                                                    - {{ date('h:i A', strtotime($tlEvent->endDateTime)) ?? '' }}
                                              <br>
                                              @php
                                                     $start  = new \Carbon\Carbon($tlEvent->startDateTime);
                                                     $end    = new \Carbon\Carbon($tlEvent->endDateTime);

                                                     $timeString =  $start->diff($end)->format('(%H hr %I min)');

                                                     echo $timeString;
                                              @endphp

                                             @endif

                                                </span></h5>
                                                <div class="dropdown" id="switch-boards">
                                                    <p>{{ $tlEvent->address ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if( $tlEvent->event == "GPS_OFF")
                                    <div class="container_time right unplan_comp">
                                        <div class="content_time">
                                            <div class="cnt_dtl">

                                                <h5>
                                                    GPS OFF  ({{ !empty($tlEvent->distanceKm) ? $tlEvent->distanceKm : 0 }} km)
                                                    <span>
                                                 @if(!empty($tlEvent->startDateTime))
                                                        {{ date('h:i A', strtotime($tlEvent->startDateTime)) ?? '' }}
                                                @endif

                                             @if(!empty($tlEvent->endDateTime))
                                                    - {{ date('h:i A', strtotime($tlEvent->endDateTime)) ?? '' }}
                                              <br>
                                              @php
                                                     $start  = new \Carbon\Carbon($tlEvent->startDateTime);
                                                     $end    = new \Carbon\Carbon($tlEvent->endDateTime);

                                                     $timeString =  $start->diff($end)->format('(%H hr %I min)');

                                                     echo $timeString;
                                              @endphp

                                             @endif

                                                </span></h5>
                                                <div class="dropdown" id="switch-boards">
                                                    <p>{{ $tlEvent->address ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif



                                @empty

                                @endforelse


{{--
                                <div class="container_time right punchin">
                                    <div class="content_time">
                                        <div class="cnt_dtl">
                                            <h5>PUNCH-IN <span>08:47 am</span></h5>
                                            <p> GAT NO 41, OLD SURVEY, NO. 12, 1A, Akola Rd, near AMRUT PETROL PUMP, Irrigation Colony, Khamgaon, Maharashtra 444303, India
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="container_travel travel_arrow">
                                    <div class="travel_time">
                                        <h5>Travel (52.19 km) <span>08:47 am - 09:50 am<br>(1 hr 3 min)</span></h5>
                                    </div>
                                </div>
                                <div class="container_time right unplan_comp">
                                    <div class="content_time">
                                        <div class="cnt_dtl">
                                            <h5>UNPLANNED STOP <span>09:50 am - 10:19 am<br>(29 min)</span></h5>
                                            <div class="dropdown" id="switch-boards">
                                                <p><a onclick="getLocationOnClick(20.4571163,76.9425091,514458,'2432078',this,event)" href="#">Click here to Show Address</a></p>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container_travel travel_arrow">
                                    <div class="travel_time">
                                        <h5>Travel (30.20 km) <span>10:19 am - 11:05 am<br>(46 min)</span></h5>
                                    </div>
                                </div>
                                <div class="container_time right unplan_comp">
                                    <div class="content_time">
                                        <div class="cnt_dtl">
                                            <h5>UNPLANNED STOP <span>11:05 am - 11:30 am<br>(25 min)</span></h5>
                                            <div class="dropdown" id="switch-boards">
                                                <p><a onclick="getLocationOnClick(20.238137,77.0025157,514458,'2432724',this,event)" href="#">Click here to Show Address</a></p>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container_travel travel_arrow">
                                    <div class="travel_time">
                                        <h5>Travel (2.83 km) <span>11:30 am - 11:33 am<br>(3 min)</span></h5>
                                    </div>
                                </div>
                                <div class="container_time right unknown">
                                    <div class="content_time_end">
                                        <div class="cnt_dtl">
                                            <h5>LAST KNOWN LOCATION <span> 11:33 am</span></h5>
                                            <p><a onclick="getLocationOnClick(20.238137,77.0025157,514458,'2432724',this,event)" href="#">Click here to Show Address</a></p>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
<!-- END: Content -->

@endsection

@section('js')

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdXBkcV7rut1b3uMqOU7jv5Dq1bFo1r1w"></script> --}}

<script>
    function initialize() {
        // var mapOptions = {
        //     zoom: 3,
        //     center: new google.maps.LatLng(0, -180),
        //     mapTypeId: google.maps.MapTypeId.TERRAIN
        // };

        var r = [];
        var mapOptions = {
            scrollwheel: true,
            zoom: 1,
            center: new google.maps.LatLng(21.7679, 78.8718),
            // mapTypeId: google.maps.MapTypeId.ROADMAP
            mapTypeId: google.maps.MapTypeId.roadmap
        };

        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        r.push("{{ $string }}");

        // var r=['23.0285053,72.60027339999999|23.0263517,72.58190130000001|23.011157,72.5630675'];

        // console.log(r);
        // console.log(r1);

        // ['23.0285053,72.60027339999999|23.0263517,72.58190130000001|23.011157,72.5630675']
        // var r=['28.7041,77.1025|22.9734,78.6569|22.2587,71.1924|21.7645,72.1519'];
        // var r = ['23.0285053,72.60027339999999|23.0263517,72.58190130000001|23.011157,72.5630675|23.0523938,72.63101619999999|23.0499889,72.6699673|23.0145339,72.59294009999999|23.0363768,72.5466727|23.1012966,72.54070519999999|23.04679339999999,72.5310299|23.011157,72.5630675|23.0808812,72.57679259999999|22.995165,72.604097|23.0894723,72.68517969999999|23.003922,72.54606869999999'];

        // var r=['-12.040397656836609,-77.03373871559225|-12.040248585302038,-77.03993927003302|-12.050047116528843,-77.02448169303511|-12.044804866577001,-77.02154422636042'];
        var coordinates = r[0].split("|");
        var flightPlanCoordinates = new Array();
        var bounds = new google.maps.LatLngBounds();

        //Create and open InfoWindow.
        var infoWindow = new google.maps.InfoWindow();

        // Define a symbol using SVG path notation, with an opacity of 1.
        const lineSymbol = {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
            scale: 1.5,
            strokeColor: "#0f0f10",
        };

        var baseUrl = "{{ url('') }}";


        for (i = 0; i < coordinates.length; i++) {
            var point = new google.maps.LatLng(coordinates[i].split(',')[0], coordinates[i].split(',')[1]);

            // console.log(coordinates.length);

            bounds.extend(point);
            flightPlanCoordinates.push(point);

            // var markerImage = 'http://localhost/rajuri-steel/public/admin-assets/images/logo/marker.png';
            // console.log(baseUrl);
            // console.log(coordinates.length);

            var markerImage = "";

            if (i == 0 || coordinates.length == 1) {
                markerImage = baseUrl+'/admin-assets/images/logo/start.png';
            } else if (i + 1 == coordinates.length) {
                markerImage = baseUrl+'/admin-assets/images/logo/end.png';
            } else {
                markerImage = baseUrl+'/admin-assets/images/logo/ping.png';
            }


            var marker = new google.maps.Marker({
                position: point,
                map: map,
                title: coordinates[i].split(',')[2],
                icon: {
                    url: markerImage,
                    // size: new google.maps.Size(20, 20), //marker image size
                    // origin: new google.maps.Point(0, 0), // marker origin
                    // anchor: new google.maps.Point(35, 86) // X-axis value (35, half of marker width) and 86 is Y-axis value (height of the marker).
                }

                // label: {
                //     text: "5409 Madison St",
                // }
            });

            //Attach click event to the marker.
            //  (function (marker) {
            //     google.maps.event.addListener(marker, "click", function (e) {
            //         //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
            //         infoWindow.setContent("<div style = 'width:200px;min-height:40px'>Title</div>");
            //         infoWindow.open(map, marker);
            //     });
            // })(marker);

        }



        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#161516',
            strokeOpacity: 0.9,
            strokeWeight: 1,
            icons: [{
                icon: lineSymbol,
                offset: 0,
                repeat: "50px",
            }, ],
        });


        flightPath.setMap(map);
        map.fitBounds(bounds);


    }


    $(document).on("click",".hideTimeLineClass",function() {
        window.location.href = "{{ route('inOuts.index')}}";
    });


</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWF9wFs2DV5_Ywpme7CSJg4F1wWOg0yK4&callback=initialize&libraries=places&v=weekly"
    defer></script>
@endsection
