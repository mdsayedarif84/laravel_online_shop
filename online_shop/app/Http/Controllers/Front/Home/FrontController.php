<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class FrontController extends Controller
{
    public function index(){
        $products =    Product::where('is_featured','Yes')
            ->orderBy('id','DESC')
            ->where('status',1)
            ->take(8)
            ->get();
        $data['featuresProducts']= $products;
        $lastetProducts =    Product::orderBy('id','DESC')
            ->where('status',1)
            ->take(8)
            ->get();
        $data['lastetProducts']= $lastetProducts;
        return view('front.body.main-body',$data);
    }
}
