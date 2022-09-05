<html>
<head>
    <title>{{__('labels.gift_report_title')}}</title>
</head>

<body>

    @php
        setlocale(LC_MONETARY, 'en_IN');
        $totalOrderPrice = 0;
    @endphp

    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2">
                </th>
                <th colspan="7">
                    <h3><strong> {{ $reports[0]['mType'] ? 'Order Report' : 'Gift Report' }} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>

            <tr></tr>

            <tr>
                <th><b>{{__('labels.no')}}</b></th>
                <th><b>Date And Time </b></th>
                <th><b>User Type </b></th>
                <th><b>User Name </b></th>
                <th><b>Actor Type </b></th>
                <th><b>Actor Name </b></th>
                 <th><b>Actor Id </b></th>
                <th><b>Firm Name </b></th>
                 <th><b>Location </b></th>
                <th><b>{{__('labels.item_quantity')}} </b></th>
                <th><b>Total Price </b></th>


                {{-- <th><b>{{__('labels.item_name')}} </b></th> --}}
                @if($reports[0]['mType'] == "Order")
                <th><b>{{__('labels.status')}} </b></th>
                @endif
            </tr>

            <tr></tr>

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
                {{-- <td>{{ $report['itemNames'] ?? '-' }}</td> --}}
                <td>{{ money_format('%!i', $report['totalPriceOrder']) ?? '-' }}</td>
                @if($report['mType'] == "Order")
                <td>{{ $report['mType'] == "Order"  ? $report['mStatus'] : '-' }}</td>
                @endif
            </tr>

            @php
            $totalOrderPrice += $report['totalPriceOrder'];
            @endphp


            <tr></tr>
            <tr></tr>

            @if($report['mType'] == "Order")
            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><b>Product Name</b></th>
                                <th><b>Qty</b></th>
                                <th><b>Price</b></th>
                                <th><b>Total Price</b></th>
                            </tr>

                            @forelse ($report->orderProducts  as $key1 => $value1)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $value1['pName']  ?? '-'}}
                                    @if(!empty($value1['productOptionName']))
                                     ({{ $value1['productOptionName'] ?? '-' }})
                                    @endif
                                </td>
                                <td>{{ $value1['orderQty'] ?? '-' }}</td>
                                <td>{{ $value1['price'] ?? '-' }}</td>
                                <td>{{ money_format('%!i', $value1['totalPrice']) ?? '-' }}</td>
                            </tr>

                            @empty

                            @endforelse

                        </tbody>
                    </table>

                </td>
            </tr>

            @endif

            @if($report['mType'] == "Gift")


            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <th>Product Name</th>
                                <th>Qty</th>

                            </tr>

                            @forelse ($report->orderProducts  as $key1 => $value1)
                            <tr>
                                <td>{{ $value1['pName']  ?? '-'}}
                                    @if(!empty($value1['productOptionName']))
                                     ({{ $value1['productOptionName'] ?? '-' }})
                                    @endif
                                </td>
                                <td>{{ $value1['orderQty'] ?? '-' }}</td>
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

            @if($report['mType'] == "Order")
            <tr>
                <th colspan="12" ><b>Total Price </b></th>
            </tr>

            <tr>
                <td  colspan="12"  >{{ money_format('%!i', $totalOrderPrice)  }}</td>
            </tr>

            @endif


        </tbody>
    </table>
</body>

</html>
