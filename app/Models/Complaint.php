<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class  Complaint extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'complaints';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','rjDealerId','cType','cDate','cTime','cEmail',
        'cSiteLocation','cSiteLatitude','cSiteLongitude','cAddress',
        'cStateId','cSName','cCityId','cCName','cTalukaId','cTName','cPinCode','cWpMobileNumber',
        'cMobileNumber','cProductNameBillingDetails','cTotalQty','cLotNumber','cTruckNumber',
        'cComments','cRemarks','cTName','cPincode','projectName','totalQTMT','dateOfDelivery',
        'firmRegistrationNumber','cPhotoVideo','cStatus','complaintType','cStatusUpdateUserBy',
        'otherComplaint'
    ];

    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

    public static function getSelectQuery()
    {
        return self::selectRaw('complaints.id,complaints.cType,complaints.mtListView,complaints.mtListEdit,complaints.cTotalQty,complaints.cTotalQty,
        complaints.cStatus,complaints.complaintType,complaints.cDesc,complaints.cSiteLocation,complaints.cAddress,complaints.cWpMobileNumber,cProductNameBillingDetails,
        mtListView,cTotalQty,cLotNumber,cTruckNumber,cComments,cRemarks,
        DATE_FORMAT(complaints.cDate, "' . config('constant.schedule_date_format') . '") as cDateFormate,
        DATE_FORMAT(complaints.cTime, "' . config('constant.schedule_time_format') . '") as cTimeFormate');
    }


    public static function complaintDetails()
    {
        // $pathPanImage = url('storage/images/knowledge');

        $pathPanImage = config('constant.baseUrlS3') . config('constant.complaint_image');


        return self::getSelectQuery()->selectraw('complaints.*,dealers.fType,dealers.dealerId,dealers.firmName,dealers.name,
        IF(ISNULL(cPhotoVideo) or cPhotoVideo = "", "", CONCAT("'.$pathPanImage.'","/",cPhotoVideo)) as cPhotoVideo,
        cPhotoVideo as shortNamecPhotoVideo')
        ->join('dealers','dealers.id','complaints.rjDealerId');

    }



}
