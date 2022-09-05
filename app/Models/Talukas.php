<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Talukas extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'talukas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'countryId','stateId','cityId','tName'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('id,countryId,stateId,cityId,tName');
    }

    /**
     * Get the States, City.
     */
    public static function getStateCity()
    {
       $query = self::selectRaw('talukas.id, talukas.countryId, talukas.stateId, talukas.tName, talukas.isActive, talukas.cityId, talukas.createdAt, states.sName, cities.cName')
       ->join('states','states.id','talukas.stateId')
       ->join('cities','cities.id','talukas.cityId');

       return $query;
    }

    public static function scopeIsActive($query) {
        return $query->where('talukas.isActive', 1);
    }
}
