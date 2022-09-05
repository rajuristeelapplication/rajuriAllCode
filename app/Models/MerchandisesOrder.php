<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MerchandisesOrder extends CustomModel
{
    use HasFactory;

    protected $table = 'merchandises_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'moType','userId','merchandisesOrderId','productOptionsId','orderQty','totalQty','orderDesc','orderAttachment','orderTextView','isDescription'
    ];


}
