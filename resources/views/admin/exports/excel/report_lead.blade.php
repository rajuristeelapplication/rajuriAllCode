<html>
<head>
    <title>{{__('labels.lead_report')}}</title>
</head>

<body>
    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2"></th>
                <th colspan="5">
                    <h3><strong> {{__('labels.lead_report')}} </strong></h3>
                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
               <th>{{__('labels.lead_type')}}</th>
                <th>{{__('labels.created_by')}}</th>
                <th>{{__('labels.address')}}</th>
                <th>{{__('labels.material_details')}}</th>
                <th>{{__('labels.total_quality')}}</th>
                <th>{{__('labels.report_created_on')}}</th>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['lType'] ?? '' }}</td>
                    <td>{{ $report['fullName'] ?? '' }}</td>
                    <td>{{ $report['pAddress'] ?? '' }}</td>
                    <td>{{ $report['mtListView'] ?? '-' }}</td>
                    <td>{{ $report['totalQTMT'] ?? '-' }}</td>
                    <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
