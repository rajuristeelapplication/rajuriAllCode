<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class MarketingAdminEmployees extends CustomModel
{
    use HasFactory;

    protected $table = 'marketing_admin_employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mAdminId','employeeId'
    ];
}
