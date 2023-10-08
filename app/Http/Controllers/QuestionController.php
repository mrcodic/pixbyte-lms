<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Category;
use App\Models\Grade;
use App\Models\Question;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {
        return view('questions.index');
    }
    public function get_question_data(Request $request){
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        $question = Question::where('user_id',get_instructor())->filter($request)->orderBy('id','desc')->skip($start)->take($limit)->get();
        $questions= QuestionResource::collection($question);

        return datatables($questions)->setOffset($start)->with(['recordsTotal'=>Question::where('user_id',get_instructor())->count(), "recordsFiltered" => Question::where('user_id',get_instructor())->filter($request)->count(),'start' => $start])->make(true);
    }
        public function gradeCategory($id){

                $grade=Grade::findOrFail($id);
                $category=$grade->categories()->where('user_id',get_instructor())->get();
               return response()->json(['status'=>true,'data'=>$category],200);
        }
        public function gradeSubCategory($id){
                $category=Category::findOrFail($id);
                $subcategory=$category->subCats;
               return response()->json(['status'=>true,'data'=>$subcategory],200);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $grades=Grade::select('name','id')->get();
        $categories=Category::where('user_id',get_instructor())->select('id','name')->get();
        return Inertia::render('Questions',
            [
                'grades'=>$grades,
                'categories'=>$categories
            ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {

        $input=$request->except('user_id','booleanValue');
        $input['user_id']=get_instructor();
        $input['question_status']=($request->booleanValue)?2:1;
        $question=Question::create($input);
        return redirect()->route('question.index')->with(['message'=>"created Success",'alert-type'=>'success']);
    }
    public function saveCat(Request $request){
        $subCat=null;
        $cat=null;
        if(!empty($request->category_id)){
            $subCat=SubCategory::create(['name'=>$request->name,'category_id'=>$request->category_id,'user_id'=>get_instructor()]);
        }else{
            $cat=Category::create(['name'=>$request->name,'user_id'=>get_instructor()]);
            $cat->grades()->syncWithoutDetaching($request->grade_id);
        }

        return response()->json(['status'=>true,'cat'=>$cat,'subCat'=>$subCat],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $grades=Grade::select('name','id')->get();
        $categories=Category::where('user_id',get_instructor())->select('id','name')->get();

        return Inertia::render('EditQuestion', [
            'questions' => $question,
            'grades'=>$grades,
            'categories'=>$categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $input = $request->except('booleanValue','pageTbl');
        $input['question_status']=($request->booleanValue)?2:1;
        $question->update($input);

        return response()->json(['status'=>true , 'url' => route('question.index')],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
         $question->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }
    public function delete_selected(Request $request){
        Question::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function uploadImageQues(Request $request){
       if($request->hasFile('upload')){
        $question = new Question();
           $question->id=0;
           $question->exists=true;
           $image=$question->addMediaFromRequest('upload')->toMediaCollection('images');
       }
       return response()->json([
           'uploaded'=>1,
           'url'=>str_replace("//storage","/storage",$image->getFullUrl()),
           'file_name'=>'upload'
       ]);
    }
}
