<html>
<head>
    <title>Knowledge Report</title>
</head>

<body>
    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2"></th>
                <th colspan="6">
                    <h3><strong> Knowledge Report </strong></h3>
                    @if(!empty($reportInfo['rangeDate']))
                    <p> Date of Report : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            {{-- <tr>
                <th>{{__('labels.no')}}</th>
                <th>{{__('labels.report_user_name')}}</th>
                <th>{{__('labels.report_form_type')}}</th>
                <th>{{__('labels.report_firm_name')}}</th>
                <th>{{__('labels.report_dealer_name')}}</th>
                <th>{{__('labels.report_dealer_id')}}</th>
                <th>{{__('labels.report_status')}}</th>
                <th>{{__('labels.report_date_time')}}</th>
            </tr> --}}

            <tr>
                <th>{{__('labels.no')}}</th>
                <th> Date And Time</th>
                <th>User Type</th>
                <th> {{__('labels.report_user_name')}}</th>
                <th>Actor Type</th>
                <th>{{__('labels.report_dealer_name')}}</th>
                <th> Actor Id</th>
                <th>Current Location</th>
                <th>Start Location</th>
                <th>Destination</th>
                <th>Vehicle Number</th>
                <th>Start Reading photo</th>
                <th>End Reading Photo</th>
                <th>{{__('labels.report_status')}}</th>
            </tr>


            @forelse($reports as $key => $report)

            <tr>
                    @php
                    $key++;
                    @endphp
                <td>{{ $key ?? '' }}</td>
                <td>{{ ($report['kdateFormate'].' '.$report['ktimeFormate']) ?? '-' }}</td>
                <td>{{ $report['roleName'] ?? '' }}</td>
                <td>{{ $report['fullName'] ?? '' }}</td>
                <td>{{ $report['fType'] ?? '-' }}</td>
                <td>{{ $report['name'] ?? '-' }}</td>
                <td>{{ $report['dealerId'] ?? '-' }}</td>
                <td>{{ $report['kCurrentLocation'] ?? '-' }}</td>
                <td>{{ $report['kStartLocation'] ?? '-' }}</td>
                <td>{{ $report['kDestination'] ?? '-' }}</td>
                <td>{{ $report['vehicleNumber'] ?? '-' }}</td>
                <td>
                    @if(!empty($report['startMeterReadingPhoto']))
                        {{ $report['startMeterReadingPhoto'] }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!empty($report['endMeterReadingPhoto']))
                        {{ $report['endMeterReadingPhoto'] }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $report['kStatus'] ?? '-' }}</td>
            </tr>

                {{-- <tr>
                    @php
                    $key++;
                    @endphp
                    <td>{{ $key ?? '' }}</td>
                    <td>{{ $report['fullName'] ?? '' }}</td>
                    <td>{{ $report['fType'] ?? '-' }}</td>
                    <td>{{ $report['firmName'] ?? '-' }}</td>
                    <td>{{ $report['name'] ?? '-' }}</td>
                    <td>{{ $report['dealerId'] ?? '-' }}</td>
                    <td>{{ $report['kStatus'] ?? '-' }}</td>
                    <td>{{ ($report['kdateFormate'].' '.$report['ktimeFormate']) ?? '-' }}</td>
                </tr> --}}
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
