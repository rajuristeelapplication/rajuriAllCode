<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MerchandiseReportExport implements FromView,ShouldAutoSize
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

           $pathPanImage = config('constant.baseUrlS3') . config('constant.merchandises_image');
        // $reports = ReportHelper::merchandisesReport($this->request, $type = NULL)->get();

        $reports = ReportHelper::merchandisesReport($this->request, $type = NULL)
        ->with([
            'orderProducts' => function ($query) use($pathPanImage) {

                $query->selectRaw('merchandises_orders.*,
                IF(ISNULL(orderAttachment) or orderAttachment = "", "", CONCAT("'.$pathPanImage.'","/",orderAttachment)) as orderAttachment,
                product_attributes.productOptionName,product_attributes.productId,products.pName,product_attributes.isSubProduct
                ')
                ->leftJoin('product_attributes','product_attributes.id','merchandises_orders.productOptionsId')
                ->leftJoin('products','products.id','product_attributes.productId');
            }
        ])->get();



        $reportInfo = ReportHelper::reportInfo($this->request);


        return view('admin.exports.excel.report_merchandise',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
