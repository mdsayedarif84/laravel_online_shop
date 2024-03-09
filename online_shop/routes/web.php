<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Category\SubCategoryController;
use App\Http\Controllers\Admin\TempImage\TempImagesController;
use App\Http\Controllers\Admin\Brand\BrandController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\ProductSubCategoryController;
use App\Http\Controllers\Admin\Product\ProductImageController;
//FrontController
use App\Http\Controllers\Front\Home\FrontController;
use App\Http\Controllers\Front\Shop\ShopController;
use App\Http\Controllers\Front\Cart\CartController;
use App\Http\Controllers\Front\CustomerAuth\AuthController;

use Illuminate\Http\Request;



// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class,'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class,'product'])->name('front.product');
Route::post('/add-to-cart', [CartController::class,'addToCart'])->name('front.addToCart');
Route::get('/cart', [CartController::class,'cart'])->name('cart');
Route::post('/update-cart', [CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/remove-cart', [CartController::class,'removeItem'])->name('front.removeCart');

Route::get('/customer-login', [AuthController::class,'login'])->name('login');
Route::get('/register', [AuthController::class,'register'])->name('register');
Route::post('/process-register', [AuthController::class,'processRegister'])->name('process-register');


Route::group(['prefix'=>'admin'],function(){
    Route::group(['middleware' => 'admin.guest'],function(){
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });
    Route::group(['middleware' => 'admin.auth'],function(){
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');
        
        //Cateogry Routes
        Route::get('/category/list', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/categories/{id}/update', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destory'])->name('categories.destory');

        //sub cateogry route
        Route::get('/sub-categories/list', [SubCategoryController::class, 'index'])->name('sub-categories.list');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories/store', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-category/{id}/edit', [SubCategoryController::class, 'edit'])->name('sub-category.edit');
        Route::put('/sub-category/{id}/update', [SubCategoryController::class, 'update'])->name('sub-category.update');
        Route::delete('/sub-categories/{id}/destory', [SubCategoryController::class, 'destory'])->name('sub-categories.destory');

        //Brand 
        Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
        Route::get('/brand/list', [BrandController::class, 'index'])->name('brand.list');
        Route::get('/brand/{id}/edit', [BrandController::class, 'edit'])->name('brand.edit');
        Route::put('/brand/{id}/update', [BrandController::class, 'update'])->name('brand.update');
        Route::delete('/brand/{id}/destory', [BrandController::class, 'destory'])->name('brand.destory');

        //Prouduct
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
        Route::get('/products/list', [ProductController::class, 'list'])->name('products.list');
        Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{id}/update', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{id}/delete', [ProductController::class, 'delete'])->name('product.delete');
        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('get.products');

        Route::get('/product-subCategories', [ProductSubCategoryController::class, 'index'])->name('product-subCategories.index');
        Route::post('/product-image/update', [ProductImageController::class, 'update'])->name('product-image.update');
        Route::delete('/product-image/delete', [ProductImageController::class, 'destory'])->name('product-image.destory');

                //temp-images.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        Route::get('/getSlug',function(Request $request){
            $slug = '';
            if(!empty($request->title)){
               $slug = Str::slug($request->title);
            }
            return response()->json([
                'status'=> true,
                'slug'=> $slug,
            ]);
        })->name('getSlug');
    });
});