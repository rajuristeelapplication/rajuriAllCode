<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Brochure;
use App\Models\PaySlip;
use App\Models\Notification;
use App\Helpers\ReportHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Exports\VisitReportExport;
use App\Exports\LeadOrderReportExport;
use App\Exports\MerchandiseOrderReportExport;
use App\Models\State;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * send response to user.
     *
     * @return json
     */
    public function toJson($result = [], $message = '', $status = 1)
    {
        // return response()->json([
        //     'status' => $status,
        //     'result' => !empty($result) ? $result : new \stdClass(),
        //     'message' => $message,
        // ]);


        return response()->json([
            'status' => $status,
            'result' => !empty($result) ? $result : new \stdClass(),
            'message' => $message,
        ], 200, [], JSON_PRESERVE_ZERO_FRACTION);
    }

    public function __construct(Request $request)
    {
        $allStates = State::selectRaw('states.*')
                    // ->join('cities','cities.stateId','states.id')
                    // ->groupBy('states.id')
                    ->where('states.isActive',1)
                    ->get();



        view()->share(['allStates' => $allStates]);
    }
    /**
     * Show the check unique value.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUnique(Request $request, $table, $columnName)
    {
        if ($request->ajax()) {

            if (!empty($table)) {
                if ($table == 'users') {
                    if (!empty($request->value)) {
                        $where = [
                            [$columnName, '=', $request->value],
                        ];

                        if (!empty($request->id)) {
                            $where[] = ['id', '!=', $request->id];
                        }

                        $where[] = ['deletedAt', '=', null];

                        $count = DB::table($table)
                            ->where($where)
                            ->count();

                        return $count > 0 ?  'false' : 'true';
                    }
                }
            }
            if (!empty($request->value)) {
                $where = [
                    [$columnName, '=', $request->value],
                ];

                if (!empty($request->id)) {
                    $where[] = ['id', '!=', $request->id];
                }

                $count = DB::table($table)
                    ->where($where)
                    ->count();

                return $count > 0 ?  'false' : 'true';
            }
        }
    }


    /**
     * Brochures Pdf Download.
     *
     * @int id
     *
     * return download pdf
     */

    public function brochuresDownloadPdf($id)
    {
        $downloadPdf = Brochure::getSelectQuery()->where('id', $id)->first();

        try {

            $file= storage_path().'/app/public/'.$downloadPdf->pdf1;

            $headers = array(
                      'Content-Type: application/pdf',
                    );

            return \Response::download($file, 'brochures.pdf', $headers);

            // return response()->streamDownload(function ()  use ($downloadPdf) {
            //     echo file_get_contents($downloadPdf->pdf);
            // }, 'download.pdf');
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    /**
     * pay slip Pdf From App Side
     *
     * @param  mixed $request
     * @return view or pdf download
     */

    public function paySlipDownloadPdf($id)
    {
        $downloadPdf = PaySlip::getSelectQuery()->where('id', $id)->first();



        try {

            $file= storage_path().'/app/public/'.$downloadPdf->payPdf1;

            $headers = array(
                      'Content-Type: application/pdf',
                    );

            return \Response::download($file, 'paySlip.pdf', $headers);

            // return response()->streamDownload(function ()  use ($downloadPdf) {
            //     echo file_get_contents($downloadPdf->payPdf);
            // }, $downloadPdf->monthYear.'.pdf');
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    /**
     * merchandises Pdf From App Side
     *
     * @param  mixed $request
     * @return view or pdf download
     */
    public function merchandisesPdf(Request $request)
    {

        try {

            $pathPanImage = config('constant.baseUrlS3') . config('constant.merchandises_image');


            $userId = Crypt::decryptString($request->userId);
            $startDate = Crypt::decryptString($request->startDate);
            $endDate = Crypt::decryptString($request->endDate);
            $type = Crypt::decryptString($request->type);
            $mType = Crypt::decryptString($request->mType);
            $stateId = Crypt::decryptString($request->stateId);
            $cityId = Crypt::decryptString($request->cityId);
            $talukaId = Crypt::decryptString($request->talukaId);
            $search = Crypt::decryptString($request->search);

            $request = (object)['startDate' => $startDate, 'endDate' => $endDate,'stateId' => $stateId,'cityId' => $cityId,'talukaId' => $talukaId,'search' =>$search,'mType' => $mType,'userId' => $userId];

            $merchandisesReport =  ReportHelper::merchandisesReport($request, "api")
                ->with([
                    'orderProducts' => function ($query) use($pathPanImage) {

                        $query->selectRaw('merchandises_orders.*,
                        IF(ISNULL(orderAttachment) or orderAttachment = "", "", CONCAT("'.$pathPanImage.'","/",orderAttachment)) as orderAttachment,
                        product_attributes.productOptionName,product_attributes.productId,products.pName,product_attributes.isSubProduct
                        ')
                        ->leftJoin('product_attributes','product_attributes.id','merchandises_orders.productOptionsId')
                        ->leftJoin('products','products.id','product_attributes.productId');
                    }
                ]);

               if($mType != "All")
               {
                $merchandisesReport =   $merchandisesReport->where(['merchandises.mType' => $mType]);
               }
               $merchandisesReport =   $merchandisesReport->where(['merchandises.userId' => $userId])
                ->get();

                // echo "<pre>";
                // print_r($merchandisesReport->toArray());
                // exit;


            $reportInfo = ReportHelper::reportInfo1($startDate, $endDate);

            if ($type == "pdf") {

                $pdf = PDF::loadView('admin.exports.report_merchandise', [
                    'reports' => $merchandisesReport,
                    'reportInfo' => $reportInfo,
                ])->setPaper('a4', 'landscape');

                $filename =  $userId . '_merchandise_' . time() . ".pdf";

                return $pdf->download($filename);
            }else if($type == "excel"){

                $filename =  $userId . '_merchandise_' . time() . ".xlsx";
                $type = "api";

                return (new MerchandiseOrderReportExport($request,$type))->download($filename);

            } else {
                return view('admin.exports.report_merchandise', [
                    'reports' => $merchandisesReport,
                    'reportInfo' => $reportInfo,
                ]);
            }
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    /**
     * Lead Pdf From App Side
     *
     * @param  mixed $request
     * @return view or pdf download
     */


    public function leadPdf(Request $request)
    {
        // try {
            $userId = Crypt::decryptString($request->userId);
            $startDate = Crypt::decryptString($request->startDate);
            $endDate = Crypt::decryptString($request->endDate);
            $type = Crypt::decryptString($request->type);
            $lType = Crypt::decryptString($request->lType);
            $stateId = Crypt::decryptString($request->stateId);
            $cityId = Crypt::decryptString($request->cityId);
            $talukaId = Crypt::decryptString($request->talukaId);
            $search = Crypt::decryptString($request->search);

            $request = (object)['startDate' => $startDate, 'endDate' => $endDate ,'stateId' => $stateId,'cityId' => $cityId,'talukaId' => $talukaId,'search' =>$search,'lType' => $lType,'userId' => $userId ];

            $merchandisesReport =  ReportHelper::leadReport($request, "api");

            if($lType != "All")
            {
             $merchandisesReport =   $merchandisesReport->where(['leads.lType' => $lType]);
            }

            $merchandisesReport =   $merchandisesReport->where(['leads.userId' => $userId])
                ->get();

            $reportInfo = ReportHelper::reportInfo1($startDate, $endDate);

            if ($type == "pdf") {

                $pdf = PDF::loadView('admin.exports.report_lead', [
                    'reports' => $merchandisesReport,
                    'reportInfo' => $reportInfo,
                ])->setPaper('a4', 'landscape');

                $filename =  $userId . '_lead_' . time() . ".pdf";

                return $pdf->download($filename);
            }
            else if($type == "excel"){

                $filename =  $userId . '_lead_' . time() . ".xlsx";
                $type = "api";

                return (new LeadOrderReportExport($request,$type))->download($filename);

            }
             else {
                return view('admin.exports.report_lead', [
                    'reports' => $merchandisesReport,
                    'reportInfo' => $reportInfo,
                ]);
            }
        // } catch (\Exception $e) {
        //     echo 'Message: ' . $e->getMessage();
        // }
    }

    /**
     * Daily Pdf From App Side
     *
     * @param  mixed $request
     * @return view or pdf download
     */

    public function dailyPdf(Request $request)
    {
        try {

            $userId = Crypt::decryptString($request->userId);
            $startDate = Crypt::decryptString($request->startDate);
            $endDate = Crypt::decryptString($request->endDate);
            $type = Crypt::decryptString($request->type);
            $sType = Crypt::decryptString($request->sType);
            $stateId = Crypt::decryptString($request->stateId);
            $cityId = Crypt::decryptString($request->cityId);
            $talukaId = Crypt::decryptString($request->talukaId);
            $search = Crypt::decryptString($request->search);

            $request = (object)['startDate' => $startDate, 'endDate' => $endDate,'stateId' => $stateId,'cityId' => $cityId,'talukaId' => $talukaId,'search' =>$search,'sType' => $sType,'userId' => $userId];

            $merchandisesReport =  ReportHelper::dailyReport($request, "api");

            if($sType != "All")
            {
             $merchandisesReport = $merchandisesReport->where(['schedules.sType' => $sType]);
            }
            $merchandisesReport = $merchandisesReport->where(['schedules.userId' => $userId, 'schedules.schedulesStatus' => 'End'])
                ->get();

            $reportInfo = ReportHelper::reportInfo1($startDate, $endDate);

            if ($type == "pdf") {

                $pdf = PDF::loadView('admin.exports.report_daily_update', [
                    'reports' => $merchandisesReport,
                    'reportInfo' => $reportInfo,
                ])->setPaper('a4', 'landscape');

                $filename =  $userId . '_daily_update_' . time() . ".pdf";

                return $pdf->download($filename);
            }elseif($type == "excel")
            {
                $type = "api";
                return (new VisitReportExport($request,$type))->download('Visit_Report_' . date('d-m-Y') . '.xlsx');
            }
             else {
                return view('admin.exports.report_daily_update', [
                    'reports' => $merchandisesReport,
                    'reportInfo' => $reportInfo,
                ]);
            }
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }


    public function notificationCount(Request $request)
    {
        $notificationCount = Notification::where('isRead',0)->where('isAdmin',0)->count();

        return $this->toJson(['notificationCount' => $notificationCount]);
    }
}
