<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\subCategory;
use App\Models\Brand;
use App\Models\Product;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug=null, $subCategorySlug=null){
        $categorySlected    =   '';
        $subCategorySelected=   '';
        $brandsArray        =   [];
       
        $categories = Category::orderBy('Name','ASC')->with('sub_category')->orderBy('id','DESC')
                        ->where('status',1)
                        ->get();
        $data['categories']= $categories;
        $brands = Brand::orderBy('Name','ASC')->orderBy('id','DESC')->where('status',1)->get();
        $data['brands']= $brands;
        $products =    Product::where('status',1);
        if(!empty($categorySlug)){
            $category   = Category::where('slug',$categorySlug)->first();
            $products   = $products->where('category_id',$category->id); 
            $categorySlected    =   $category->id;
        }
        if(!empty($subCategorySlug)){
            $subCategory   = subCategory::where('slug',$subCategorySlug)->first();
            $products   = $products->where('sub_category_id',$subCategory->id);
            $subCategorySelected    =   $subCategory->id;  
        }
        if( !empty($request->get('brands'))){
            $brandsArray    = explode(',',$request->get('brands'));
            $products   = $products->whereIn('brand_id',$brandsArray);
        }
        if($request->get('price_max') !='' && $request->get('price_min') !='' ){
            if($request->get('price_max') == 1000){
                $products   = $products->whereBetween('price',[intval($request->get('price_min')),1000000 ]);
            }else{
                $products   = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max')) ]);
            }
        }

        if($request->get('sort') !='' ){
            if($request->get('sort') == 'latest'){
                $products =    $products->orderBy('id','DESC');
            }else if($request->get('sort') == 'price_asc'){
                $products =    $products->orderBy('price','ASC');
            } else {
                $products =    $products->orderBy('price','DESC');
            }
        }else{
            $products =    $products->orderBy('id','DESC');
        };

        $products =    $products->paginate(6);
        // $products =    Product::orderBy('id','DESC')->where('status',1)->get();
        $data['products']= $products;
        $data['categorySlected']= $categorySlected;
        $data['subCategorySelected']= $subCategorySelected;
        $data['brandsArray']= $brandsArray;
        $data['priceMin']=intval($request->get('price_min'));
        $data['priceMax']=(intval($request->get('price_min')) == 0) ? 1000 : $request->get('price_max');
        $data['sort']= $request->get('sort');

        return view('front.shop.shop',$data);
    }
    public function product($slug){
        // echo $slug;
        $product    =   Product::where('slug',$slug)->with('productImage')->first();
        if( $product == null){
            abort(404);
        }
        // return $product;
        // dd($product);

        $relatedProducts=[];
        //fetch related Products
        if($product->related_products != ''){
            $relatedProductArray    =   explode(',',$product->related_products);
            $relatedProducts= Product::whereIn('id',$relatedProductArray)->with('productImage')->get();
        }
        // return $relatedProducts;
        $data['product']= $product;
        $data['relatedProducts']  =   $relatedProducts;
        return view('front.product.product',$data);
    }
}
