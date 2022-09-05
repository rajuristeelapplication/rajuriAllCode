<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProductQty extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'user_product_qty';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','productAttributesId','productId','totalQty'
    ];

}
