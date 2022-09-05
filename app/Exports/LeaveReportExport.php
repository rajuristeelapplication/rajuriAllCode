<?php

namespace App\Exports;


use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeaveReportExport implements FromView,ShouldAutoSize
{

	use Exportable;

    protected $request = null;

    /**
     * construct
     **/
    public function __construct($request)
    {

        $this->request = $request;
    }

    public function view(): View
    {
       	$reportHelper = new ReportHelper();

           $reports = ReportHelper::leaveReport($this->request, $type = NULL);
        //    ->selectRaw('users.fullName, leave_requests.id, DATE_FORMAT(leave_requests.createdAt, "' . config('constant.in_out_date_time_format') . '") as createdAtFormate')
        //    ->join('users', 'users.id', 'leave_requests.userId')
        //    ->where('leave_requests.lRStatus', '!=', 'Credit')
        //    ->orderBy('leave_requests.createdAt', 'desc')
        //    ->get();



           $reportInfo = ReportHelper::reportInfo($this->request);


        return view('admin.exports.excel.report_leave',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
