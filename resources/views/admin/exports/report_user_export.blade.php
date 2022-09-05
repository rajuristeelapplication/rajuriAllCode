<html>
<head>
    <title>{{__('labels.user_report_title')}}</title>
</head>

<body>
    <table id="customers"
        style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
        <tbody>
            <tr>
                <th colspan="3"
                    style="color:white;text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <img style="width: 200px; height: auto;" src="{{ url('admin-assets/images/logo/logo.png') }}">
                </th>
                <th colspan="7"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.user_report_title')}} </strong></h3>

                    {{-- @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif --}}
                </th>
            </tr>
            <tr>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>

                <th style="color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time </th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            {{__('labels.report_user_type')}}</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    User Name </th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date of Birth </th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        {{__('labels.report_mobile_number')}}</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_email')}}</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">City</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">Zip Code</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">Address</th>

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
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $key ?? '' }}</td>
                    <td style="padding:8px;   border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $roleName ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['dobFormate'] ?? '' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['mobileNumber'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['email'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['cName'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['zipCode'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['address'] ?? '-' }}</td>
                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
