<html>
<head>
    <title>Lead Report</title>
</head>

<body>
    <table id="customers">
        <tbody>
            <tr>
                <th colspan="2">
                </th>
                <th colspan="22">
                    <h3><strong> Lead Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> Date of Report : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date And Time</th>
                <th>Type</th>
                <th>User Type</th>
                <th>{{__('labels.report_user_name')}}</th>
                <th>Name</th>
                <th>Company Name</th>
                <th>Mobile Number</th>
                <th>eMail</th>
                <th>Project Site Address</th>
                <th>Current Address</th>
                <th>Material Type With Straight and Bend</th>
                <th>Total Quantity</th>
                <th>Project Name</th>
                <th>Required Date of Delivery</th>
                <th>Firm Registration number</th>
                <th>Shop Act Number</th>
                <th>GST IN number</th>
                <th>Pan</th>
                <th>Firm Type </th>
                <th>CIN </th>
                <th>Shop And Ware House area </th>
                <th>Payment Method </th>
                <th>Budget </th>
                <th>Lead Status </th>
            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    @php
                    $key++;
                    @endphp
                    <td>{{ $key ?? '' }}</td>
                    <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td>{{ $report['lType'] ?? '' }}</td>
                    <td>{{ $report['roleName'] ?? '' }}</td>
                    <td>{{ $report['fullName'] ?? '' }}</td>
                    <td>{{ $report['lFullName'] ?? '-' }}</td>
                    <td>{{ $report['lCompanyName'] ?? '-' }}</td>
                    <td>{{ $report['lMobileNumber'] ?? '-' }}</td>
                    <td>{{ $report['lEmail'] ?? '-' }}</td>
                    <td>{{ $report['pAddress'] ?? '-' }}</td>
                    <td>{{ $report['cAddress'] ?? '-' }}</td>

                    @php
                        $getMatrialLeads = \App\Models\MaterialReport::where(['leadId' => $report['id'],'isParent' => 0])->orderBy('mName')->get();
                    @endphp

                <td>
                    @forelse($getMatrialLeads as $value)
                        {{-- {{$value['mName'] .' - ' . $value['msType'] . ' - ' . $value['msName'] .' - ' . $value['totalQty'] . "\n"}} --}}
                        {!! $value['mName'] .' - ' . $value['msType'] . ' - ' . $value['msName'] .' - ' . $value['totalQty'] . "<br>" !!}
                    @empty
                            -
                    @endforelse
                </td>


                    {{-- <td>{{ $report['mtListView'] ?? '-' }}</td> --}}


                    <td>{{ $report['totalQTMT'] ?? '-' }}</td>
                    <td>{{ $report['projectName'] ?? '-' }}</td>
                    <td >{{ $report['dateOfDelivery'] ? date(config('constant.admin_dob_format'), strtotime($report['dateOfDelivery'])) : '-' }}</td>
                    <td>{{ $report['firmRegistrationNumber'] ?? '-' }}</td>
                    <td>{{ $report['actLicenceNumber'] ?? '-' }}</td>
                    <td>{{ $report['gstTinNumber'] ?? '-' }}</td>
                    <td>{{ $report['panText'] ?? '-' }}</td>
                    <td>{{ $report['firmType'] ?? '-' }}</td>
                    <td>{{ $report['lcin'] ?? '-' }}</td>
                    <td>{{ $report['shopWarehouseArea'] ?? '-' }}</td>
                    <td>{{ $report['modeOfPayment'] ?? '-' }}</td>
                    <td>{{ $report['budget'] ?? '-' }}</td>
                    <td>{{ $report['moveStatus']  == "Pending" ? 'In Progress' : 'Converted' }}</td>

                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
