<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regions extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'regions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'countryId','stateId','rName','isActive'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('id,countryId,stateId,rName');
    }


    /**
     * Get the States, Taluka.
     */
    public static function getStateTaluka()
    {
       $query = self::selectRaw('regions.id, regions.countryId, regions.stateId, regions.rName, regions.isActive, regions.createdAt, states.sName')
       ->leftjoin('states','states.id','regions.stateId');

       return $query;
    }
}
