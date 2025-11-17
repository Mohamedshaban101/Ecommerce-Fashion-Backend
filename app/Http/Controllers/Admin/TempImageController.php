<?php

namespace App\Http\Controllers\Admin;

use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver;

class TempImageController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all(),[
            'image'      => ['required' , 'image' , 'mimes:jpeg,png,jpg,svg,gif']
        ]);


        if($validator->fails()){
            return response()->json([
                'status'        => 422,
                'message'       => 'Ivalid Validation',
                'error'         => $validator->errors()
            ],422);
        }

        $image = $request->file('image');
        $imageName = time().'.'.$image->extension();
        $image->move(public_path('uploads/temp') , $imageName);
        $tempImage = TempImage::create([
            'name'      => $imageName   
        ]);

        $manager = new ImageManager(Driver::class);

        $img = $manager->read(public_path('uploads/temp/'.$imageName));
        $img->coverDown(400, 450);
        $img->save(public_path('uploads/temp/thumb/'.$imageName));

        
        return response()->json([
            'status'        => 200,
            'message'       => 'Image has been uploaded successfully',
            'data'         => $tempImage
        ],200);
    }
}
