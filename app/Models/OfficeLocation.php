<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class OfficeLocation extends CustomModel
{
    use HasFactory;

    protected $table = 'office_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title','subTitle','address','email'
    ];

    protected $casts = [
        'email' => 'array',
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('title,subTitle,address,email');
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

}
