<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use App\Models\Product_Image;
use File;
class ProductImageController extends Controller
{
    public function update(Request $request){
        $image              =   $request->image;
        $ext                =   $image->getClientOriginalExtension();
        $sPath              =   $image->getPathName();

        $productImage               =   new Product_Image();
        $productImage->product_id   =   $request->product_id;
        $productImage->image        =   'NULL';
        $productImage->save();

        $imageName                  =   $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image        =   $imageName;
        $productImage->save();
        //Generate Product Thumbnail// Large image
        $dPath      =   public_path().'/uploads/product/large/'.$imageName;
        $img   =   Image::make($sPath );
        // $img->resize(450, 600);
        $img->resize(1400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($dPath );
        //small image
        $dPath      =   public_path().'/uploads/product/small/'.$imageName;
        $img   =   Image::make($sPath );
        // $img->resize(450, 600);
        $img->fit(300, 300);
        $img->save($dPath );

        return response()->json([
            'status'=> true,
            'image_id'=> $productImage->id,
            'imagePath'=>asset('uploads/product/small/'.$productImage->image),
            'message'=> 'Image Uploaded Successfully'
        ]);
    }
    public function destory(Request $request){
        $productImage               =    Product_Image::find($request->id);
        if(empty($productImage)){
            return response()->json([
                'status'=> false,
                'message'=> 'Image Not Found'
            ]);
        }
        //Image Delete Form Folder
        File::delete(public_path('uploads/product/large/'.$productImage->image));
        File::delete(public_path('uploads/product/small/'.$productImage->image));
        //Delete form Data base
        $productImage->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'Image Deleted Successfully'
        ]);
    }
}
