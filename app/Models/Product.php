<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pName','pType'
    ];

    /**
     * Delete the Product Attribute when deleting Product.
     */
    public static function boot() {
        parent::boot();

        static::deleting(function($product) { // before delete() method call this
            $product->productOptions->each->delete();
        });
    }


    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }


    public function productOptions()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'productId', 'id');
    }

    public  static function getSelectQuery($pType,$userId,$allocationUser = '')
    {


        return self::selectRaw('id,pName,pSlug,isProductOption,pType,
        (select count(id) from user_product_qty where user_product_qty.productId = products.id AND userId = "'.$userId.'"  ) as isGift,
        (select IF(sum(product_attributes.totalQty) >= 1,1,0) from product_attributes where product_attributes.productId = products.id   ) as isShowRecord
        ')
            ->with([
                'productOptions' => function ($query) use($pType,$userId,$allocationUser) {
                    $query->selectRaw('product_attributes.id,product_attributes.productId,productOptionName,
                    CONCAT("₹ ",product_attributes.totalQty) as totalQty ,isDescription,isAttachment,
                    REPLACE(CONCAT("₹ ",product_attributes.totalQty), ".00", "") as totalQty,IF(product_attributes.totalQty = 0.0,0,1) as isShowRecord');

                    if($pType == "Gift" || $pType == "gift")
                    {

                        $query->selectRaw('user_product_qty.totalQty')
                            ->join('user_product_qty', function ($join)  use($userId){
                                $join->on('user_product_qty.productAttributesId', '=', 'product_attributes.id')
                                    ->where('user_product_qty.userId',  $userId);
                            })->where('user_product_qty.totalQty','>',0)->whereNull('user_product_qty.deletedAt');
                    }

                    if(!empty($allocationUser))
                    {
                        $query->selectRaw('user_product_qty.totalQty')
                            ->leftjoin('user_product_qty', function ($join)  use($userId){
                                $join->on('user_product_qty.productAttributesId', '=', 'product_attributes.id')
                                    ->where('user_product_qty.userId',  $userId);
                            });
                    }

                }
            ]);
    }

}
