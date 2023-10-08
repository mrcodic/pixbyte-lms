<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\Models\Grade;
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
        $grades = Grade::select('id','name')->get();
        // $subcategories = SubCategory::select('id','name')->get();
        return view('admin.category.index',get_defined_vars());
    }

    public function get_category_data(Request $request)
    {

        $category   = Category::filter($request)->get();
        $categories = CategoryResource::collection($category);
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
    // public function store(CategoryRequest $request)
    public function store(CategoryRequest $request)
    {
        $input = $request->except('grade_ids');
        $category = Category::create($input);
        $category->grades()->syncWithoutDetaching(json_decode($request->grade_ids));
        // SubCategory::whereIn('id',$request->subcategory_ids)->update(['category_id'=>$category->id]);
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
        $category->update(['name' => $request->name]);

        $category->grades()->sync(json_decode($request->grade_ids));

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
    
}
