<html>
<head>
    <title>{{__('labels.order_report_title')}}</title>
</head>

<body>
    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2">
                </th>
                <th colspan="7">
                    <h3><strong> {{__('labels.order_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            {{-- <tr>
                <th>{{__('labels.no')}}</th>
                <th>{{__('labels.order_number')}}</th>
                <th>{{__('labels.created_by')}} </th>
                <th>{{__('labels.dealer_name')}} </th>
                <th>{{__('labels.item_quantity')}} </th>
                <th>{{__('labels.item_name')}} </th>
                <th>{{__('labels.status')}} </th>
                <th>{{__('labels.report_created_on')}} </th>
            </tr> --}}

            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date And Time </th>
                <th>User Type </th>
                <th>User Name </th>
                <th>Actor Type </th>
                <th>Actor Name </th>
                 <th>Actor Id </th>
                <th>Firm Name </th>
                 <th>Location </th>
                <th>{{__('labels.item_quantity')}} </th>
                <th>{{__('labels.item_name')}} </th>
                <th>{{__('labels.status')}} </th>

            </tr>

            @forelse($reports as $key => $report)

            <tr>
                <td>{{ ++$key ?? '' }}</td>
                <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                <td>{{ $report['roleName'] ?? '-' }}</td>
                <td>{{ $report['fullName'] ?? '-' }}</td>
                <td>{{ $report['fType'] ?? '-' }}</td>
                <td>{{ $report['name'] ?? '-' }}</td>
                <td>{{ $report['dealerId'] ?? '-' }}</td>
                <td>{{ $report['firmName'] ?? '-' }}</td>
                <td>{{ $report['mAddress'] ?? '-' }}</td>
                <td>{{ $report['itemQty'] ?? '-' }}</td>
                <td>{{ $report['itemNames'] ?? '-' }}</td>
                <td>{{ $report['mType'] == "Order"  ? $report['mStatus'] : '-' }}</td>

            </tr>


            {{-- <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['orderNumber'] ?? '-' }}</td>
                    <td>{{ $report['fullName'] ?? '-' }}</td>
                    <td>{{ $report['name'] ?? '-' }}</td>
                    <td>{{ $report['itemQty'] ?? '-' }}</td>
                    <td>{{ $report['itemNames'] ?? '-' }}</td>
                    <td>{{ $report['mType'] == "Order"  ? $report['mStatus'] : '-' }}</td>
                    <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                </tr> --}}
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>

