<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\subCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySlected    =   '';
        $subCategorySelected =   '';
        $brandsArray        =   [];

        $categories = Category::orderBy('Name', 'ASC')->with('sub_category')->orderBy('id', 'DESC')
            ->where('status', 1)
            ->get();
        $data['categories'] = $categories;
        $brands = Brand::orderBy('Name', 'ASC')->orderBy('id', 'DESC')->where('status', 1)->get();
        $data['brands'] = $brands;
        $products =    Product::where('status', 1);
        if (!empty($categorySlug)) {
            $category   = Category::where('slug', $categorySlug)->first();
            $products   = $products->where('category_id', $category->id);
            $categorySlected    =   $category->id;
        }
        if (!empty($subCategorySlug)) {
            $subCategory   = subCategory::where('slug', $subCategorySlug)->first();
            $products   = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected    =   $subCategory->id;
        }
        if (!empty($request->get('brands'))) {
            $brandsArray    = explode(',', $request->get('brands'));
            $products   = $products->whereIn('brand_id', $brandsArray);
        }
        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000) {
                $products   = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            } else {
                $products   = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }
        //front page header search button code
        if (!empty($request->get('search'))) {
            $products   = $products->where('title', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                $products =    $products->orderBy('id', 'DESC');
            } else if ($request->get('sort') == 'price_asc') {
                $products =    $products->orderBy('price', 'ASC');
            } else {
                $products =    $products->orderBy('price', 'DESC');
            }
        } else {
            $products =    $products->orderBy('id', 'DESC');
        };

        $products =    $products->paginate(6);
        // $products =    Product::orderBy('id','DESC')->where('status',1)->get();
        $data['products'] = $products;
        $data['categorySlected'] = $categorySlected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] = $brandsArray;
        $data['priceMin'] = intval($request->get('price_min'));
        $data['priceMax'] = (intval($request->get('price_min')) == 0) ? 1000 : $request->get('price_max');
        $data['sort'] = $request->get('sort');

        // return $data;
        return view('front.shop.shop', $data);
    }
    public function product($slug)
    {
        // echo $slug;
        $product    =   Product::where('slug', $slug)
            ->withCount('productRating')
            ->withSum('productRating', 'rating')
            ->with(['productImage', 'productRating'])->first();
        if ($product == null) {
            abort(404);
        }
        $relatedProducts = [];
        //fetch related Products
        if ($product->related_products != '') {
            $relatedProductArray    =   explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $relatedProductArray)->where('status', 1)->with('productImage')->get();
        }
        // return $relatedProducts;
        $data['product'] = $product;
        $data['relatedProducts']  =   $relatedProducts;
        //rating calculatin
        $avgRating       =   '0.00';
        $avgRatingPer       =   0;
        if ($product->product_rating_count > 0) {
            $avgRating      =   number_format(($product->product_rating_sum_rating / $product->product_rating_count), 2);
            $avgRatingPer   =   ($avgRating * 100) / 5;
        }
        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;
        return view('front.product.product', $data);
    }
    public function productRating($id, Request $request)
    {
        $validator  =   Validator::make($request->all(), [
            'name'      => 'required|min:5',
            'email'     => 'required|email',
            'comment'   => 'required',
            'rating'    => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'    =>  false,
                'errors'     =>  $validator->errors()
            ]);
        }
        $count  =   ProductRating::where('email', $request->email)->count();
        if ($count > 0) {
            session()->flash('error', 'You Already Rated');
            return response()->json([
                'status'    =>  true,
            ]);
        }
        $productRating              =   new ProductRating();
        $productRating->product_id  =   $id;
        $productRating->username    =   $request->name;
        $productRating->email       =   $request->email;
        $productRating->comment     =   $request->comment;
        $productRating->rating      =   $request->rating;
        $productRating->status      =   0;
        $productRating->save();
        $message                =   'Thank Your For Rating Us!!';
        session()->flash('success', $message);
        return response()->json([
            'status'    =>  true,
            'message'     =>  $message
        ]);
    }
}
