<html>

<head>
    <title>{{__('labels.visit_report_title')}}</title>
</head>

<body >
    <table id="customers" border="1">
        <tbody>
            <tr>
                <td colspan="2">
                </td>
                <td colspan="10">
                    <h3><strong> {{__('labels.visit_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </td>
            </tr>
            {{-- <tr>
                <td>{{__('labels.no')}}</td>
                <td>{{__('labels.visit_type')}}</td>
                <td>{{__('labels.created_by')}}</td>
                <td>{{__('labels.dealer_name')}}</td>
                <td>{{__('labels.report_dealer_contact_number')}}  </td>
                <td>{{__('labels.cities')}} </td>
                <td>{{__('labels.talukas')}} </td>
                <td>{{__('labels.report_our_brand')}} </td>
                <td>{{__('labels.report_other_brand')}} </td>
                <td>{{__('labels.report_construction')}} </td>
                <td>{{__('labels.report_feedback')}} </td>
                <td>{{__('labels.report_created_on')}}</td>
            </tr> --}}

            <tr>
                <td>{{__('labels.no')}}</td>
                <td>Date And Time</td>
                <td>User Type</td>
                <td>User Name</td>
                <td>Visit Type</td>
                <td>Dealer Type</td>
                <td>Actor Name</td>
                <td>Actor Id</td>
                <td>Firm Name</td>
                <td>Purpose</td>
                <td>Location</td>
                <td>Address</td>
                <td>Region</td>
                <td>Mobile Number </td>
                <td>Construction Area </td>
                <td>Our Material available </td>
                <td>{{__('labels.report_other_brand')}} </td>
                <td> Other Brand quantity </td>
                <td>Our Material Used Till now </td>
                <td>Schedule required for this visit </td>
                <td>Total Completed project </td>
                <td>OnGoing Project </td>
                <td>Any New Lead </td>
                <td>Remark </td>
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
            <td>{{ $key ?? '' }}</td>
            <td>{{ $report['createdAt'] ?
                date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
            <td>{{ $report['roleName'] ?? '-' }}</td>
            <td>{{ $report['fullName'] ?? '-' }}</td>
            <td>{{ $report['sType'] ?? '-' }}</td>
            <td>{{ $report['dType'] ?? '-' }}</td>
            <td>{{ $report['name'] ?? '-' }}</td>
            <td>{{ $report['dealerId'] ?? '-' }}</td>
            <td>{{ $report['sFirmName'] ?? '-' }}</td>
            <td>{{ $report['purpose'] ?? '-' }}</td>
            <td>{{ $report['slocation'] ?? '-' }}</td>
            <td>{{ $report['dAddress1'] ?? '-' }}</td>
            <td>{{ $report['dRName'] ?? '-' }}</td>
            <td>{{ $report['mobileNumber'] ?? '-' }}</td>
            <td>{{ $report['construction'] ?? '-' }}</td>
            <td>{{ $report['ourMaterialsAvailable'] ?? '-' }}</td>
            <td>{{ $brandName ?? '-' }}</td>
            <td>{{ $report['dTotalQty'] ?? '-' }}</td>
            <td>{{ $report['usedTillNow'] ?? '-' }}</td>
            <td>{{ $report['tentativeSchedule'] ?? '-' }}</td>
            <td>{{ $report['completedProject'] ?? '-' }}</td>
            <td>{{ $report['ongoingProject'] ?? '-' }}</td>
            <td>{{ $report['anyLead'] ?? '-' }}</td>
            <td>{{ $report['feedback'] ?? '-' }}</td>
            {{-- <td style="padding:8px; width:8%; border:1px solid #DDDDDD;">{{ $report['dCName'] ?? '-' }}</td>
            <td style="padding:8px; width:8%; border:1px solid #DDDDDD;">{{ $report['dTName'] ?? '-' }}</td> --}}
        </tr>
        @empty
        @endforelse


            {{-- @forelse($reports as $key1 => $report)
                @php
                    $brandName = [];
                    if (!empty($report['otherBrandName'])) {

                        $brand = json_decode($report['otherBrandName']);

                        foreach ($brand as $key => $value) {
                            $name = App\Models\ExpenseType::select('eName')->where('id', $value)->first();
                            $brandName[] =  $name->eName;
                        }
                        $brandName =  implode(', ', $brandName);
                    }else{
                        $brandName = '-';
                    }
                @endphp
            <tr>
                <td>{{ ++$key1 ?? '' }}</td>
                <td>{{ $report['sType'] ?? '-' }}</td>
                <td>{{ $report['fullName'] ?? '-' }}</td>
                <td>{{ $report['name'] ?? '-' }}</td>
                <td>{{ $report['mobileNumber'] ?? '-' }}</td>
                <td>{{ $report['dCName'] ?? '-' }}</td>
                <td>{{ $report['dTName'] ?? '-' }}</td>
                <td>{{ $report['ourMaterialsAvailable'] ?? '-' }}</td>
                <td>{{ $brandName ?? '-' }}</td>
                <td>{{ $report['construction'] ?? '-' }}</td>
                <td>{{ $report['feedback'] ?? '-' }}</td>
                <td>{{ $report['createdAt'] ?
                    date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
            </tr>
            @empty
            @endforelse --}}

        </tbody>
    </table>
</body>

</html>
