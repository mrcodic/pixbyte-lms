<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $grades=Grade::select('id','name')->get();
        $subcategories=SubCategory::select('id','name')->where('user_id',$instructor_id)->get();
        return  view('category.index',get_defined_vars());
    }
    public function get_category_data(Request $request){
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $category = Category::where('user_id',$instructor_id)->filter($request)->get();
        $categories= CategoryResource::collection($category);
        return datatables($categories)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $input=$request->except('grade_ids','subcategory_ids');
        $input['user_id']=get_instructor();
        $category=Category::create($input);
        $category->grades()->syncWithoutDetaching($request->grade_ids);
//        SubCategory::whereIn('id',$request->subcategory_ids)->update(['category_id'=>$category->id]);
        return response()->json(['status'=>true,'message'=>'created Successfully'],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $input=$request->except('grade_ids','subcategory_ids');
        $input['user_id']=get_instructor();
        $category->update($input);
        $category->grades()->sync($request->grade_ids);
        SubCategory::where('category_id',$category->id)->update(['category_id'=>Null]);
        SubCategory::whereIn('id',$request->subcategory_ids)->update(['category_id'=>$category->id]);
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function delete_selected(Request $request){
        Category::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
}
