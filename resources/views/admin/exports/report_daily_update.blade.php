<html>

<head>
    <title>{{__('labels.visit_report_title')}}</title>
</head>

<body>
    <table id="customers" style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px;">
        <tbody>
            <tr>
                <td colspan="2"
                    style="color:white;text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <img style="width: 100%; height: auto;" src="{{ url('admin-assets/images/logo/logo.png') }}">
                </td>
                <td colspan="22"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.visit_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td
                    style="width:5%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</td>
                <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time</td>
               <td style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        User Type</td>

                <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    User Name</td>

               <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Visit Type</td>
                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Dealer Type</td>

                <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Actor Name</td>

               <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Id</td>

               <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Firm Name</td>
                <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                Purpose</td>
                <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                    Location</td>

                <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                    Address</td>

                <td style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Region</td>

                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Mobile Number </td>
                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Construction Area </td>
                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Our Material available </td>

               <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_other_brand')}} </td>

                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Other Brand quantity </td>

                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Our Material Used Till now </td>


                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Schedule required for this visit </td>


                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Total Completed project </td>

                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    OnGoing Project </td>
                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Any New Lead </td>
                <td
                    style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Remark </td>
            </tr>
            @forelse($reports as $key => $report)
                @php

                    $key++;
                    $brandName = [];
                    if (!empty($report['otherBrandName'])) {

                        $brand = json_decode($report['otherBrandName']);

                        foreach ($brand as $key1 => $value) {
                            $name = App\Models\ExpenseType::select('eName')->where('id', $value)->first();
                            $brandName[] =  $name->eName;
                        }
                        $brandName =  implode(', ', $brandName);
                    }else{
                        $brandName = '-';
                    }
                @endphp
            <tr>
                <td style="padding:8px; width:5%; border:1px solid #DDDDDD;">{{ $key ?? '' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;">{{ $report['createdAt'] ?
                    date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['fullName'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['sType'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['dType'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['name'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['dealerId'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['sFirmName'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['purpose'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['slocation'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['dAddress1'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['dRName'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['mobileNumber'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['construction'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['ourMaterialsAvailable'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $brandName ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['dTotalQty'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['usedTillNow'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['tentativeSchedule'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['completedProject'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['ongoingProject'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['anyLead'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;text-align:center;">{{ $report['feedback'] ?? '-' }}</td>
                {{-- <td style="padding:8px; width:8%; border:1px solid #DDDDDD;">{{ $report['dCName'] ?? '-' }}</td>
                <td style="padding:8px; width:8%; border:1px solid #DDDDDD;">{{ $report['dTName'] ?? '-' }}</td> --}}
            </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
