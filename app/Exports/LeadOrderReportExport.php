<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class LeadOrderReportExport implements FromView,ShouldAutoSize
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


        $reports = ReportHelper::leadReport($this->request, $this->type);

        if(!empty($this->request->lType)  && $this->request->lType != "All")
        {
         $reports =   $reports->where(['leads.lType' => $this->request->lType]);
        }

        if(!empty($this->type))
        {
            $reports =   $reports->where(['leads.userId' => $this->request->userId]);
        }

        $reports = $reports->get();


        if(!empty($this->type))
        {
            $reportInfo = ReportHelper::reportInfo1($this->request->startDate,$this->request->endDate);
        }else{
            $reportInfo = ReportHelper::reportInfo($this->request);
        }



        return view('admin.exports.excel.report_lead',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
