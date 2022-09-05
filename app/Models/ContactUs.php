<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ContactUs extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'contact_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'departmentId','headDepartmentId','email','mobileNumber','message'
    ];

}
