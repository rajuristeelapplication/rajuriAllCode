<html>
<head>
    <title>Knowledge Report</title>
</head>

<body>
    <table id="customers"
        style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
        <tbody>
            <tr>
                <th colspan="2"
                    style="color:white;text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <img style="width: 200px; height: auto;" src="{{ url('admin-assets/images/logo/logo.png') }}">
                </th>
                <th colspan="12"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> Knowledge Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> Date of Report : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:5%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time</th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        User Type</th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_user_name')}}</th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Actor Type</th>
                    <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        {{__('labels.report_dealer_name')}}</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Actor Id</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Current Location</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Start Location</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Destination</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Vehicle Number</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Start Reading photo</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        End Reading Photo</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_status')}}</th>

            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    @php
                    $key++;
                    @endphp
                    <td style="padding:8px; width:5%; border:1px solid #DDDDDD;">{{ $key ?? '' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ($report['kdateFormate'].' '.$report['ktimeFormate']) ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['fType'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['name'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['dealerId'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['kCurrentLocation'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['kStartLocation'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['kDestination'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['vehicleNumber'] ?? '-' }}</td>

                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">
                        @if(!empty($report['startMeterReadingPhoto']))
                            <img height="80px" width="80px" src="{{ $report['startMeterReadingPhoto'] }}" />
                        @else
                            -
                        @endif
                    </td>

                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">
                        @if(!empty($report['endMeterReadingPhoto']))
                            <img height="80px" width="80px" src="{{ $report['endMeterReadingPhoto'] }}" />
                        @else
                            -
                        @endif
                    </td>


                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['kStatus'] ?? '-' }}</td>
                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
