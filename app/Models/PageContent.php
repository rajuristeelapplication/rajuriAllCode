<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class PageContent extends CustomModel
{
    use HasFactory;

    protected $table = 'page_contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','slug','content'
    ];

}
