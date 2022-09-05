<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class LeaveRequest extends CustomModel
{
    use HasFactory;

    protected $table = 'leave_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','lType','fromDate','toDate','noOfLeave','leaveReasonId','otherReasonText','lRStatus','IRStatusUpdateUserBy','adminRejectOtherText'
    ];


    public static function getSelectQuery()
    {
        return self::selectRaw('leave_requests.id,lType,expense_types.eName as reason,otherReasonText,leave_requests.userId,adminRejectOtherText,
        DATE_FORMAT(leave_requests.fromDate, "' . config('constant.schedule_date_format') . '") as fromDateFormate,
        DATE_FORMAT(leave_requests.toDate, "' . config('constant.schedule_date_format') . '") as toDateFormate,
        noOfLeave,lRStatus
        ')
        ->leftjoin('expense_types','expense_types.id','leave_requests.leaveReasonId');
    }

}
