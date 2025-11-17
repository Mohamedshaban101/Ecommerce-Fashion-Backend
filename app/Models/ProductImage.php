<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id' , 'image'];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(){
        if($this->image === ''){
            return '';
        }
        return asset('/uploads/products/small/'.$this->image);
    }
    public function Product(){
        return $this->belongsTo(Product::class);
    }
}
