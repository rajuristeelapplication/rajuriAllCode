<?php

namespace App\Exports;

use App\Models\User;
use App\Helpers\ReportHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class MerchandiseOrderReportExport implements FromView, ShouldAutoSize
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

    //    echo "<pre>";
    //    print_r($this->request->all());
    //    exit;

        $pathPanImage = config('constant.baseUrlS3') . config('constant.merchandises_image');

        $reports = ReportHelper::merchandisesOrderReport($this->request, $this->type)
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

        if(!empty($this->request->mType)  && $this->request->mType != "All" && !empty($this->type))
        {
         $reports =   $reports->where(['merchandises.mType' => $this->request->mType]);
        }

        if(!empty($this->type))
        {
            $reports =   $reports->where(['merchandises.userId' => $this->request->userId]);
        }


        $reports = $reports->get();


        if(!empty($this->type))
        {
            $reportInfo = ReportHelper::reportInfo1($this->request->startDate,$this->request->endDate);
        }else{
            $reportInfo = ReportHelper::reportInfo($this->request);
        }


        return view('admin.exports.excel.report_merchandise',[
            'reports' => $reports,
            'reportInfo' => $reportInfo,
        ]);
    }
}
