<html>
<head>
    <title>{{__('labels.dealer_report_title')}}</title>
</head>

<body>
    <table id="customers" border="1">
        <tbody>
            <tr>
                <th colspan="2"></th>
                <th colspan="28">
                    <h3><strong> {{__('labels.dealer_report_title')}} </strong></h3>
                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}} : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>

            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date And Time</th>
                <th>User Type</th>
                <th>User Name</th>
                <th>Actor Type</th>
                <th>Dealer Type</th>
                <th>Dealer Name</th>
                <th>Dealer Id</th>
                <th>DOB</th>
                <th>{{__('labels.report_email')}}</th>
                <th>Whatsapp Mobile Number</th>
                <th>Firm Name</th>
                <th>Images</th>
                <th>Region</th>
                <th> Year of Incorporation</th>
                <th>Family Details.</th>
                <th>Aadhar Number</th>
                <th>Firm Registration Number</th>
                <th>Shop Act Licence Number</th>
                <th>GST TIN Number</th>
                <th>PAN</th>
                <th>Firm Type</th>
                <th>CIN</th>
                <th>Shop And Warehouse Area</th>
                <th>Mode of Payment</th>
                <th>Bank Name</th>
                <th>Address of Bank</th>
                <th>Account Number</th>
                <th>IFSC Code</th>
                <th>Nature of Account</th>
            </tr>

            {{-- <tr>
                <th>{{__('labels.no')}}</th>
                <th>{{__('labels.report_user_name')}}</th>
                <th>{{__('labels.report_dealer_name')}}</th>
                <th>{{__('labels.report_form_type')}}</th>
                <th>{{__('labels.report_dealer_type')}}</th>
                <th>{{__('labels.report_email')}}</th>
                <th>{{__('labels.report_mobile_number')}}</th>
                <th>{{__('labels.report_created_on')}}</th>
            </tr> --}}
            @forelse($reports as $key => $report)
            <tr>
                @php
                $key++;
                @endphp
                <td>{{ $key ?? '' }}</td>
                <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                <td>{{ $report['roleName'] ?? '-' }}</td>
                <td>{{ $report['createdBy'] ?? '' }}</td>
                <td>{{ $report['fType'] ?? '-' }}</td>
                <td>{{ $report['dType'] ?? '-' }}</td>
                <td>{{ $report['name'] ?? '-' }}</td>
                <td>{{ $report['dealerId'] ?? '-' }}</td>
                <td>{{ $report['dobFormate'] ?? '-' }}</td>
                <td>{{ $report['email'] ?? '-' }}</td>
                <td>{{ $report['wpMobileNumber'] ?? '-' }}</td>
                <td>{{ $report['firmName'] ?? '-' }}</td>
                <td>
                    @if(!empty($report['photo']))
                        {{ $report['photo'] }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $report['rName'] ?? '-' }}</td>
                <td>{{ $report['yearOfIncorporation'] ?? '-' }}</td>
                <td>{{ $report['familyDetails'] ?? '-' }}</td>
                <td>{{ $report['aadharNo'] ?? '-' }}</td>
                <td>{{ $report['cdFirmRegistrationNumber'] ?? '-' }}</td>
                <td>{{ $report['cdShopActLicenceNumber'] ?? '-' }}</td>
                <td>{{ $report['cdGstNumber'] ?? '-' }}</td>
                <td>{{ $report['cdPan'] ?? '-' }}</td>
                <td>{{ $report['cdFirmType'] ?? '-' }}</td>
                <td>{{ $report['cdCin'] ?? '-' }}</td>
                <td>{{ $report['cdShopWarehouseArea'] ?? '-' }}</td>
                <td>{{ $report['bdModeOfPayment'] ?? '-' }}</td>
                <td>{{ $report['bdBankName'] ?? '-' }}</td>
                <td>{{ $report['bdBankAddress'] ?? '-' }}</td>
                <td>{{ $report['bdAccountNumber'] ?? '-' }}</td>
                <td>{{ $report['bdIfscCode'] ?? '-' }}</td>
                <td>{{ $report['bdNatureOfAccount'] ?? '-' }}</td>
            </tr>



            {{-- <tr>
                    @php
                    $key++;
                    @endphp
                    <td>{{ $key ?? '' }}</td>
                    <td>{{ $report['createdBy'] ?? '' }}</td>
                    <td>{{ $report['name'] ?? '-' }}</td>
                    <td>{{ $report['fType'] ?? '-' }}</td>
                    <td>{{ $report['dType'] ?? '-' }}</td>
                    <td>{{ $report['email'] ?? '-' }}</td>
                    <td>{{ $report['mobileNumber'] ?? '-' }}</td>
                    <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                </tr> --}}
            @empty
            @endforelse

        </tbody>
    </table>
</body>

</html>
