<?php

namespace App\Http\Controllers\Admin\Products;

use Exception;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class StoreProductController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'title'             => ['required' , 'string'],
            'description'       => ['string' , 'nullable'],
            'short_description' => ['string' , 'nullable'],
            'price'             => ['required' , 'numeric'],
            'compare_price'     => ['numeric' , 'nullable'],
            'sku'               => ['required' , 'string' , 'unique:products,sku'],
            'status'            => ['boolean'],
            'barcode'           => ['string' , 'nullable'],
            'qty'               => ['integer' , 'nullable'],
            'gallery'             => ['nullable'],
            'is_featured'       => ['string'],
            'category'       => ['required' , 'integer'],
            'brand'          => ['required' , 'integer'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'error'         => $validator->errors(),
                'message'       => 'Invalid Validation'
            ],403);
        }

        try {
            $validated = $validator->validated();
            $product = Product::create([
                'title'             => $validated['title'],
                'description'       => $validated['description'] ?? '',
                'short_description' => $validated['short_description'] ?? '',
                'price'             => $validated['price'],
                'compare_price'     => $validated['compare_price'] ?? null,
                'qty'               => $validated['qty'] ?? null,
                'sku'               => $validated['sku'],
                'barcode'           => $validated['barcode'] ?? '',
                'status'            => $validated['status'] ?? 1,
                'is_featured'       => $validated['is_featured'] ?? 'no',
                'category_id'       => $validated['category'],
                'brand_id'          => $validated['brand']
            ]);
            $product->sizes()->sync($request->sizes);
            if($request->gallery){
                foreach($request->gallery as $key => $tempImageId){
                    $tempImage = TempImage::find($tempImageId);

                    // Large Thumbnail
                    $imageArray = explode('.' , $tempImage->name);
                    $imageExtension = end($imageArray);
                    $imageName = $product->id.'-'.time().uniqid().'.'.$imageExtension;
                    $manager = new ImageManager(Driver::class);
                    $img = $manager->read(public_path('uploads/temp/'.$tempImage->name));
                    $img->scaleDown(1200);
                    $img->save(public_path('uploads/products/large/'.$imageName));

                    // Small Thumbnail
                    $img = $manager->read(public_path('uploads/temp/'.$tempImage->name));
                    $img->coverDown(400,460);
                    $img->save(public_path('uploads/products/small/'.$imageName));

                    $productImage = new ProductImage();
                    $productImage->image = $imageName;
                    $productImage->product_id = $product->id;
                    $productImage->save();
                    if($key == 0){
                        $product->image = $imageName;
                        $product->save();
                    }
                }
            }
            return response()->json([
                'status'        => 200,
                'message'       => 'Product Created Successfully',
                'data'          => $product
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => 500,
                'errors'        => $e->getMessage(),  
            ] , 500);
        }
    }
}
