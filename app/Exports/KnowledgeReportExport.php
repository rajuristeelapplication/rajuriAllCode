<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KnowledgeReportExport implements FromView,ShouldAutoSize
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

        $reports = ReportHelper::reportKnowledgeExport($this->request);
        $reportInfo = ReportHelper::reportInfo($this->request);



        return view('admin.exports.excel.report_knowledge_export',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
