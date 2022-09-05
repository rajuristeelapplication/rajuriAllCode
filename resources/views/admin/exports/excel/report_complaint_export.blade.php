<html>
<head>
    <title>{{__('labels.complaint_report_title')}}</title>
</head>

<body>
    <table border="1" id="customers">
        <tbody>
            <tr>
                <th colspan="2">
                </th>
                <th colspan="5">
                    <h3><strong> {{__('labels.complaint_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            {{-- <tr>
                <th>
                    {{__('labels.no')}}</th>
                <th>
                    {{__('labels.report_full_name')}}</th>
                <th>
                    {{__('labels.report_complaint_type')}}</th>
                <th>
                    {{__('labels.report_dealer_name')}}</th>
                <th>
                    {{__('labels.report_status')}}</th>
                <th>
                    {{__('labels.report_created_on')}}</th>
            </tr> --}}

            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date  Time </th>
                <th>User Type</th>
                <th>User Name</th>
                <th>{{__('labels.report_complaint_type')}}</th>
                <th>Actor Type</th>
                <th>{{__('labels.report_dealer_name')}}</th>
                <th>Actor Id</th>
                <th>Firm Name</th>
               <th>Site Location</th>
                <th>Address</th>
                <th>Mobile Number</th>
                <th>Complaint Type</th>
                <th>Project Name  Billing Details</th>
               <th>Product</th>
                <th>Quantity</th>
                <th>Lot No.</th>
                <th>Truck Number</th>
                <th>Comments</th>
                <th>Remark</th>
                <th>{{__('labels.report_status')}}</th>
            </tr>

            @forelse($reports as $key => $report)

            <tr>
                @php
                $key++;
                @endphp
                <td>{{ $key ?? '' }}</td>
                <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                <td>{{ $report['roleName'] ?? '' }}</td>
                <td>{{ $report['fullName'] ?? '' }}</td>
                <td>{{ $report['cType'] ?? '-' }}</td>
                <td>{{ $report['fType'] ?? '-' }}</td>
                <td>{{ $report['name'] ?? '-' }}</td>
                <td>{{ $report['dealerId'] ?? '-' }}</td>
                <td>{{ $report['firmName'] ?? '-' }}</td>
                <td>{{ $report['cSiteLocation'] ?? '-' }}</td>
                <td>{{ $report['cAddress'] ?? '-' }}</td>
                <td>{{ $report['cWpMobileNumber'] ?? '-' }}</td>
                <td>{{ $report['complaintType'] ?? '-' }}</td>
                <td>{{ $report['cProductNameBillingDetails'] ?? '-' }}</td>
                <td>{{ $report['mtListView'] ?? '-' }}</td>
                <td>{{ $report['cTotalQty'] ?? '-' }}</td>
                <td>{{ $report['cLotNumber'] ?? '-' }}</td>
                <td>{{ $report['cTruckNumber'] ?? '-' }}</td>
                <td>{{ $report['cComments'] ?? '-' }}</td>
                <td>{{ $report['cRemarks'] ?? '-' }}</td>
                <td>{{ $report['cStatus'] ?? '-' }}</td>
            </tr>


                {{-- <tr>
                    @php
                    $key++;
                    @endphp
                    <td>{{ $key ?? '' }}</td>
                    <td>{{ $report['fullName'] ?? '' }}</td>
                    <td>{{ $report['cType'] ?? '-' }}</td>
                    <td>{{ $report['name'] ?? '-' }}</td>
                    <td>{{ $report['cStatus'] ?? '-' }}</td>
                    <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                </tr> --}}
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
