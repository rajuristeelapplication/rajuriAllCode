<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Dealer extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'dealers';

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'fType','dType','userId','date','time','location','latitude','longitude','name',
        'dealerId','firmName','address1','address2','stateId','sName',
        'cityId','cName','talukaId','tName','pinCode','regionId','rName',
        'wpMobileNumber','mobileNumber','email','dob','photo','shopPhoto',
        'yearOfIncorporation','aadharNo','familyDetails','msRequriedMaterial',
        'msUtillNow','msCompletedProject','msOnGoingProject','msYearOfIncorporation',
        'piTypeOfSite','piStatusOfSite','piArea','piEstimateCost','piProjectEngineer',
        'piArchitect','piExecutor','cdFirmRegistrationNumber','cdShopActLicenceNumber',
        'cdGstNumber','cdPan','cdFirmType','cdCin','cdShopWarehouseArea','bdModeOfPayment',
        'bdBankName','bdBankAddress','bdAccountNumber','bdIfscCode','bdNatureOfAccount','dealerStatus'
    ];


     /**
     * Dealer Listing Query.
     *
     * @return query object
     */

    public static function getSelectQuery()
    {
        // $pathPhoto = url('storage/images/dealer');

        $pathPhoto = config('constant.baseUrlS3') . config('constant.dealer_image');

        return  self::selectRaw('id,fType,dType,name,email,wpMobileNumber,mobileNumber,dealerId,stateId,sName,cityId,cName,talukaId,tName,dob,latitude,longitude,dealerStatus,
        DATE_FORMAT(dealers.dob, "' . config('constant.schedule_date_format') . '") as dobFormate,pinCode,statusDealers,
        IF(ISNULL(dealers.photo) or dealers.photo = "", "", CONCAT("'.$pathPhoto.'","/",dealers.photo)) as photo,photo as shortNamePhoto,dealers.firmName,dealers.location,dealers.address1,dealers.address2,dealers.regionId,dealers.rName');
    }

     /**
     * Dealer Details Query.
     *
     * @return query object
     */

    public static function dealerDetails()
    {
        // $pathPhoto = url('storage/images/dealer');
        $pathPhoto = config('constant.baseUrlS3') . config('constant.dealer_image');

        return self::selectRaw('dealers.*,
                IF(ISNULL(photo) or photo = "", "", CONCAT("'.$pathPhoto.'","/",photo)) as photo,
                IF(ISNULL(shopPhoto) or shopPhoto = "", "", CONCAT("'.$pathPhoto.'","/",photo)) as shopPhoto,
                photo as shortNamePhoto, shopPhoto as shortNameShopPhoto,
                DATE_FORMAT(dealers.time, "' . config('constant.dealer_time_format') . '") as time,
                DATE_FORMAT(dealers.date, "' . config('constant.schedule_date_format') . '") as dDateFormate,
                DATE_FORMAT(dealers.time, "' . config('constant.schedule_time_format') . '") as dTimeFormate,
                DATE_FORMAT(dealers.dob, "' . config('constant.schedule_date_format') . '") as dobFormate
        ');
    }


    /**
     * Dealer And Users Details Query.
     *
     * @return query object
     */

    public static function dealerAndUsersDetails()
    {
        // $pathPhoto = url('storage/images/dealer');
        // $pathShopPhoto = url('storage/images/dealer');

        $pathPhoto = config('constant.baseUrlS3') . config('constant.dealer_image');

        return self::selectRaw('dealers.*,users.fullName as createdBy,roles.roleName,
        users.roleId,
        IF(ISNULL(photo) or photo = "", "", CONCAT("'.$pathPhoto.'","/",photo)) as photo,
        IF(ISNULL(shopPhoto) or shopPhoto = "", "", CONCAT("'.$pathPhoto.'","/",shopPhoto)) as shopPhoto,
        photo as shortNamePhoto, shopPhoto as shortNameShopPhoto,
        DATE_FORMAT(dealers.time, "' . config('constant.dealer_time_format') . '") as time,
        DATE_FORMAT(dealers.date, "' . config('constant.schedule_date_format') . '") as dDateFormate,
        DATE_FORMAT(dealers.time, "' . config('constant.schedule_time_format') . '") as dTimeFormate,
        DATE_FORMAT(dealers.dob, "' . config('constant.schedule_date_format') . '") as dobFormate')
        ->join('users', 'users.id', '=', 'dealers.userId')
        ->join('roles', 'roles.id', '=', 'users.roleId');
    }


}
