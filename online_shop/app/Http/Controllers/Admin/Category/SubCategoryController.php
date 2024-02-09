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
        $subCategories= SubCategory::select('sub_categories.*', 'categories.name as categoryName')
                        ->latest('sub_categories.id')
                        ->leftJoin('categories','categories.id','sub_categories.category_id');
        if (!empty($request->get('keyword'))){
            $subCategories= $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
            $subCategories= $subCategories->orWhere('categories.name','like','%'.$request->get('keyword').'%');
        }
        $subCategories =   $subCategories->paginate(15);
        return view('admin.sub_category.list',compact('subCategories'));
        
    }
    public function create(){
        $categories     =   Category::orderBy('id','desc')->get();
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
    public function edit($id, Request $request){
        $subCategory   =   SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Records Not Found!');
            return redirect()->route('sub-categories.list');
        }
        $categories     =   Category::orderBy('id','desc')->get();
        $data['categories']=$categories;
        $data['subCategory']=$subCategory;
        return view('admin.sub_category.edit',$data);
    }
    public function update($id,Request $request){
        $subCategory   =   SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Records Not Found!');
            return response()->json([
                'status'    => false,
                'notFound'    => true,
            ]);
            // return redirect()->route('sub-categories.list');
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            // 'slug'=>'required|unique:sub_categories',
            'slug'=>'required|unique:sub_categories,slug,'.$subCategory->id.',id',
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
            $subCategory->name =    $request->name;
            $subCategory->slug =    $request->slug;
            $subCategory->status =    $request->status;
            $subCategory->category_id =    $request->category;
            $subCategory->save();

            $request->session()->flash('success','Sub Category Update  Successfully');
            return response()->json([
                'status'=> true,
                'errors'=>"Sub Category Update  Successfully"
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function destory($id, Request $request){
        $subCategory   =   SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Sub Category Not  Found');
            return response()->json([
                'status'=> true,
                'message'=>'Sub Category Not Found'
            ]);
            // return redirect()->route('categories.index');
        }        
         $subCategory->delete();
         $request->session()->flash('success','Sub Category Delete  Successfully');
         return response()->json([
            'status'=> true,
            'message'=>'Sub Category Delete Successfully'
        ]);
    }
}