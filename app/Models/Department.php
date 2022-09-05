<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Department extends CustomModel
{
    use HasFactory;

    protected $table = 'departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dName','isActive'
    ];



    /**
     * Delete the Head of Department when deleting department.
     */
    public static function boot() {
        parent::boot();

        static::deleting(function($department) { // before delete() method call this
            $department->headDepartment->each->delete();
        });
    }

    public static function getSelectQuery()
    {
        return self::selectRaw('id,dName,isActive');
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }


    /**
     * Get the Head of Department.
     */
    public function headDepartment()
    {
        return $this->hasMany(HeadDepartment::class,'departmentId', 'id');
    }



}
