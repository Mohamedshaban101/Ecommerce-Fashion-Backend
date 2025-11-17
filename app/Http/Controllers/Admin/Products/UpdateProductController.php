<?php

namespace App\Http\Controllers\Admin\Products;

use Exception;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class UpdateProductController extends Controller
{
    public function __invoke(Request $request , $id){
        $product = Product::find($id);
        if($product == null){
            return response()->json([
                'status'        => 404,
                'message'       => 'Product Not Found',
            ],404);
        }
        $validator = Validator::make($request->all() , [
            'title'             => ['required' , 'string'],
            'description'       => ['string'  , 'nullable'],
            'short_description' => ['string' , 'nullable'],
            'price'             => ['required' , 'numeric'],
            'compare_price'     => ['numeric' , 'nullable'],
            'sku'               => ['required' , 'string' , Rule::unique('products','sku')->ignore($id)],
            'status'            => ['required' , 'boolean'],
            'barcode'           => ['string' , 'nullable'],
            'qty'               => ['integer' , 'nullable'],
            'is_featured'       => ['required','string'],
            'category'       => ['required' , 'integer'],
            'brand'          => ['required' , 'integer'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status'        => 422,
                'error'         => $validator->errors(),
                'message'       => 'Invalid Validation'
            ],422);
        }

        try {
            $validated = $validator->validated();
            $product->update([
                'title'             => $validated['title'],
                'description'       => $validated['description'],
                'short_description' => $validated['short_description'],
                'price'             => $validated['price'],
                'compare_price'     => $validated['compare_price'],
                'qty'               => $validated['qty'],
                'sku'               => $validated['sku'],
                'barcode'           => $validated['barcode'],
                'status'            => $validated['status'],
                'is_featured'       => $validated['is_featured'],
                'category_id'       => $validated['category'],
                'brand_id'          => $validated['brand']
            ]);
            if($request->has('sizes')){
                $product->sizes()->sync($request->sizes);
            }
            if($request->gallery){
                $oldImages = ProductImage::where('product_id', $product->id)->get();
                $currentGalleryIds = $request->gallery;
                foreach($oldImages as $old){
                    if(!in_array($old->id, $currentGalleryIds)){
                        $largePath = public_path('uploads/products/large/'.$old->image);
                        $smallPath = public_path('uploads/products/small/'.$old->image);
                        if(file_exists($largePath)) unlink($largePath);
                        if(file_exists($smallPath)) unlink($smallPath);
                        $old->delete();
                    }
                }
                // $old->delete();
                foreach($request->gallery as $key => $tempImageId){
                    $tempImage = TempImage::find($tempImageId);
                    if($tempImage){
                        $imageArray = explode('.' , $tempImage->name);
                        $imageExtension = end($imageArray);
                        $imageName = $product->id.'-'.time().uniqid().'.'.$imageExtension;
                        $manage = new ImageManager(Driver::class);
                        $img = $manage->read(public_path('uploads/temp/'.$tempImage->name));
                        $img->scaleDown(1200);
                        $img->save(public_path('uploads/products/large/'.$imageName));

                        $img = $manage->read(public_path('uploads/temp/'.$tempImage->name));
                        $img->coverDown(400,460);
                        $img->save(public_path('uploads/products/small/'.$imageName));

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $imageName
                        ]);
                        if($key == 0){
                            $product->image = $imageName;
                            $product->save();
                        }
                        continue;
                    }
                    $existingImage = ProductImage::find($tempImageId);
                    if($existingImage){
                        continue;
                    }
                }
            }
            return response()->json([
                'status'        => 200,
                'message'       => 'Product Updated Successfully',
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
