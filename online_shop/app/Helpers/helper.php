<?php

use App\Models\Category;
use App\Models\Product_Image;

function getCategories()
{
   return Category::orderBy('Name', 'ASC')
      ->with('sub_category')
      ->orderBy('id', 'DESC')
      ->where('status', 1)
      ->where('showHome', 'Yes')
      ->get();
}
function getProductImage($id)
{
   return  Product_Image::where('product_id', $id)->first();
}
