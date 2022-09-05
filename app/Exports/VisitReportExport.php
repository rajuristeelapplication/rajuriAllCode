<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitReportExport implements FromView,ShouldAutoSize
{

	use Exportable;

    protected $request = null;
    protected $type = null;

    /**
     * construct
     **/
    public function __construct($request,$type = NULL)
    {

        $this->request = $request;
        $this->type = $type;
    }

    public function view(): View
    {

        $reports = ReportHelper::dailyReport($this->request, $this->type);

        if(!empty($this->request->sType)  && $this->request->sType != "All")
        {
         $reports =   $reports->where(['schedules.sType' => $this->request->sType]);
        }

        if(!empty($this->type))
        {
            $reports =   $reports->where(['schedules.userId' => $this->request->userId, 'schedules.schedulesStatus' => 'End']);
        }

        $reports = $reports->get();

        if(!empty($this->type))
        {
            $reportInfo = ReportHelper::reportInfo1($this->request->startDate,$this->request->endDate);
        }else{
            $reportInfo = ReportHelper::reportInfo($this->request);
        }


        return view('admin.exports.excel.report_daily_update',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
