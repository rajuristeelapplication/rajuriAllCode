<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseType extends CustomModel
{
    use HasFactory;

    protected $table = 'expense_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'eName','type','title'
    ];

    public static function getSelectQuery()
    {
        return self::selectRaw('id,eName,type,isActive');
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

}
