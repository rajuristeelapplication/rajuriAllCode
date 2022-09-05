<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class CompanyProfile extends CustomModel
{
    use HasFactory;

    protected $table = 'company_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title','subTitle','description','isActive'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('id,title,subTitle,description');
    }

    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }
}
