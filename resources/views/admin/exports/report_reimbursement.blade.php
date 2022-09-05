@if($reimbursementType == "incentive")

<html>
<head>
    <title>Incentive Report</title>
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
                <th colspan="8"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> Incentive Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time
                </th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Project Name  </th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                Location </th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Quantity </th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Payment Status </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_expense_type')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Invoice Number </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                Description </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.status')}} </th>
               <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Amount </th>
            </tr>

            @php
                    $totalAmt = 0;
            @endphp
            @forelse($reports as $key => $report)
                <tr>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['dateOfCreateAtFormate'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['projectName'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['rLocation'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['rQuantity'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['paymentStatus'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['eName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['invoiceNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['description'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['rStatus'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">
                        @if(!empty($report['totalAmount']))
                        <img style="vertical-align: middle;display:inline-block"  height="13" width="13" src="{{ url('rupee.png') }}" />

                        <span style="vertical-align: middle;display:inline-block">{{ round($report['totalAmount'],2) }}</span>

                        @php
                          $totalAmt+= $report['totalAmount'];
                        @endphp

                        @else
                        -
                        @endif
                    </td>
                </tr>
            @empty
            @endforelse

            @if(!empty($totalAmt))

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;text-align:right" colspan="2"> Total Amount  </td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;" >
                    <img style="vertical-align: middle;display:inline-block"  height="13" width="13" src="{{ url('rupee.png') }}" />
                    {{round($totalAmt,2) ?? 0}}</td>
            </tr>

            @endif

        </tbody>
    </table>
</body>

</html>

@endif

@if($reimbursementType == "birthday")

<html>
<head>
    <title>Birthday Report</title>
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
                <th colspan="7"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> Birthday Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time
                </th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Executive Type  </th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                Executive Name </th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Actor Type </th>
                <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Actor Name </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_expense_type')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Description </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.status')}} </th>
               <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Amount </th>
            </tr>

            @php
                    $totalAmt = 0;
            @endphp
            @forelse($reports as $key => $report)
                <tr>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['dateOfCreateAtFormate'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['fType'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['name'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['eName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['description'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['rStatus'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">
                        @if(!empty($report['totalAmount']))
                        <img style="vertical-align: middle;display:inline-block"  height="13" width="13" src="{{ url('rupee.png') }}" />

                        <span style="vertical-align: middle;display:inline-block">{{ round($report['totalAmount'],2) }}</span>

                        @php
                          $totalAmt+= $report['totalAmount'];
                        @endphp

                        @else
                        -
                        @endif
                    </td>
                </tr>
            @empty
            @endforelse

            @if(!empty($totalAmt))

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;text-align:right" colspan="2"> Total Amount  </td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;" >
                    <img style="vertical-align: middle;display:inline-block"  height="13" width="13" src="{{ url('rupee.png') }}" />
                    {{round($totalAmt,2) ?? 0}}</td>
            </tr>

            @endif

        </tbody>
    </table>
</body>

</html>

@endif


@if($reimbursementType == "other")
<html>
<head>
    <title>{{__('labels.reimbursement_report_title')}}</title>


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
                <th colspan="6"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.reimbursement_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time
                </th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                Executive Type  </th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                Executive Name </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_expense_type')}}</th>

                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Description </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                Image </th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.status')}} </th>
               <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Amount </th>

            </tr>

            @php
                    $totalAmt = 0;
            @endphp
            @forelse($reports as $key => $report)
                <tr>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['dateOfCreateAtFormate'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['eName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['description'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">
                    @if(count($report['reimbursementsImage']) > 0)
                        @forelse ($report['reimbursementsImage'] as $images)
                        <img src="{{$images->rAttachment}}"  height="50" width="50" style="margin-bottom:10px;"/>
                        @empty

                        @endforelse

                        @else
                         -
                    @endif
                    </td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['rStatus'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">
                        @if(!empty($report['totalAmount']))
                        <img style="vertical-align: middle;display:inline-block"  height="13" width="13" src="{{ url('rupee.png') }}" />

                        <span style="vertical-align: middle;display:inline-block">{{ round($report['totalAmount'],2) }}</span>

                        @php
                          $totalAmt+= $report['totalAmount'];
                        @endphp

                        @else
                        -
                        @endif
                    </td>
                </tr>
            @empty
            @endforelse

            @if(!empty($totalAmt))

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;text-align:right" colspan="2"> Total Amount  </td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;" >
                    <img style="vertical-align: middle;display:inline-block"  height="13" width="13" src="{{ url('rupee.png') }}" />
                    {{round($totalAmt,2) ?? 0}}</td>
            </tr>

            @endif

        </tbody>
    </table>
</body>
</html>

@endif
