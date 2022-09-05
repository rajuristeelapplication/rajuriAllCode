<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class PaySlip extends CustomModel
{
    use HasFactory;

    protected $table = 'payslips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','payPdf','isActive','month'
    ];

    public static function getSelectQuery()
    {
        // $pathPhoto = url('storage/images/pay_slip');

        // $pathPhoto = url('storage/images/pay_slip');

        $pathPhoto = config('constant.baseUrlS3') . config('constant.pay_slip');

        $pathPhoto1 = config('constant.pay_slip');


        $publicPath = url('pay-slip');

        return self::selectRaw('id,IF(ISNULL(payPdf) or payPdf = "", "", CONCAT("'.$pathPhoto.'","/",payPdf)) as payPdf,
        IF(ISNULL(payPdf) or payPdf = "", "", CONCAT("'.$pathPhoto1.'","/",payPdf)) as payPdf1,
        DATE_FORMAT(payslips.month, "' . config('constant.payslip_month_year') . '") as monthYear,
                                CONCAT("'.$publicPath.'","/",id) as downloadPdf,isActive,month');
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

}
