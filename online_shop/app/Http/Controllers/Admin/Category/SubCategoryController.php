<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Validator;


class SubCategoryController extends Controller
{
    public function index(Request $request){
        $subCategories= SubCategory::latest('id');
        if (!empty($request->get('keyword'))){
            $subCategories= $subCategories->where('name','like','%'.$request->get('keyword').'%');
        }
        $subCategories =   $subCategories->paginate(15);
        return view('admin.sub_category.list',compact('subCategories'));
        
    }
    public function create(){
        $categories     =   Category::orderBy('name','ASC')->get();
        $data['categories'] =   $categories;
        return view('admin.sub_category.create',$data);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories',
            'category'=>'required',
            'status'=>'required',
        ],
        [
            'name.required' => 'Enter Your Name!',
            'slug.required' => 'Input Correct slug & unique!',
            'category.required' => 'Select Category!',
            'status.required' => 'Select Status !',
        ]);
        if($validator->passes()){
            $subCategory    =   new SubCategory();
            $subCategory->name =    $request->name;
            $subCategory->slug =    $request->slug;
            $subCategory->status =    $request->status;
            $subCategory->category_id =    $request->category;
            $subCategory->save();

            $request->session()->flash('success','Sub Category Added  Successfully');
            return response()->json([
                'status'=> true,
                'errors'=>"Sub Category Added  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}