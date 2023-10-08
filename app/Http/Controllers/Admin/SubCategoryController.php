<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\Admin\SubCategoryResource;
use App\Models\Category;
use App\Models\Grade;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $grades = Grade::select('id', 'name')->get();
        $categories = Category::select('id','name')->get();
        // $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();

        return view('admin.subcategory.index', get_defined_vars());
    }

    public function get_subcategory_data(Request $request)
    {
        $category   = SubCategory::filter($request)->get();
        $categories = SubCategoryResource::collection($category);
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
    public function store(SubCategoryRequest $request)
    {
        $category = Category::find($request->category_id);
        $request['user_id'] = $category->user_id ?? auth()->user()->id;

        SubCategory::create($request->all());

        return response()->json(['status' => true, 'message' => 'created Successfully'], 200);
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
    public function update(SubCategoryRequest $request, SubCategory $subcategory)
    {
        $subcategory->update($request->all());

        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $category)
    {
        $category->delete();
        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }
}
