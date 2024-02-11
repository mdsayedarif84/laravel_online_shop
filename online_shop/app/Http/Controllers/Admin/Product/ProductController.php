<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\Brand;
use App\Models\Product;
use File;
use Image;


class ProductController extends Controller
{
    public function create(){
        $data               =   [];
        $categories         =   Category::orderBy('name','desc')->get();
        $brands             =   Brand::orderBy('name','desc')->get();
        $data['categories'] =   $categories;
        $data['brands']     =   $brands;
        return view('admin.product.create',$data);
    }
    public function store(){
        $rules= [
            'title'=>'required',
            'slug'=>'required',
            'price'=>'required|numeric',
            'sku'=>'required',
            'track_qty'=>'required|in:Yes, NO',
            'category'=>'required|numeric',
            'is_featured'=>'required|in:Yes, NO',
        ];
        [
            'name.required' => 'Enter Your Name!',
            'slug.required' => 'Input Correct slug & unique!',
        ];
        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        };
        $validator = Validator::make($request->all(),$rules);
        
        if($validator->passes()){
            $product          =   new Product();
            $product->name    =   $request->name;
            $product->slug    =   $request->slug;
            $product->status  =   $request->status;
            $product->save();
            
            // Save Image
            if( !empty($request->image_id)){
                $tempImage  =   TempImage::find($request->image_id);
                $extArray   =   explode('.',$tempImage->name);
                $ext        =   last($extArray);

                $newImageName=  $category->id.'.'.$ext;
                $sPath      =   public_path().'/temp/'.$tempImage->name;
                $dPath      =   public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                //generated Image Thumbnail
                $dPath      =   public_path().'/uploads/category/thumb/'.$newImageName;
                $img   =   Image::make($sPath );
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath );

                $category->image  =   $newImageName;
                $category->save();
            }
            $request->session()->flash('success','Category Added  Successfully');
            return response()->json([
                'status'=> true,
                'errors'=>"Category Added  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}
