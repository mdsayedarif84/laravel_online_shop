<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title          =   fake()->unique()->name();
        $slug           =   Str::slug($title); 
        $subCategory    =   [16];
       $subCatRandKey   = array_rand($subCategory);

       $brands    =   [11];
       $brandRandKey   = array_rand($brands);
        return [
            'title'     =>   $title,
            'slug'      =>   $slug,
            'category_id'   =>   4,
            'sub_category_id'   =>   $subCategory[$subCatRandKey],
            'brand_id'          =>   $brands[$brandRandKey],
            'price'             =>   rand(10,1000),
            'sku'               =>   rand(1000, 1000000),
            'track_qty'         =>   'Yes',
            'qty'               =>   10,
            'is_featured'       =>   'Yes',
            'status'            =>   1,
        ];
    }
}
