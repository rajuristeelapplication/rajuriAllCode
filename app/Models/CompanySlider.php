<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class CompanySlider extends CustomModel
{
    use HasFactory;

    protected $table = 'company_sliders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image','isActive'
    ];

    public static function getSelectQuery()
    {
        // $pathPhoto = url('storage/images/company_sliders');

        $pathPhoto = config('constant.baseUrlS3') . config('constant.company_sliders');

        return self::selectRaw('id,IF(ISNULL(image) or image = "", "", CONCAT("'.$pathPhoto.'","/",image)) as photo,isActive,createdAt');
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }

}
