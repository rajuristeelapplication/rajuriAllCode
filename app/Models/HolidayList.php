<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class HolidayList extends CustomModel
{
    use HasFactory;

    protected $table = 'holiday_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pdf','isActive','title'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('id,title,
                         DATE_FORMAT(holiday_lists.hDate, "' . config('constant.holiday_first_day') . '") as hDateMonthDay,
                         DATE_FORMAT(holiday_lists.hDate, "' . config('constant.holiday_month') . '") as hDateMonth,
                         DATE_FORMAT(holiday_lists.hDate, "' . config('constant.holiday_day') . '") as hDateDay,
                         hDate');
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

}
