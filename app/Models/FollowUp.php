<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowUp extends CustomModel
{
    use HasFactory;

    protected $table = 'follow_ups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','rjDealerId','fPurpose','fPurposeText','fDate','fTime','fDateTime',
        'fReminder','fIsDone'
    ];

    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

    public static function getSelectQuery()
    {
        return self::selectRaw('follow_ups.*,dealers.dealerId,fPurpose,fIsDone,dealers.fType,dealers.firmName,dealers.name,
                CASE WHEN follow_ups.fReminder IS NOT NULL THEN 1 ELSE 0 END AS isfReminder,
                CASE WHEN date(follow_ups.fReminder) >= "'.Carbon::today().'" THEN 1 ELSE 0 END AS isFutureReminder,
                DATE_FORMAT(follow_ups.fReminder, "' . config('constant.schedule_date_format') . '") as rDateFormate,
                DATE_FORMAT(follow_ups.fReminder, "' . config('constant.schedule_time_format') . '") as rTimeFormate,
                DATE_FORMAT(follow_ups.fDate, "' . config('constant.schedule_date_format') . '") as fDateFormate,
                DATE_FORMAT(follow_ups.fTime, "' . config('constant.schedule_time_format') . '") as fTimeFormate,
                CONCAT(DATE_FORMAT(follow_ups.fReminder, "' . config('constant.schedule_date_format') . '") ," | ",DATE_FORMAT(follow_ups.fReminder, "' . config('constant.schedule_time_format') . '")) as rDateTimeFormate
        ')
        ->join('dealers','dealers.id','follow_ups.rjDealerId');;
    }

}
