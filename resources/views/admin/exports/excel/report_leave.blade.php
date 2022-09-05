<html>
<head>
    <title>{{__('labels.leave_report_title')}}</title>
</head>

<body>
    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2">
                </th>
                <th colspan="7">
                    <h3><strong> {{__('labels.leave_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>{{__('labels.report_full_name')}}</th>
                <th>{{__('labels.leave_type')}}</th>
                <th>{{__('labels.fromDate')}}</th>
                <th>{{__('labels.toDate')}}</th>
                <th>{{__('labels.noOfLeave')}}</th>
                <th>{{__('labels.status')}}</th>
                <th>{{__('labels.report_created_on')}}</th>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['fullName'] ?? '-' }}</td>
                    <td>{{ $report['lType'] ?? '-' }}</td>
                    <td>{{ $report['fromDateFormate'] ?? '-' }}</td>
                    <td>{{ $report['toDateFormate'] ?? '-' }}</td>
                    <td>{{ $report['noOfLeave'] ?? '-' }}</td>
                    <td>{{ $report['lRStatus'] ?? '-' }}</td>
                    <td>{{ $report['createdAtFormate'] ?? '-' }}</td>

                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
