<html>
<head>
    <title>{{__('labels.dealer_report_title')}}</title>
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
                <th colspan="28"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> {{__('labels.dealer_report_title')}} </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                <th style="width:7%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Date & Time</th>
                <th style="width:6%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            User Type</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    User Name</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Actor Type</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Dealer Type</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Dealer Name</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Dealer Id</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            DOB</th>
                <th style="width:6%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.report_email')}}</th>

                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Whatsapp Mobile Number</th>

                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Firm Name</th>

                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Images</th>

                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Region</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Year of Incorporation</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Family Details.</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Aadhar Number</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Firm Registration Number</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        Shop Act Licence Number</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        GST TIN Number</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        PAN</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Firm Type</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            CIN</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Shop & Warehouse Area</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Mode of Payment</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Bank Name</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Address of Bank</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Account Number</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            IFSC Code</th>
                <th style="width:3%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                            Nature of Account</th>

            </tr>
            @forelse($reports as $key => $report)
                <tr>
                    @php
                    $key++;
                    @endphp
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $key ?? '' }}</td>
                    <td style="padding:8px; width:7%; border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td style="padding:8px; width:6%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['createdBy'] ?? '' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['fType'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['dType'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['name'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['dealerId'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['dobFormate'] ?? '-' }}</td>
                    <td style="padding:8px; width:6%; border:1px solid #DDDDDD;">{{ $report['email'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['wpMobileNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['firmName'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">
                        @if(!empty($report['photo']))
                            <img width="80px" height="80px" src="{{ $report['photo'] }} " />
                        @else
                            -
                        @endif

                    </td>

                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['rName'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['yearOfIncorporation'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['familyDetails'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['aadharNo'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['cdFirmRegistrationNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['cdShopActLicenceNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['cdGstNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['cdPan'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['cdFirmType'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['cdCin'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['cdShopWarehouseArea'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['bdModeOfPayment'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['bdBankName'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['bdBankAddress'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['bdAccountNumber'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['bdIfscCode'] ?? '-' }}</td>
                    <td style="padding:8px; width:3%; border:1px solid #DDDDDD;">{{ $report['bdNatureOfAccount'] ?? '-' }}</td>

                </tr>
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
