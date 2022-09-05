<html>
<head>
    <title>{{__('labels.complaint_report_title')}}</title>
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
                <th colspan="18"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.complaint_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time </th>
                    <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        User Type</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    User Name</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_complaint_type')}}</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Type</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_dealer_name')}}</th>

                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Id</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Firm Name</th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                Site Location</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Address</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Mobile Number</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Complaint Type</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Project Name & Billing Details</th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                Product</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Quantity</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Lot No.</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Truck Number</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                Comments</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Remark</th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_status')}}</th>

            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    @php
                    $key++;
                    @endphp
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $key ?? '' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cType'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['fType'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['name'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['dealerId'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['firmName'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cSiteLocation'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cAddress'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cWpMobileNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['complaintType'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cProductNameBillingDetails'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['mtListView'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cTotalQty'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cLotNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cTruckNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cComments'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cRemarks'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['cStatus'] ?? '-' }}</td>
                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
