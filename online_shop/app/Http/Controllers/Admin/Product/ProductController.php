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
    public function validateRules($request){
        $rules= [
            'title'=>'required',
            'slug'=>'required|unique:products',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products',
            'track_qty'=>'required|in:Yes, NO',
            'category'=>'required|numeric',
            'is_featured'=>'required|in:Yes, NO',
        ];
        $messages = [
            'title.required' => 'The title field is Needable.',
            'slug.required' => 'The slug field is required.',
            'slug.unique' => 'The slug has already been taken.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'sku.required' => 'The SKU field is required.',
            'sku.unique' => 'The SKU has already been taken.',
            'track_qty.required' => 'The track quantity field is required.',
            'track_qty.in' => 'The track quantity must be either Yes or NO.',
            'category.required' => 'The category field is required.',
            'category.numeric' => 'The category must be a number.',
            'is_featured.required' => 'The is featured field is required.',
            'is_featured.in' => 'The is featured field must be either Yes or NO.',
        ];
        return ['rules' => $rules, 'messages' => $messages];
    }
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
        $validationData = $this->validateRules($request);
        $rules = $validationData['rules'];
        $messages = $validationData['messages'];
    
        if(!empty( $request->track_qty ) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        };
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->passes()){
            $product                =   new Product();
            $product->title         =   $request->title;
            $product->slug          =   $request->slug;
            $product->description   =   $request->description;
            $product->short_description   =   $request->short_description;
            $product->shipping_returns   =   $request->shipping_returns;
            $product->related_products   =   (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
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
        //fectch Image
        $productImages      = Product_Image::where('product_id',$product->id)->get();
        $subCategories      = SubCategory::where('category_id',$product->category_id)->get();

        $relatedProducts=[];
        //fetch related Products
        if($product->related_products != ''){
            $relatedProductArray    =   explode(',',$product->related_products);
            $relatedProducts= Product::whereIn('id',$relatedProductArray)->with('productImage')->get();
        }
        // return $relatedProducts;
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
        $data['relatedProducts']  =   $relatedProducts;
        return view('admin.product.edit',$data);
    }
    public function update($id, Request $request){
        $product            =   Product::find($id);
        $validationData = $this->validateRules($request);
        $rules= [
            'title'=>'required',
            'slug'=>'required|unique:products,slug,'.$product->id.',id',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products,sku,'.$product->id.',id',
            'track_qty'=>'required|in:Yes, NO',
            'category'=>'required|numeric',
            'is_featured'=>'required|in:Yes, NO',
        ];        
        $messages = $validationData['messages'];
        if(!empty( $request->track_qty ) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        };
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->passes()){
            $product->title         =   $request->title;
            $product->slug          =   $request->slug;
            $product->description   =   $request->description;
            $product->short_description   =   $request->short_description;
            $product->shipping_returns   =   $request->shipping_returns;
            $product->related_products   =   (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
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
    public function delete($id, Request $request){
        $product            =   Product::find($id);
        if(empty($product)){
            $request->session()->flash('error','Product Not Found');
            return response()->json([
                'status'=> false,
                'notFound'=>ture,
            ]);
        }
        $productImages     =    Product_Image::where('product_id',$id)->get();
         //Image Delete Form Folder
         if(!empty($productImages)){
            foreach($productImages as $productImage){
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }
             Product_Image::where('product_id',$id)->delete();
        }
        $product->delete();
        $request->session()->flash('success','Product Delete  Successfully');
        return response()->json([
            'status'=> true,
            'success'=>"Product Delete  Successfully"
         ]);
    }
    public function getProducts(Request $request){
        $temProduct=[];
        if($request->term !=""){
            $products = Product::where('title','like','%'.$request->term.'%')->get();
            if($products != null){
                foreach($products as $product){
                    $temProduct[]=array('id'=>$product->id, 'text'=>$product->title);
                }
            }
        }
        return response()->json([
            'tags'=>    $temProduct,
            'status'=>  true
        ]);
    }
}
