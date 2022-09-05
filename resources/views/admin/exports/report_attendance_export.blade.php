<html>
<head>
    <title>Attendance Report</title>
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
                <th colspan="7"
                    style="text-align:center;background-color: #e94262; padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
                    <h3><strong style="color: #ffff;"> Attendance Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p style=" color: #ffff;"> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    {{__('labels.no')}}</th>
                {{-- <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Date & Time </th> --}}
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                        User Type </th>
               <th style="width:20%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                User Name</th>
                <th style="width:15%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Punch In Time</th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Punch Out Time </th>

                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                   In Reading Images </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD">
                    Out Reading Images </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD;white-space: nowrap;">
                        Total Working Hours </th>
                <th style="width:10%; color: white; background-color: #e94262;  padding: 12px; text-align: left; border:1px solid #DDDDDD;white-space: nowrap;">
                            Total Travel Km </th>

            </tr>

            @php
                    $totalMin = 0;
                    $totalKm = 0;
            @endphp

            @forelse($reports as $key => $report)
                <tr>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ ++$key ?? '' }}</td>
                    {{-- <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td> --}}
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['roleName'] ?? '-' }}</td>
                    <td style="padding:8px; width:20%; border:1px solid #DDDDDD;">{{ $report['fullName'] ?? '-' }}</td>
                    <td style="padding:8px; width:15%; border:1px solid #DDDDDD;">{{ $report['inDateTime'] ? date(config('constant.report_date_format'), strtotime($report['inDateTime'])) : '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ $report['outDateTime'] ? date(config('constant.report_date_format'), strtotime($report['outDateTime'])) : '-' }}</td>
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">
                        <a target="_black" href="{{ $report['inReadingPhoto'] }}" >
                        <img style="height: 80px;width:80px"   src="{{ $report['inReadingPhoto'] }}" />
                        </a>
                    </td>
                    @if(!empty($report['outReadingPhoto']))
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">
                        <a  target="_black" href="{{ $report['outReadingPhoto'] }}" >
                          <img style="height: 80px;width:80px" src="{{ $report['outReadingPhoto'] }}" />
                        </a>
                    </td>
                    @else
                        <td style="padding:8px; width:10%; border:1px solid #DDDDDD;"> - </td>
                    @endif
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">

                        @if(!empty($report['outDateTime']))

                        @php
                            $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $report['inDateTime']);
                            $endTime = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $report['outDateTime']);

                            $totalMin += $startTime->diffInMinutes($endTime);

                            $totalDuration =  $startTime->diff($endTime)->format('%H hours %I')." min";
                        @endphp
                                 {{ $totalDuration  ?? '-'}}
                        @else
                            -
                        @endif
                    </td>
                    {{-- {{ $report['diffHours'] ?? '-' }} --}}

                    @php
                            $totalKm += round($report['travelKm'],2) ?? 0;
                    @endphp
                    <td style="padding:8px; width:10%; border:1px solid #DDDDDD;">{{ round($report['travelKm'],2) ?? '-' }}</td>

                </tr>
            @empty
            @endforelse

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;text-align:right" colspan="2"> Total  </td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;" >

                    @php

                    $hours = floor($totalMin / 60);
                    $min = $totalMin - ($hours * 60);

                    @endphp

                    {{ $hours.' hours '. $min .' min' }}

                </td>
                <td style="padding:8px; width:15%; border:1px solid #DDDDDD;" >

                    {{ $totalKm }}</td>
            </tr>

        </tbody>
    </table>
</body>

</html>
