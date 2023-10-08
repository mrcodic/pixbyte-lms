<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $categories=Category::select('id','name')->where('user_id',$instructor_id)->get();
        return  view('subcategory.index',get_defined_vars());
    }
    public function get_subcategory_data(Request $request){
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $subcategory = SubCategory::where('user_id',$instructor_id)->filter($request)->get();
        $subcategories= SubCategoryResource::collection($subcategory);
        return datatables($subcategories)->make(true);
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
    public function store(SubCategoryRequest $request)
    {
        $input=$request->except('user_id');
        $input['user_id']=get_instructor();
        $data= SubCategory::create($input);
        return response()->json(['status'=>true,'message'=>'created Successfully','data'=>$data],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategory $subcategory)
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
        $input=$request->except('user_id');
        $input['user_id']=get_instructor();
        $subcategory->update($input);
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function delete_selected(Request $request){
        SubCategory::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
}
