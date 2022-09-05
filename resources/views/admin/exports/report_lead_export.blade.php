<html>
<head>
    <title>Lead Report</title>
</head>

<body>
    <table id="customers"
        style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-spacing: 0px; ">
        <tbody>
            <tr>
                <th colspan="2"
                    style="color:white;text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <img style="width: 200px; height: auto;" src="{{ url('admin-assets/images/logo/logo.png') }}">
                </th>
                <th colspan="23"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> Lead Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> Date of Report : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Type</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        User Type</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_user_name')}}</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Name</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Company Name</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Mobile Number</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    eMail</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Project Site Address</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Current Address</th>

                <th style="color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD;white-space: nowrap;">
                    Material Type With Straight and Bend</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Total Quantity</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Project Name</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Required Date of Delivery</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Firm Registration number</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Shop Act Number</th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    GST IN number</th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Pan </th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Firm Type </th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            CIN </th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                                Shop & Ware House area </th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Payment Method </th>
                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Budget </th>

                <th style=" color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Lead Status </th>



            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    @php
                    $key++;
                    @endphp
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $key ?? '' }}</td>
                    <td  style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['lType'] ?? '' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['lFullName'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['lCompanyName'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['lMobileNumber'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['lEmail'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['pAddress'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['cAddress'] ?? '-' }}</td>

                    @php
                        $getMatrialLeads = \App\Models\MaterialReport::where(['leadId' => $report['id'],'isParent' => 0])->orderBy('mName')->get();
                    @endphp


                    <td style="padding:8px;  border:1px solid #DDDDDD;">
                        {{-- {{ $report['mtListView'] ?? '-' }} --}}
                        @forelse($getMatrialLeads as $value)
                            {!! $value['mName'] .' - ' . $value['msType'] . ' - ' . $value['msName'] .' - ' . $value['totalQty'] . "<br>" !!}
                        @empty
                                -
                        @endforelse

                    </td>

                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['totalQTMT'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['projectName'] ?? '-' }}</td>
                    <td  style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['dateOfDelivery'] ? date(config('constant.admin_dob_format'), strtotime($report['dateOfDelivery'])) : '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['firmRegistrationNumber'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['actLicenceNumber'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['gstTinNumber'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['panText'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['firmType'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['lcin'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['shopWarehouseArea'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['modeOfPayment'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">{{ $report['budget'] ?? '-' }}</td>
                    <td style="padding:8px;  border:1px solid #DDDDDD;">
                        {{ $report['moveStatus']  == "Pending" ? 'In Progress' : 'Converted'  }}
                    </td>

                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
