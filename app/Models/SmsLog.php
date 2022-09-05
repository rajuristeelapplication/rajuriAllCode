<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class SmsLog extends CustomModel
{
    use HasFactory;
    protected $table = 'sms_log';

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = ['model', 'userId', 'senderId', 'title'];




}
