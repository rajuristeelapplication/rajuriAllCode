<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MaterialReportExport implements FromView,ShouldAutoSize
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
        $reports = ReportHelper::materialReport($this->request, $type = NULL)
        ->orderBy('material_reports.createdAt', 'desc')
        ->where('material_reports.isParent',0)
        ->get();
        $reportInfo = ReportHelper::reportInfo($this->request);



        return view('admin.exports.excel.report_material',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
