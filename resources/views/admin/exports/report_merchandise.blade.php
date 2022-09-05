<html>
<head>
    <title> {{ $reports[0]['mType'] == "Order" ?  __('labels.order_report_title')  : __('labels.gift_report_title') }}</title>
</head>

<body>
    <table id="customers"
        style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
        <tbody>
            <tr>
                <th colspan="4"
                    style="color:white;text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <img style="width: 200px; height: auto;" src="{{ url('admin-assets/images/logo/logo.png') }}">
                </th>
                <th colspan="9"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{ $reports[0]['mType'] == "Order" ?  __('labels.order_report_title')  : __('labels.gift_report_title') }} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>

            @forelse($reports as $key => $report)




            <tr>
                <th style="width:1%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Date & Time </th>

                <th style="width:8%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            User Type </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    User Name </th>
                    <th style="width:5%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Type </th>

                <th style="width:5%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Name </th>

                 <th style="width:5%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Actor Id </th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                Firm Name </th>

                 <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                    Location </th>
                <th style="width:5%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.item_quantity')}} </th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.item_name')}} </th>
                    <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Photo </th>

                @if($report['mType'] == 'Order')
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.status')}} </th>
                @endif

            </tr>

                <tr>
                    @if(count($report->orderProducts) == 0)
                    <td  style="padding:8px; width:1%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    @else
                    <td  rowspan="2" style="padding:8px; width:1%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    @endif
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td style="padding:8px; width:8%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '-' }}</td>
                    <td style="padding:8px; width:5%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '-' }}</td>
                    <td style="padding:8px; width:5%; border:1px solid #DDDDDD;">{{ $report['fType'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['name'] ?? '-' }}</td>
                    <td style="padding:8px; width:5%; border:1px solid #DDDDDD;">{{ $report['dealerId'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['firmName'] ?? '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['mAddress'] ?? '-' }}</td>
                    <td style="padding:8px; width:5%; border:1px solid #DDDDDD;">{{ $report['itemQty'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['itemListShow1'] ?? '-' }}</td>
                    <td>
                        @if($report['mPhoto'])
                            <img src="{{ $report['mPhoto'] ?? '-' }}" height="50" width="50" />
                        @else
                         -
                         @endif

                    </td>
                    @if($report['mType'] == 'Order')
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['mType'] == "Order"  ? $report['mStatus'] : '-' }}</td>
                    @endif
                </tr>


                @if(count($report->orderProducts))



                <tr>
                    <td colspan="11">

                        <table  style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
                            <tbody>
                                <tr>
                                    <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Product Name</th>
                                    <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Qty</th>
                                    @if($report->mType == "Order")
                                        <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Price</th>
                                        <th style="padding:5px; border:1px solid #DDDDDD;text-align: left;">Total Price</th>
                                    @endif
                                </tr>

                                @forelse ($report->orderProducts  as $key1 => $value1)



                                <tr>
                                    <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['pName']  ?? '-'}}
                                        @if(!empty($value1['productOptionName']))
                                                ({{ $value1['productOptionName'] }})
                                        @endif
                                    </td>
                                    <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['orderQty'] ?? '-' }}</td>
                                    @if($report->mType == "Order")
                                    <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['price'] ?? '-' }}</td>
                                    <td style="padding:5px; border:1px solid #DDDDDD;">{{ $value1['totalPrice'] ?? '-' }}</td>
                                    @endif
                                </tr>

                                @empty

                                @endforelse


                            </tbody>
                        </table>

                    </td>
                </tr>

                @endif


            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
