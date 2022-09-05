<html>
<head>
    <title>{{__('labels.user_report_title')}}</title>
</head>

<body>
    <table border="1" id="customers">
        <tbody>
            <tr>
                <th colspan="2">
                </th>
                <th colspan="5">
                    <h3><strong > {{__('labels.user_report_title')}} </strong></h3>

                    {{-- @if(!empty($reportInfo['rangeDate']))
                    <p> Date of Report : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif --}}
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date Time</th>
                <th>{{__('labels.report_user_type')}}</th>
                <th>User Name</th>
                <th>Date of Birth</th>
                <th>{{__('labels.report_mobile_number')}}</th>
                <th>{{__('labels.report_email')}}</th>
                <th>City</th>
                <th>Zip Code</th>
                <th>Address</th>
            </tr>

            @forelse($reports as $key => $report)
                <tr>
                    @php
                    $key++;

                    if($report['roleId'] == config('constant.sales_executive_id')){
                    $roleName = "Sales Executive";
                    }

                    if($report['roleId'] == config('constant.marketing_executive_id')){
                        $roleName = "Marketing Executive";
                    }
                    @endphp
                    <td>{{ $key ?? '' }}</td>
                    <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td>{{ $roleName ?? '-' }}</td>
                    <td>{{ $report['fullName'] ?? '' }}</td>
                    <td>{{ $report['dobFormate'] ?? '' }}</td>
                    <td>{{ $report['mobileNumber'] ?? '-' }}</td>
                    <td>{{ $report['email'] ?? '-' }}</td>
                    <td>{{ $report['cName'] ?? '-' }}</td>
                    <td>{{ $report['zipCode'] ?? '-' }}</td>
                    <td>{{ $report['address'] ?? '-' }}</td>
                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
