<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeadDepartment extends CustomModel
{
    use HasFactory;

    protected $table = 'head_departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'departmentId','hdName','isActive'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('id,departmentId,hdName,isActive');
    }

    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

}
