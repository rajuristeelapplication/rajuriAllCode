@if($reimbursementType == "incentive")

<html>
<head>
    <title>Incentive Report</title>
</head>

<body>
    <table id="customers">
        <tbody>
            <tr>
                <th colspan="3"></th>
                <th colspan="8">
                    <h3><strong> Incentive Report </strong></h3>
                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date Time</th>
               <th>Project Name  </th>
               <th>Location </th>
                <th>Quantity </th>
                <th>Payment Status </th>
                <th>{{__('labels.report_expense_type')}}</th>
                <th>Invoice Number </th>
                <th>Description </th>
                <th>{{__('labels.status')}} </th>
               <th>Amount </th>
            </tr>

            @php
                    $totalAmt = 0;
            @endphp
            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['dateOfCreateAtFormate'] ?? '-' }}</td>
                    <td>{{ $report['projectName'] ?? '-' }}</td>
                    <td>{{ $report['rLocation'] ?? '-' }}</td>
                    <td>{{ $report['rQuantity'] ?? '-' }}</td>
                    <td>{{ $report['paymentStatus'] ?? '-' }}</td>
                    <td>{{ $report['eName'] ?? '-' }}</td>
                    <td>{{ $report['invoiceNumber'] ?? '-' }}</td>
                    <td>{{ $report['description'] ?? '-' }}</td>
                    <td>{{ $report['rStatus'] ?? '-' }}</td>
                         @php
                          $totalAmt+= $report['totalAmount'];
                        @endphp

                    <td>{{ !empty($report['totalAmount']) ?  '₹ '.round($report['totalAmount'],2)  : '-' }}</td>
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
                <td></td>
                <td> Total Amount  </td>
                <td>{{'₹ '. round($totalAmt,2) ?? 0}}</td>
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
    <table id="customers">
        <tbody>
            <tr>
                <th colspan="3">
                </th>
                <th colspan="7">
                    <h3><strong> Birthday Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date Time</th>
               <th>Executive Type  </th>
               <th>Executive Name </th>
                <th> Actor Type </th>
                <th>Actor Name </th>
                <th>{{__('labels.report_expense_type')}}</th>
                <th>Description </th>
                <th>{{__('labels.status')}} </th>
               <th>Amount </th>
            </tr>

            @php
                    $totalAmt = 0;
            @endphp
            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['dateOfCreateAtFormate'] ?? '-' }}</td>
                    <td>{{ $report['roleName'] ?? '-' }}</td>
                    <td>{{ $report['fullName'] ?? '-' }}</td>
                    <td>{{ $report['fType'] ?? '-' }}</td>
                    <td>{{ $report['name'] ?? '-' }}</td>
                    <td>{{ $report['eName'] ?? '-' }}</td>
                    <td>{{ $report['description'] ?? '-' }}</td>
                    <td>{{ $report['rStatus'] ?? '-' }}</td>
                    @php
                    $totalAmt+= $report['totalAmount'];
                  @endphp
                    <td>{{ !empty($report['totalAmount']) ?  '₹ '.round($report['totalAmount'],2)  : '-' }}</td>

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
                <td></td>
                <td>{{ '₹ '. round($totalAmt,2) ?? 0}}</td>
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
    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2"></th>
                <th colspan="5">
                    <h3><strong> {{__('labels.reimbursement_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date Time</th>
                <th>User Type</th>
                <th> {{__('labels.report_user_name')}}</th>
                <th>{{__('labels.report_expense_type')}} </th>
                <th>Description</th>
                <th>Image</th>
                <th>{{__('labels.status')}} </th>
                <th>Amount</th>

            </tr>
            @php
            $totalAmt = 0;
            @endphp

            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['dateOfCreateAtFormate'] ?? '-' }}</td>
                    <td>{{ $report['roleName'] ?? '-' }}</td>
                    <td>{{ $report['fullName'] ?? '-' }}</td>
                    <td>{{ $report['eName'] ?? '-' }}</td>
                    @php
                    $totalAmt+= $report['totalAmount'];
                   @endphp


                    <td>{{ $report['description'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">
                        @if(count($report['reimbursementsImage']) > 0)
                            @forelse ($report['reimbursementsImage'] as $images)
                                {{$images->rAttachment ?? ''}}
                            @empty
                            @endforelse
                            @else
                             -
                        @endif
                        </td>
                    <td>{{ $report['rStatus'] ?? '-' }}</td>
                    <td>{{ !empty($report['totalAmount']) ?  '₹ '.round($report['totalAmount'],2)  : '-' }}</td>
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
                <td > Total Amount  </td>
                <td> {{ '₹ '. round($totalAmt,2) ?? 0}}</td>
            </tr>

            @endif

        </tbody>
    </table>
</body>

</html>

@endif
