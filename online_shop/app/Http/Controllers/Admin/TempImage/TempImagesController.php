<?php

namespace App\Http\Controllers\Admin\TempImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DropzoneRequest;
use App\Models\TempImage;

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
            return response()->json([
                'status'=> true,
                'image_id'=>$tempImage->id,
                'message'=> 'Image Uploaded Successfully'
            ]);
        }
    }
}
