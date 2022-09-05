<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReimbursementReportExport implements FromView,ShouldAutoSize
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

        $reportInfo = ReportHelper::reportInfo($this->request);

        // $reports = ReportHelper::reimbursementReport($this->request, $type = NULL)
        //                         ->selectRaw('users.fullName, reimbursements.id, reimbursements.rName')
        //                         ->join('users', 'users.id', 'reimbursements.userId')
        //                         ->orderBy('reimbursements.createdAt', 'desc')
        //                         ->get();

        $reports = ReportHelper::reimbursementReport($this->request, $type = NULL)
        ->selectRaw('users.fullName, reimbursements.id, reimbursements.rName,users.roleId,roles.roleName')
        ->join('users', 'users.id', 'reimbursements.userId')
        ->join('roles', 'roles.id', 'users.roleId');

            if(!empty($this->request->userType))
            {
             $reports =  $reports->where('users.roleId',$this->request->userType);
            }

            if(!empty($this->request->userId))
            {
             $reports =  $reports->where('users.id',$this->request->userId);
            }

            $reports = $reports->orderBy('reimbursements.createdAt', 'desc')
                    ->get();

        // echo "<pre>";
        // print_r($reports->toArray());
        // exit;

        return view('admin.exports.excel.report_reimbursement', [
            'reports' => $reports,
            'reportInfo' => $reportInfo,
            'reimbursementType' => $this->request->reimbursementType
        ]);
    }
}
