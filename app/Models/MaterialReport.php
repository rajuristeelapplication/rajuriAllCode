<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MaterialReport extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'material_reports';



    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'mrType','userId','leadId','materialId','totalQty','mName','msType','msName','materialTypeId',
        'isParent'
    ];


    public static function getSelectQuery()
    {
        return self::selectRaw('material_reports.*, leads.lFullName, users.fullName,
        DATE_FORMAT(material_reports.createdAt, "' . config('constant.in_out_date_time_format') . '") as createDateFormate')
        ->leftjoin('leads','leads.id','material_reports.leadId')
        ->leftjoin('users','users.id','material_reports.userId');
    }

}
