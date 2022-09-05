<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'product_attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'productId','productOptionName','totalQty','isDescription', 'isAttachment', 'isSubProduct'
    ];


    public static function ProductAvailabilityCheck($where,$merchandisesRecord)
    {


        if($merchandisesRecord->mType == "Gift" || $merchandisesRecord->mType == "gift")
        {

            return ProductAttribute::selectRaw('product_attributes.id,isSubProduct,product_attributes.productOptionName,product_attributes.totalQty,products.pName,product_attributes.isDescription')
                    ->join('products','products.id','product_attributes.productId')
                    ->selectRaw('user_product_qty.totalQty')
                            ->join('user_product_qty', function ($join)  use($merchandisesRecord){
                                $join->on('user_product_qty.productAttributesId', '=', 'product_attributes.id')
                                    ->where('user_product_qty.userId',  $merchandisesRecord->userId);
                            })

                    ->where($where)->first();

        }else{
            return ProductAttribute::selectRaw('product_attributes.id,isSubProduct,productOptionName,totalQty,pName,product_attributes.isDescription')
            ->join('products','products.id','product_attributes.productId')
            ->where($where)->first();
        }

    }



}
