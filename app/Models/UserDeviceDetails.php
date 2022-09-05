<?php

namespace App\Models;

class UserDeviceDetails extends CustomModel
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deviceToken',
        'fcmToken',
        'userId'
    ];
}
