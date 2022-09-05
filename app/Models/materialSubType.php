<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class  materialSubType extends CustomModel
{
    use HasFactory;

    protected $table = 'material_sub_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'materialTypeId','msType','msName','isActive','orderByKey'
    ];


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

    public function straightBend()
    {
        return $this->hasMany('App\Models\materialSubType', 'materialTypeId', 'id')->where('msType', 'Bend')->orderBy('orderByKey', 'asc');
    }

}
