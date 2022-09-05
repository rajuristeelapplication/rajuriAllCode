<html>
<head>
    <title>{{__('labels.lead_report')}}</title>
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
                <th colspan="5"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.lead_report')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.lead_type')}}</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.created_by')}}</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.address')}}</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.material_details')}}</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.total_quality')}}</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_created_on')}}</th>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['lType'] ?? '' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['pAddress'] ?? '' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['mtListView'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['totalQTMT'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
