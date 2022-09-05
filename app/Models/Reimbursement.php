<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Reimbursement extends CustomModel
{
    use HasFactory;

    protected $table = 'reimbursements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','rjDealerId','expenseId','rName','fromAddress','toAddress','dateOfTravelling','fuel','totalAmount',
        'description','projectName','rLocation','rQuantity','paymentStatus','invoiceNumber','rStatus','rStatusUpdateUserBy'
    ];

    public function reimbursementsImage()
    {
        return $this->hasMany('App\Models\ReimbursementImage', 'reimbursementsId', 'id');
    }

    public static function getSelectQuery()
    {
        // $pathPanImage = url('storage/images/reimbursement');

        $pathPanImage = config('constant.baseUrlS3') . config('constant.reimbursement_image');
        // IF(ISNULL(rAttachment) or rAttachment = "", "", CONCAT("'.$pathPanImage.'","/",rAttachment)) as rAttachment,
        // rAttachment  as shortNameRAttachment,

        return self::selectRaw('reimbursements.*,
        CONCAT("' . config('constant.rupess_symbol') . '","",totalAmount) as totalAmountFormate,
        DATE_FORMAT(reimbursements.dateOfTravelling, "' . config('constant.schedule_date_time_format') . '") as dateOfTravellingFormate,
        DATE_FORMAT(reimbursements.createdAt, "' . config('constant.schedule_date_time_format') . '") as dateOfCreateAtFormate,
        expense_types.eName,dealers.fType,dealers.dType,dealers.dealerId,dealers.name
        ')
        ->leftjoin('expense_types','expense_types.id','reimbursements.expenseId')
        ->leftjoin('dealers','dealers.id','reimbursements.rjDealerId')
        ->with([
            'reimbursementsImage' => function ($query) use($pathPanImage) {
                $query->selectRaw('reimbursements_image.id,reimbursements_image.reimbursementsId,
                IF(ISNULL(reimbursements_image.rAttachment) or reimbursements_image.rAttachment = "", "", CONCAT("'.$pathPanImage.'","/",reimbursements_image.rAttachment)) as rAttachment');
            }
        ]);


        // return self::selectRaw('reimbursements.id,reimbursements.userId, expense_types.eName,rStatus,
        //             CONCAT("' . config('constant.rupess_symbol') . '","",totalAmount) as totalAmountFormate,
        //             DATE_FORMAT(reimbursements.dateOfTravelling, "' . config('constant.schedule_date_format') . '") as dateOfTravellingFormate,
        //             IF(ISNULL(rAttachment) or rAttachment = "", "", CONCAT("'.$pathPanImage.'","/",rAttachment)) as rAttachment,
        //             rAttachment  as shortNameRAttachment,
        //             DATE_FORMAT(reimbursements.createdAt, "' . config('constant.schedule_date_format') . '") as dateOfCreateAtFormate
        //             ')
        //             ->leftjoin('expense_types','expense_types.id','reimbursements.expenseId');
    }


    public static function reimbursementDetails()
    {
        return  self::getSelectQuery();
    }

}
