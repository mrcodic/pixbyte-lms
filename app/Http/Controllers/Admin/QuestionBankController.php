<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionBankRequest;
use App\Http\Resources\QuestionBankCreateResource;
use App\Http\Resources\QuestionBankQuestionsResource;
use App\Http\Resources\QuestionBankResource;
use App\Http\Resources\QuestionResource;
use App\Models\Category;
use App\Models\Grade;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\QuestionQuiezz;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.questionBank.index');
    }

    public function get_question_bank_data(Request $request){
        $question = QuestionBank::filter($request)->orderBy('id','desc')->get();
        $questions= QuestionBankResource::collection($question);
        return datatables($questions)->make(true);
    }

    public function get_question_in_bank_data(Request $request){
        $question = Question::whereIn('subcategory_id',$request->grade)->filter($request)->get();
        $questions= QuestionResource::collection($question);
        return datatables($questions)->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::select('name','id')->get();
        return  view('admin.questionBank.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionBankRequest $request)
    {
        // dd($request->questions);
        $questionBank= QuestionBank::create(['name'=>$request->name,'type'=>$request->type?1:0,'question_num'=>json_encode($request->question_num),'sub_cat_id'=>implode(',',$request->sub_cat_id),'grade'=>Null,'cat_id'=>$request->cat_id]);
        if($request->question_num && $request->question_num!="" && $request->type ){
            $subcats=SubCategory::with('questions')->whereIn('id',array_unique(array_values($request->sub_cat_id)))->get();
             $questionIds=[];
              foreach ($subcats as $sub){
                  foreach ($sub ->questions->take($request->question_num[$sub['id']])->shuffle() as $q){
                       array_push($questionIds,$q->id);
                  }
              }
             $questionBank->questions()->syncWithoutDetaching($questionIds);
        }else{
            $ids=explode(',',$request->questions);

            $questionBank->questions()->syncWithoutDetaching($ids);
        }

        return redirect()->route('admin.question-bank.list')->with(['message'=>"created Success",'alert-type'=>'success']);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $questionBank=QuestionBank::with('questions')->findOrFail($id);
        return view('admin.questionBank.edit',compact('questionBank'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $grades =Grade::select('name','id')->get();
        $categories=Category::select('name','id')->get();
        $questionBank=QuestionBank::with('questions')->findOrFail($id);
        $questions=$questionBank->questions->pluck('id')->toArray();
        $pageTbl = $request->pageTbl ?? null;
        $subCategory = DB::table('sub_categories')->select('id','name')->whereIn('id', explode(',', $questionBank->sub_cat_id))->get();
        // dd($subCategory);
        return view('admin.questionBank.edit',compact('questionBank','grades','questions','categories','subCategory' ,'pageTbl'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, QuestionBank $questionBank)
    public function update(QuestionBankRequest $request, QuestionBank $questionBank)
    {
        $questionBank->update(['name'=>$request->name,'type'=>$request->type?1:0,'question_num'=>json_encode($request->question_num),'sub_cat_id'=>implode(',',$request->sub_cat_id),'grade'=>Null,'cat_id'=>$request->cat_id]);
        if($request->question_num && $request->question_num!="" && $request->type ){
            $subcats=SubCategory::with('questions')->whereIn('id',array_unique($request->sub_cat_id))->get();
            $questionIds=[];
            foreach ($subcats as $sub){
                foreach ($sub ->questions->take($request->question_num[$sub['id']])->shuffle() as $q){
                    array_push($questionIds,$q->id);
                }
            }
            $questionBank->questions()->sync($questionIds);
        }else{
            $ids = explode(',',$request->questions);
            $questionBank->questions()->sync($ids);

          $quizIDs=$questionBank->quizzes;
          foreach ($quizIDs as $quiz){
           $quiz->questions()->syncWithOutDetaching($ids);
          }
        }
        return redirect()->route('admin.question-bank.list')->with(['message'=>"created Success",'alert-type'=>'success','pageTbl' => $request->pageTbl ?? null]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionBank $questionBank)
    {
        $questionBank->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }
    public function get_category_grade($id)
    {
        $grade=Grade::find($id);
        $categories=$grade->categories;
        return response()->json(['status'=>true,'data'=>$categories],200);

    }
    public function get_sub_category_grade($id)
    {

        $category=Category::find($id);
        $subcategories=$category->subCats;
        return response()->json(['status'=>true,'data'=>$subcategories],200);

    }
    public function get_questions(Request $request)
    {
        $ids=$request->ids;
        $questions=Question::whereIn('subcategory_id',$ids)->get();
        return response()->json(['status'=>true,'data'=>$questions],200);

    }
    public function delete_selected(Request $request){
        QuestionBank::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
}

