<?php

namespace App\Models;

use App\Models\Size;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = ['product_id' , 'size_id'];
}
