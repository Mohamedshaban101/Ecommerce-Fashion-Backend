<?php

namespace App\Models;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title' , 'description' , 'short_description' , 'price' , 'compare_price' , 'image' , 'brand_id' , 'category_id','qty' , 'sku' , 'barcode' , 'status' , 'is_featured'];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(){
        if($this->image === ''){
            return '';
        }
        return asset('/uploads/products/small/'.$this->image);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }
    public function sizes(){
        return $this->belongsToMany(Size::class , 'product_sizes' , 'product_id' , 'size_id');
    }
}
