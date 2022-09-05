<html>
<head>
    <title>{{__('labels.order_report_title')}}</title>
</head>

<body>
    @php
        setlocale(LC_MONETARY, 'en_IN');
        $totalOrderPrice = 0;
    @endphp
    <table id="customers"
        style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
        <tbody>
            <tr>
                <th colspan="3"
                    style="color:white;text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <img style="width: auto; height: 53px;" src="{{ url('admin-assets/images/logo/logo.png') }}">
                </th>
                <th colspan="9"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.order_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            {{-- <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.order_number')}}</th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.created_by')}} </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.dealer_name')}} </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.item_quantity')}} </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.item_name')}} </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.status')}} </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_created_on')}} </th>
            </tr> --}}




            @forelse($reports as $key => $report)


            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>

                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Date & Time </th>

                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            User Type </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    User Name </th>
                    <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Type </th>

                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Name </th>

                 <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Actor Id </th>

                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                Firm Name </th>

                 <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                    Location </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.item_quantity')}} </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Total Price </th>
                {{-- <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.item_name')}} </th> --}}
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.status')}} </th>

            </tr>

            <tr>
                <td rowspan="2" style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '-' }}</td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '-' }}</td>
                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['fType'] ?? '-' }}</td>
                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['name'] ?? '-' }}</td>
                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['dealerId'] ?? '-' }}</td>
                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['firmName'] ?? '-' }}</td>
                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['mAddress'] ?? '-' }}</td>
                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['itemQty'] ?? '-' }}</td>
                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ money_format('%!i', $report['totalPriceOrder'])  ?? '-' }}</td>
                {{-- <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['itemNames'] ?? '-' }}</td> --}}

                <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['mType'] == "Order"  ? $report['mStatus'] : '-' }}</td>
            </tr>

            @php
                $totalOrderPrice += $report['totalPriceOrder'];
            @endphp

            <tr>
                <td colspan="11">

                    <table  style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
                        <tbody>
                            <tr>
                                <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Product Name</th>
                                {{-- <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Sub Product Name</th> --}}
                                <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Qty</th>
                                <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Price</th>
                                <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Total Price</th>
                            </tr>

                            @forelse ($report->orderProducts  as $key1 => $value1)
                            <tr>
                                <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['pName']  ?? '-'}}
                                    @if(!empty($value1['productOptionName']))
                                            ({{ $value1['productOptionName'] }})
                                    @endif
                                </td>
                                {{-- <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['productOptionName'] ?? '-' }}</td> --}}
                                <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['orderQty'] ?? '-' }}</td>
                                <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['price'] ?? '-' }}</td>
                                <td style="padding:5px; border:1px solid #DDDDDD;">{{ money_format('%!i', $value1['totalPrice'])  ?? '-' }}</td>
                            </tr>

                            @empty

                            @endforelse


                        </tbody>
                    </table>

                </td>
            </tr>


            @empty
            @endforelse

            <tr>
                <th colspan="12" style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD;text-align:center;">
                        Total Price </th>
            </tr>

            <tr>
                <td  colspan="12" style="padding:8px; width:15%; border:1px solid #DDDDDD;text-align:center;" >
                    {{ money_format('%!i', $totalOrderPrice)  }}</td>
            </tr>



        </tbody>
    </table>
</body>

</html>
