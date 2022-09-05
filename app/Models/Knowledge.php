<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Knowledge extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'knowledges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','rjDealerId','kdate','ktime','kCurrentLocation','kCurrentLatitude','kCurrentLongitude','kStartLocation','startAddressTitle','kStartLatitude',
        'kStartLongitude','ksStateId','ksSName','ksCityId','ksCName','ksTalukaId','ksTName','ksPinCode',
        'kDestination','destinationTitle','kDestinationLatitude','kDestinationLongitude','kdStateId','kdSName','kdCityId','kdCName','kdTalukaId',
        'kdTName','kdPinCode','vehicleNumber','startMeterReadingPhoto','endMeterReadingPhoto','kStatus','isActive','kStatusUpdateUserBy',
        'kEndLocation','kEndLatitude','kEndLongitude','kSourceLocation','kSourceTitle','kSourceLatitude','kSourceLongitude'
    ];

    public static function getSelectQuery()
    {
        $pathPanImage = config('constant.baseUrlS3') . config('constant.knowledge_image');

        return self::selectRaw('knowledges.id,dealers.fType,dealers.dealerId,dealers.firmName,dealers.name,dealers.dealerId,
        knowledges.kStatus,vehicleNumber,kStartLocation,kDestination,startAddressTitle,destinationTitle,
        IF(ISNULL(startMeterReadingPhoto) or startMeterReadingPhoto = "", "", CONCAT("'.$pathPanImage.'","/",startMeterReadingPhoto)) as startMeterReadingPhoto,
        IF(ISNULL(endMeterReadingPhoto) or endMeterReadingPhoto = "", "", CONCAT("'.$pathPanImage.'","/",endMeterReadingPhoto)) as endMeterReadingPhoto,
        kSourceLocation,kSourceTitle,kSourceLatitude,kSourceLongitude,
        DATE_FORMAT(knowledges.ktime, "' . config('constant.dealer_time_format') . '") as ktime,
        DATE_FORMAT(knowledges.kdate, "' . config('constant.schedule_date_format') . '") as kdateFormate,
        DATE_FORMAT(knowledges.ktime, "' . config('constant.schedule_time_format') . '") as ktimeFormate')
        ->join('dealers','dealers.id','knowledges.rjDealerId');
    }

    public static function knowledgeDetails()
    {

        // $pathPanImage = url('storage/images/knowledge');

        $pathPanImage = config('constant.baseUrlS3') . config('constant.knowledge_image');

        $result = self::getSelectQuery()->selectraw('knowledges.*,
        DATE_FORMAT(knowledges.ktime, "' . config('constant.dealer_time_format') . '") as ktime,
        IF(ISNULL(startMeterReadingPhoto) or startMeterReadingPhoto = "", "", CONCAT("'.$pathPanImage.'","/",startMeterReadingPhoto)) as startMeterReadingPhoto,
        IF(ISNULL(endMeterReadingPhoto) or endMeterReadingPhoto = "", "", CONCAT("'.$pathPanImage.'","/",endMeterReadingPhoto)) as endMeterReadingPhoto,
        startMeterReadingPhoto as shortNameStartMeterReadingPhoto,endMeterReadingPhoto  as shortNameEndMeterReadingPhoto
        ');
        return $result;
    }


}
