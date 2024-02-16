<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Product_Image;
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
    public function store(Request $request){
        // dd($request->image_array);
        // exit();
        $rules= [
            'title'=>'required',
            'slug'=>'required|unique:products',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products',
            'track_qty'=>'required|in:Yes, NO',
            'category'=>'required|numeric',
            'is_featured'=>'required|in:Yes, NO',
        ];
        [
            'title.required' => 'Enter Your Name!',
            'slug.required' => 'Input Correct slug & unique!',
        ];
        if(!empty( $request->track_qty ) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        };
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $product                =   new Product();
            $product->title         =   $request->title;
            $product->slug          =   $request->slug;
            $product->description   =   $request->description;
            $product->price         =   $request->price;
            $product->compare_price =   $request->compare_price;
            $product->sku           =   $request->sku;
            $product->barcode       =   $request->barcode;
            $product->track_qty     =   $request->track_qty;
            $product->qty           =   $request->qty;
            $product->status        =   $request->status;
            $product->category_id   =   $request->category;
            $product->sub_category_id  =   $request->sub_category;
            $product->brand_id      =   $request->brand_id;
            $product->is_featured   =   $request->is_featured;
            $product->save();

            //Save Gallery Pics
            if(!empty($request->image_array)){
                foreach($request->image_array as $tem_img_id){
                    $tempImgInfo     =   TempImage::find($tem_img_id);
                    $extArray       =   explode('.',$tempImgInfo->name);// like name 1707459095
                    $ext            =   last($extArray); // like as jpg,png jpeg,git etc
                    $productImage   =   new Product_Image();
                    $productImage->product_id   =   $product->id;
                    $productImage->image        =   'NULL';
                    $productImage->save();

                    $imageName      =   $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image        =   $imageName;
                    $productImage->save();

                    //Generate Product Thumbnail// Large image
                    $sPath      =   public_path().'/temp/'.$tempImgInfo->name;
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
                }
            }
         
            $request->session()->flash('success','Product Added  Successfully');
            return response()->json([
                'status'=> true,
                'success'=>"Product Added  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function list(Request $request){
        $products   =   Product::latest('id')->with('productImage');
        // dd($products);
        if (!empty($request->get('keyword'))){
            $products= $products->where('title','like','%'.$request->get('keyword').'%');
        }
        $products =   $products->paginate();
        $data['products']=$products;
        return view('admin.product.list',$data);

    }
    public function edit($id,Request $request){
        $product            =   Product::find($id);
        if(empty($product)){
            $request->session()->flash('error','Product Not Found');
            return redirect()->route('product.list')->with('error','Product Not A Found');
        }
        $productImages      = Product_Image::where('product_id',$product->id)->get();
        $subCategories      = SubCategory::where('category_id',$product->category_id)->get();
        // return $subCategories;
        $data               =   [];
        if(empty($product)){
            $request->session()->flash('error','Records Not Found!');
            return redirect()->route('products.list');
        }
        $categories             =   Category::orderBy('name','desc')->get();
        $brands                 =   Brand::orderBy('name','desc')->get();
        $data['categories']     =   $categories;
        $data['brands']         =   $brands;
        $data['product']        =   $product;
        $data['subCategories']  =   $subCategories;
        $data['productImages']  =   $productImages;
        return view('admin.product.edit',$data);
    }
    public function update($id, Request $request){
        $product            =   Product::find($id);
        $rules= [
            'title'=>'required',
            'slug'=>'required|unique:products,slug,'.$product->id.',id',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products,sku,'.$product->id.',id',
            'track_qty'=>'required|in:Yes, NO',
            'category'=>'required|numeric',
            'is_featured'=>'required|in:Yes, NO',
        ];
        if(!empty( $request->track_qty ) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        };
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $product->title         =   $request->title;
            $product->slug          =   $request->slug;
            $product->description   =   $request->description;
            $product->price         =   $request->price;
            $product->compare_price =   $request->compare_price;
            $product->sku           =   $request->sku;
            $product->barcode       =   $request->barcode;
            $product->track_qty     =   $request->track_qty;
            $product->qty           =   $request->qty;
            $product->status        =   $request->status;
            $product->category_id   =   $request->category;
            $product->sub_category_id  =   $request->sub_category;
            $product->brand_id      =   $request->brand_id;
            $product->is_featured   =   $request->is_featured;
            $product->save();

            //Save Gallery Pics
            
            $request->session()->flash('success','Product Update  Successfully');
            return response()->json([
                'status'=> true,
                'success'=>"Product Update  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}
