<?php

namespace App\Http\Controllers\Admin\TempImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TempImagesController extends Controller
{
    public function create(Request $request){
        $image  =   $request->image();
        if(!empty($image)){
            $ext                =   $image->getClientOriginalExtension();
            $newName            =   time().'.'.$ext;
            $tempImage          =   new TempImage();
            $tempImage->name    =   $newName;
            $tempImage->save();
            $image->move(public_path().'/temp',$newName);
            return response()->json([
                'status'=> true,
                'image_id'=>$tempImage->id,
                'message'=> 'Image Uploaded Successfully'
            ]);
        }
    }
}
