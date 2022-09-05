<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class LeaveTraction extends CustomModel
{
    use HasFactory;

    protected $table = 'leave_tractions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','leaveRequestId','lRType','noOfLeave','lTStatus'
    ];


    public static function insertLeaveTraction($userId,$leaveRequestId,$lRType,$noOfLeave,$lTStatus)
    {

        $leaveTraction = new self();
        $leaveTraction->userId = $userId ?? NULL;
        $leaveTraction->leaveRequestId = $leaveRequestId ?? NULL;
        $leaveTraction->lRType = $lRType ?? NULL;
        $leaveTraction->noOfLeave = $noOfLeave ?? NULL;
        $leaveTraction->lTStatus = $lTStatus ?? NULL;
        $leaveTraction->save();

    }

}
