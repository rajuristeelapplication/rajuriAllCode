<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class  MaterialType extends CustomModel
{
    use HasFactory;

    protected $table = 'material_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mName', 'isActive'
    ];


    public static function scopeIsActive($query)
    {
        return $query->where('isActive', 1);
    }

    public function Straight()
    {
        return $this->hasMany('App\Models\materialSubType', 'materialTypeId', 'id')->where('msType', 'Straight')->orderBy('orderByKey', 'asc');
    }

    public function Bend()
    {
        return $this->hasMany('App\Models\materialSubType', 'materialTypeId', 'id')->where('msType', 'Bend')->orderBy('orderByKey', 'asc');
    }

    public function straightBend()
    {
        return $this->hasMany('App\Models\materialSubType', 'materialTypeId', 'id')->orderBy('orderByKey', 'asc');
    }

    public  static function getMaterialType()
    {
        // return MaterialType::selectRaw('id,mName,totalQty')
        //     ->with([
        //         'Straight' => function ($query) {
        //             $query->selectRaw('id,materialTypeId,msType,msName,totalQty');
        //         },
        //         'Bend' => function ($query) {
        //             $query->selectRaw('id,materialTypeId,msType,msName,totalQty');
        //         }
        //     ]);

        return MaterialType::selectRaw('id,mName,totalQty,isSubOption')
            ->with([
                'straightBend' => function ($query) {
                    $query->selectRaw('id,materialTypeId,msType,msName,totalQty');
                }
            ]);

    }
}
