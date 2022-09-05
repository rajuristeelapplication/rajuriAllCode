<html>
<head>
    <title>{{__('labels.marketing_van_request_title')}}</title>
</head>

<body>
    <table id="customers" border="1">
        <tbody>
            <tr>
                <td colspan="2">
                </td>
                <td colspan="6">
                    <h3><strong> {{__('labels.marketing_van_request_title')}} </strong></h3>
                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{__('labels.no')}}</td>
                <td>Date Time</td>
                <td>Executive Name</td>
                <td>Actor Type</td>
                <td>Actor Name </td>
                <td>Current location </td>
                <td>{{__('labels.start_location')}} </td>
                <td>{{__('labels.vehicle_number')}} </td>
                <td>{{__('labels.report_start_meter_reading')}} </td>
                <td>{{__('labels.report_end_meter_reading')}} </td>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ isset($report['kdateFormate']) ? $report['kdateFormate'].' '.$report['ktimeFormate']  : '-' }}</td>
                    <td>{{ $report['fullName'] ?? '-' }}</td>
                    <td>{{ $report['fType'] ?? '-' }}</td>
                    <td>{{ $report['name'] ?? '-' }}</td>
                    <td>{{ $report['kCurrentLocation'] ?? '-' }}</td>
                    <td>{{ $report['kStartLocation'] ?? '-' }}</td>
                    <td>{{ $report['vehicleNumber'] ?? '-' }}</td>
                    <td>{{ $report['startMeterReadingPhoto'] ?? '-' }}</td>
                    <td>{{ $report['endMeterReadingPhoto'] ?? '-' }}</td>
                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
