<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function productImage()
    {
        return $this->hasMany(Product_Image::class);
    }
    public function productRating()
    {
        return $this->hasMany(ProductRating::class)->where('status', 1);
    }
}
