<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MarketingVanReportExport implements FromView,ShouldAutoSize
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
        $reports = ReportHelper::marketingVanRequestReport($this->request, $type = NULL)
        ->selectRaw('users.fullName, del.fType,del.dealerId,del.firmName,del.name,
        del.dealerId, DATE_FORMAT(knowledges.kdate, "' . config('constant.schedule_date_format') . '") as kdateFormate,
        DATE_FORMAT(knowledges.ktime, "' . config('constant.schedule_time_format') . '") as ktimeFormate')
        ->join('users', 'users.id', 'knowledges.userId')
        ->join('dealers as del', 'del.id', 'knowledges.rjDealerId')
        ->orderBy('knowledges.kDate', 'desc')
        ->get();
        $reportInfo = ReportHelper::reportInfo($this->request);



        return view('admin.exports.excel.report_marketing_van_request',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
