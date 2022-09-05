<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class  LeadJson extends CustomModel
{
    use HasFactory;

    protected $table = 'lead_jsons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lead_mt_jsons'
    ];

}
