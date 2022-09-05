<html>
<head>
    <title>{{__('labels.material_report_title')}}</title>
</head>

<body>
    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2">
                </th>
                <th colspan="7">
                    <h3><strong> {{__('labels.material_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>{{__('labels.report_user_name')}}</th>
                <th>{{__('labels.report_lead_name')}}</th>
                <th>{{__('labels.report_material_name')}}</th>
                <th>{{__('labels.report_sub_material_type')}} </th>
                <th>{{__('labels.report_sub_material_name')}} </th>
                <th>{{__('labels.total_quality')}} </th>
                <th>{{__('labels.report_created_on')}} </th>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['fullName'] ?? '-' }}</td>
                    <td>{{ $report['lFullName'] ?? '-' }}</td>
                    <td>{{ $report['mName'] ?? '-' }}</td>
                    <td>{{ $report['msType'] ?? '-' }}</td>
                    <td>{{ $report['msName'] ?? '-' }}</td>
                    <td>{{ $report['totalQty'] ?? '-' }}</td>
                    <td>{{ $report['createDateFormate'] ?? '-' }}</td>

                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
