<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\Admin\QuestionResource;
use App\Models\Category;
use App\Models\Grade;
use App\Models\Question;
use App\Models\SubCategory;
use App\Models\User;
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
        return view('admin.questions.index');
    }

    public function get_question_data(Request $request)
    {
        $start = request('start');
        $limit = ((request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10);

        $request['name'] = $request->search['value'];

        $question = Question::filter($request)->orderBy('id','desc');
        $recordsFiltered = $question->count();
        $questions= QuestionResource::collection($question->skip($start)->take($limit)->get());
        
        return datatables($questions)->setOffset($start)
        ->with([
            'recordsTotal'    => Question::count(),
            'recordsFiltered' => $recordsFiltered,
            'start' => $start
        ])->make(true);
    }

    public function gradeCategory($id){
            $grade=Grade::findOrFail($id);
            $category=$grade->categories;
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
        $grades      = Grade::select('name','id')->get();
        $categories  = Category::select('id','name')->get();
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();
        return view('admin.questions.create', get_defined_vars());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $input = $request->except('booleanValue');
        // $input['question_status']=($request->booleanValue)?2:1;

        Question::create($input);

        return redirect()->route('admin.question.list')->with(['message'=>"created Success",'alert-type'=>'success']);
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
        $grades      = Grade::select('name','id')->get();
        $categories  = Category::select('id','name')->get();
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();
        return view('admin.questions.edit', get_defined_vars());
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
        $input = $request->except('pageTbl');
        $question->update($input);

        return redirect()->route('admin.question.list')->with(['message'=>"update Success",'alert-type'=>'success']);
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
