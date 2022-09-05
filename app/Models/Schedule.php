<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'schedules';

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'rjDealerId','sType','sDate','userId','isAdmin','sTime','sFirmName','purpose','purposeText','slocation','slatitude','slongitude','schedulesStatus',
        'startLocation','startLatitude','startLongitude','endLocation','endLatitude','endLongitude','uploadPhoto','watermarkImage',
        'voiceRecording','dAddress1','dAddress2','dStateId','dSName','dCityId','dCName','dTalukaId','dTName','dPinCode',
        'dRegionId','dRName','dWpMobileNumber','dMobileNumber','brandUsed','construction','ourMaterialsAvailable',
        'otherBrandName','otherBrandFormattedName','dTotalQty','competitorActivitiesText','competitorActivitiesImage','typeOfSite','statusOfSite',
        'area','estimatedCost','projectEngineer','architect','executive','usedTillNow','tentativeSchedule',
        'completedProject','ongoingProject','anyLead','feedback','additionVisit','materialPhoto','isReport','dvrDate'
    ];

     /**
     * Dealer Listing Query.
     *
     * @return query object
     */

    public static function getSelectQuery($userId = 0)
    {
        return  self::selectRaw('schedules.id,schedules.userId,dealers.fType,schedules.rjDealerId,dealers.dType,dealers.dealerId,schedules.sType,schedules.sFirmName,schedules.purpose,
                                dealers.name,schedules.sDate,schedules.dRName,schedules.dTotalQty,schedules.usedTillNow,schedules.slocation,
                                schedules.tentativeSchedule,schedules.completedProject,schedules.ongoingProject,schedules.anyLead,
                               DATE_FORMAT(schedules.sDate, "' . config('constant.schedule_date_format') . '") as sDateFormate,
                               DATE_FORMAT(schedules.sTime, "' . config('constant.schedule_time_format') . '") as sTimeFormate,
                                schedulesStatus,
                                IF(schedulesStatus = "Start",1,0) as greenDot,
                                isReport,dvrDate,
                                DATE_FORMAT(dvrDate, "' . config('constant.schedule_date_format') . '") as dvrFormate
                         ')
                       ->join('dealers','dealers.id','schedules.rjDealerId');
    }

     /**
     * Dealer Details Query.
     *
     * @return query object
     */

    public static function scheduleDetails()
    {
        $pathPhoto = config('constant.baseUrlS3') . config('constant.schedule_image');
        $pathPhoto1 = config('constant.baseUrlS3') . config('constant.dealer_image');

        return self::selectRaw('schedules.*,
            IF(ISNULL(dealers.photo) or dealers.photo = "", "", CONCAT("'.$pathPhoto1.'","/",dealers.photo)) as photo,photo as shortNamePhoto,
            IF(ISNULL(uploadPhoto) or uploadPhoto = "", "", CONCAT("'.$pathPhoto.'","/",uploadPhoto)) as uploadPhoto,
            IF(ISNULL(voiceRecording) or voiceRecording = "", "", CONCAT("'.$pathPhoto.'","/",voiceRecording)) as voiceRecording,
            IF(ISNULL(watermarkImage) or watermarkImage = "", "", CONCAT("'.$pathPhoto.'","/",watermarkImage)) as watermarkImage,
            IF(ISNULL(materialPhoto) or materialPhoto = "", "", CONCAT("'.$pathPhoto.'","/",materialPhoto)) as materialPhoto,
            uploadPhoto as shortNameUploadPhoto,watermarkImage as shortNameWaterMarkImage,voiceRecording as shortNameVoiceRecording,
            materialPhoto as shortNameMaterialPhoto,
            IF(schedulesStatus = "Start",1,0) as greenDot,
            DATE_FORMAT(schedules.sTime, "' . config('constant.dealer_time_format') . '") as sTime,
            DATE_FORMAT(schedules.sDate, "' . config('constant.schedule_date_format') . '") as sDateFormate,
            DATE_FORMAT(schedules.sTime, "' . config('constant.schedule_time_format') . '") as sTimeFormate,
            IF(ISNULL(competitorActivitiesImage) or competitorActivitiesImage = "", "", CONCAT("'.$pathPhoto.'","/",competitorActivitiesImage)) as competitorActivitiesImage,
            competitorActivitiesImage as shortNameCompetitorActivitiesImage,dealers.fType,dealers.dealerId,dealers.name,
            dealers.location,dealers.latitude,dealers.longitude,
            dealers.stateId,dealers.sName,dealers.cityId,dealers.cName,dealers.talukaId,dealers.tName,dealers.pinCode,dealers.regionId,dealers.rName,
            dealers.wpMobileNumber,dealers.mobileNumber,dealers.address1,dealers.address2,
            DATE_FORMAT(dvrDate, "' . config('constant.schedule_date_format') . '") as dvrFormate
        ')
        ->join('dealers','dealers.id','schedules.rjDealerId');
    }

    // public static function scheduleDetails()
    // {

    //     $pathPhoto = config('constant.baseUrlS3') . config('constant.schedule_image');
    //     $pathPhoto1 = config('constant.baseUrlS3') . config('constant.dealer_image');

    //     return self::selectRaw('schedules.*,
    //     IF(ISNULL(dealers.photo) or dealers.photo = "", "", CONCAT("'.$pathPhoto1.'","/",dealers.photo)) as photo,photo as shortNamePhoto,
    //         IF(ISNULL(uploadPhoto) or uploadPhoto = "", "", CONCAT("'.$pathPhoto.'","/",uploadPhoto)) as uploadPhoto,
    //         IF(ISNULL(voiceRecording) or voiceRecording = "", "", CONCAT("'.$pathPhoto.'","/",voiceRecording)) as voiceRecording,
    //         IF(ISNULL(watermarkImage) or watermarkImage = "", "", CONCAT("'.$pathPhoto.'","/",watermarkImage)) as watermarkImage,
    //         uploadPhoto as shortNameUploadPhoto,voiceRecording as shortNameVoiceRecording,
    //         IF(schedulesStatus = "Start",1,0) as greenDot,
    //         DATE_FORMAT(schedules.sTime, "' . config('constant.dealer_time_format') . '") as sTime,
    //         DATE_FORMAT(schedules.sDate, "' . config('constant.schedule_date_format') . '") as sDateFormate,
    //         DATE_FORMAT(schedules.sTime, "' . config('constant.schedule_time_format') . '") as sTimeFormate,
    //         IF(ISNULL(competitorActivitiesImage) or competitorActivitiesImage = "", "", CONCAT("'.$pathPhoto.'","/",competitorActivitiesImage)) as competitorActivitiesImage,
    //         competitorActivitiesImage as shortNameCompetitorActivitiesImage,dealers.fType,dealers.dealerId,dealers.name,
    //         dealers.location,dealers.latitude,dealers.longitude,dealers.address1,dealers.address2,
    //         dealers.stateId,dealers.sName,dealers.cityId,dealers.cName,dealers.talukaId,dealers.tName,dealers.pinCode,dealers.regionId,dealers.rName,
    //         dealers.wpMobileNumber,dealers.mobileNumber,dealers.piTypeOfSite,dealers.piStatusOfSite,dealers.piArea,dealers.piEstimateCost,dealers.piProjectEngineer,
    //         dealers.piArchitect,dealers.piExecutor,dealers.msRequriedMaterial,dealers.msUtillNow,dealers.msCompletedProject,dealers.msOnGoingProject,dealers.msYearOfIncorporation,
    //         dealers.mobileNumber,dealers.wpMobileNumber

    //     ')
    //     ->join('dealers','dealers.id','schedules.rjDealerId');
    // }



}
