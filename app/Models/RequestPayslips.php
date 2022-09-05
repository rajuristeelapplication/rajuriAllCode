<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class RequestPayslips extends CustomModel
{

    protected $table = 'request_payslips';

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = ['userId', 'rPDate'];

}
