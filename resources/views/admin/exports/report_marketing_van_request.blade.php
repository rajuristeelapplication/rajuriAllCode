<html>
<head>
    <title>{{__('labels.marketing_van_request_title')}}</title>
</head>

<body>
    <table id="customers"
        style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
        <tbody>
            <tr>
                <td colspan="3"
                    style="color:white;text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <img style="width: 200px; height: auto;" src="{{ url('admin-assets/images/logo/logo.png') }}">
                </td>
                <td colspan="7"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.marketing_van_request_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</td>

                <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        {{__('labels.report_date_time')}} </td>
                <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Executive Name
                </td>
                <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Actor Type
                </td>
                <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Actor Name
                </td>
                <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Current location</td>
                <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.start_location')}}</td>
                <td style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.vehicle_number')}} </td>
                <td style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        {{__('labels.report_start_meter_reading')}}</td>
                <td style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        {{__('labels.report_end_meter_reading')}}</td>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ isset($report['kdateFormate']) ? $report['kdateFormate'].' '.$report['ktimeFormate']  : '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['fType'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['name'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['kCurrentLocation'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['kStartLocation'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['vehicleNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">@if(!empty($report['startMeterReadingPhoto']))<img alt="{{ config('app.name') }} Meter Reading Photo"
                        src="{{ $report['startMeterReadingPhoto'] ?? '' }}" height="auto" width="100"/> @else - @endif</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">@if(!empty($report['endMeterReadingPhoto']))<img alt="{{ config('app.name') }} Meter Reading Photo"
                        src="{{ $report['endMeterReadingPhoto'] ?? '' }}" height="auto" width="100"/>@else - @endif</td>

                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
