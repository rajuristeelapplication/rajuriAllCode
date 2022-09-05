<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Brochure extends CustomModel
{
    use HasFactory;

    protected $table = 'brochures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pdf','isActive','title'
    ];

    /**
     * get Select Query
     *
     * @return object
     */

    public static function getSelectQuery()
    {
        // $pathPhoto = url('storage/images/brochures');
        $pathPhoto = config('constant.baseUrlS3') . config('constant.brochures_image');

        $pathPhoto1 =  config('constant.brochures_image');

        $publicPath = url('brochures');

        return self::selectRaw('id,title,IF(ISNULL(pdf) or pdf = "", "", CONCAT("'.$pathPhoto.'","/",pdf)) as pdf,
        IF(ISNULL(pdf) or pdf = "", "", CONCAT("'.$pathPhoto1.'","/",pdf)) as pdf1,
        IF(ISNULL(image) or image = "", "", CONCAT("'.$pathPhoto.'","/",image)) as image,
        CONCAT("'.$publicPath.'","/",id) as downloadPdf,isActive');
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

}
