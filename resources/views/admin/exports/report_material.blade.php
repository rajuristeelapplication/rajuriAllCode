<html>
<head>
    <title>{{__('labels.material_report_title')}}</title>
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
                <th colspan="7"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.material_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
               <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_user_name')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_lead_name')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_material_name')}}</th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_sub_material_type')}} </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_sub_material_name')}} </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.total_quality')}} </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_created_on')}} </th>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['lFullName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['mName'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['msType'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['msName'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['totalQty'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['createDateFormate'] ?? '-' }}</td>

                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
