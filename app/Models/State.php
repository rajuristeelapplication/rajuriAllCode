<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'states';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'countryId','sName'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('states.id, states.sName, states.isActive');
    }

    public static function scopeIsActive($query) {
        return $query->where('states.isActive', 1);
    }
}
