<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class InOut extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'in_outs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inAddress','inLatitude','inLongitude','inReadingPhoto','inDateTime','outAddress','outLatitude','outLongitude','outReadingPhoto','outDateTime','date'
    ];

    public static function getSelectQuery()
    {
        // $pathPhotoId = url('storage/images/inout');

        $pathPhotoId = config('constant.baseUrlS3') . config('constant.in_out_image');

        return self::selectRaw('in_outs.*,inReadingPhoto as shortInReadingPhoto,outReadingPhoto as shortOutReadingPhoto,
        IF(ISNULL(inReadingPhoto) or inReadingPhoto = "", "", CONCAT("'.$pathPhotoId.'","/",inReadingPhoto)) as inReadingPhoto,
        IF(ISNULL(outReadingPhoto) or outReadingPhoto = "", "", CONCAT("'.$pathPhotoId.'","/",outReadingPhoto)) as outReadingPhoto');
    }

     /**
     * check User Today Date In Out
     *
     * @return Array
     *
     */

    public static function checkUserTodayDateInOut($userId)
    {
        $now = Carbon::now()->format('Y-m-d');

        return self::getSelectQuery()->where(['userId' => $userId])->whereDate('date',$now)->first();
    }

     /**
     * checkStatus
     *
     * @return Array
     *
     */


    public static function checkStatus($userId)
    {
        $status = self::checkUserTodayDateInOut($userId);

        if(empty($status))
        {
            return ['status' => 0,'details' => new \stdClass()];
        }else{
            $check =  empty($status->outDateTime) ? 1 : 2;

            return ['status' => $check,'details' => $status];
        }
    }
}
