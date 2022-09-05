<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class  Lead extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lType','lFullName','lCompanyName','lShopName','lMobileNumber','lEmail',
        'dRegionId','dRName','pAddress','pAddress2','userId',
        'platitude','plongitude','pStateId','pSName','pCityId','pCName','pTalukaId','pTName',
        'pPincode','isSameAddress','cAddress','clatitude','clongitude','cStateId','cSName','cCityId',
        'cCName','cTalukaId','cTName','cPincode','projectName','totalQTMT','dateOfDelivery',
        'firmRegistrationNumber','actLicenceNumber','gstTinNumber','panImage','panText','firmType','lcin',
        'shopWarehouseArea','modeOfPayment','attachmentImage','descriptionStore','spaceAvailable',
        'budget','orderQty'
    ];

    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

    public static function getSelectQuery()
    {
        return self::selectRaw('leads.id,leads.lType,leads.lFullName,leads.panText,leads.pAddress,leads.firmType,leads.totalQTMT,leads.mtListView,leads.moveStatus,leads.pCityId');
    }

    public static function leadDetails()
    {
        // $pathPanImage = url('storage/images/dealer');
        // $pathAttachmentImage = url('storage/images/dealer');

        $pathPhoto = config('constant.baseUrlS3') . config('constant.dealer_image');



        return self::selectRaw('leads.*,
        IF(ISNULL(panImage) or panImage = "", "", CONCAT("'.$pathPhoto.'","/",panImage)) as panImage,
        IF(ISNULL(attachmentImage) or attachmentImage = "", "", CONCAT("'.$pathPhoto.'","/",attachmentImage)) as attachmentImage,
        panImage as shortNamePanImage, attachmentImage as shortNameAttachmentImage,
        DATE_FORMAT(leads.dateOfDelivery, "' . config('constant.schedule_date_format') . '") as dateOfDeliveryFormate
        ')

        ;
    }

}
