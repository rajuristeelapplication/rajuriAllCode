<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Cities extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'countryId','stateId','cName','isActive'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('cities.id,cities.countryId,cities.stateId,cities.cName,cities.isActive');
    }

    /**
     * Get the States.
     */
    public static  function getState()
    {
       $query = self::selectRaw('cities.id, cities.countryId, cities.stateId, cities.cName, cities.isActive, states.sName')
       ->join('states','states.id','cities.stateId');

       return $query;
    }

    public static function scopeIsActive($query) {
        return $query->where('cities.isActive', 1);
    }
}
