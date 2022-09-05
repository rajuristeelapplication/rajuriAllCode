<html>
<head>
    <title>Attendance Report</title>
</head>

<body>
    <table id="customers">
        <tbody>
            <tr>
                <th colspan="2"></th>
                <th colspan="7">
                    <h3><strong> Attendance Report </strong></h3>

                    @if(!empty($reportInfo['rangeDate']))
                    <p> {{__('labels.date_of_report')}}  : {{ $reportInfo['rangeDate'] ?? '' }}</p>
                    @endif
                </th>
            </tr>
            <tr>
                <th>{{__('labels.no')}}</th>
                <th>Date And Time </th>
                <th>User Type </th>
                <th>User Name</th>
                <th>Punch In Time</th>
                <th>Punch Out Time </th>
                <th>In Reading Images </th>
                <th>Out Reading Images </th>
                <th>Total Working Hours </th>
                <th>Total Travel Km</th>
            </tr>
            @php
                    $totalMin = 0;
                    $totalKm = 0;
            @endphp

            @forelse($reports as $key => $report)
                <tr>
                    <td>{{ ++$key ?? '' }}</td>
                    <td>{{ $report['createdAt'] ? date(config('constant.report_date_format'), strtotime($report['createdAt'])) : '-' }}</td>
                    <td>{{ $report['roleName'] ?? '-' }}</td>
                    <td>{{ $report['fullName'] ?? '-' }}</td>
                    <td>{{ $report['inDateTime'] ? date(config('constant.report_date_format'), strtotime($report['inDateTime'])) : '-' }}</td>
                    <td>{{ $report['outDateTime'] ? date(config('constant.report_date_format'), strtotime($report['outDateTime'])) : '-' }}</td>
                    <td>
                        @if(!empty( $report['inReadingPhoto']))
                        <a target='_black' href='{{ $report['inReadingPhoto'] ?? '-' }}'> {{ $report['inReadingPhoto'] ?? '-' }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(!empty( $report['outReadingPhoto']))
                        <a target='_black' href='{{ $report['outReadingPhoto'] ?? NULL }}'>{{ $report['outReadingPhoto'] ?? NULL }}</a>
                            @else
                            -
                        @endif

                     </td>
                     <td>
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

                    @php
                     $totalKm += round($report['travelKm'],2) ?? 0;
                    @endphp

                    <td>{{ round($report['travelKm'],2) ?? '-' }}</td>
            @empty
            @endforelse

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td> Total  </td>
                <td>

                    @php

                    $hours = floor($totalMin / 60);
                    $min = $totalMin - ($hours * 60);

                    @endphp

{{ $hours.' hours '. $min .' min' }}

                </td>
                <td>{{ $totalKm }}</td>
            </tr>

        </tbody>
    </table>
</body>

</html>
