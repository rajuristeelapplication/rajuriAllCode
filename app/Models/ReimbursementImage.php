<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReimbursementImage extends CustomModel
{
    use HasFactory;

    protected $table = 'reimbursements_image';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reimbursementsId','rAttachment'
    ];

    public static function getSelectQuery()
    {
        // $pathPanImage = url('storage/images/reimbursement');

        $pathPanImage = config('constant.baseUrlS3') . config('constant.reimbursement_image');

        return self::selectRaw('reimbursements.reimbursementsId,
        IF(ISNULL(rAttachment) or rAttachment = "", "", CONCAT("'.$pathPanImage.'","/",rAttachment)) as rAttachment,
        ');
    }

}
