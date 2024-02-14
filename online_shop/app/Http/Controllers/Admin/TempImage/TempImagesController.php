<?php

namespace App\Http\Controllers\Admin\TempImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DropzoneRequest;
use App\Models\TempImage;
use Image;

class TempImagesController extends Controller
{
    public function create(Request $request){
        $image  =   $request->image;
        if(!empty($image)){
            $ext                =   $image->getClientOriginalExtension();
            $imageName          =   time().'.'.$ext;
            $tempImage          =   new TempImage();
            $tempImage->name    =   $imageName;
            $tempImage->save();

            $image->move(public_path().'/temp',$imageName);

            //Generate Thumbnail
            $sourcePath = public_path().'/temp/'.$imageName;
            $destPath = public_path().'/temp/thumb/'.$imageName;
            $image  =   Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destPath);

            return response()->json([
                'status'=> true,
                'image_id'=>$tempImage->id,
                'imagePath'=>asset('/temp/thumb/'.$imageName),
                'message'=> 'Image Uploaded Successfully'
            ]);
        }
    }
}
