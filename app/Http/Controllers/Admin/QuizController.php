<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizRequest;
use App\Http\Resources\QuestionBankResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\RoomShowResource;
use App\Http\Traits\PointsTrait;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Grade;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\QuestionQuestionBank;
use App\Models\Quiz;
use App\Models\QuizUser;
use App\Models\Result;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    use PointsTrait;


    public function index()
    {
        return view('admin.quiz.index');
    }

    public function get_quiz_data(Request $request){
        $question = Quiz::filter($request)->orderBy('id','desc')->get();
        $questions= QuizResource::collection($question);
        return datatables($questions)->make(true);
    }

    public function get_answer($que){
        $questions=Question::select('id','title','answers','answer_desc')->find($que);
        return response()->json(['status'=>true,'data'=>$questions],200);
    }

    public function create(){
        $grades = Grade::all();
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();
        return view('admin.quiz.create',get_defined_vars());
    }

    public function reset_quiz(Request $request){
            Result::where(['quiz_id'=>$request->quiz,'student_id'=>$request->student_id])->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }

    public function store(QuizRequest $request)
    {
        $input = $request->except(['questions','_token','totalRecord','checkbox_all_que','checkbox_all_question','user_id','select_all','question_bank_id','show_answer','retake','randomize_question','randomize_answer','questions', "questions_table_length"]);
        $input['question_bank_id']=implode(',',$request->question_bank_id);
        $input['user_id'] = $request->user_id;
        $input['show_answer']=($request->show_answer)?1:0;
        $input['retake']=($request->retake)?1:0;
        $input['randomize_question']=($request->randomize_question)?1:0;
        $input['randomize_answer']=($request->randomize_answer)?1:0;
        if($request->checkbox_all_question=="1"){
            $questionsIds= QuestionQuestionBank::whereIn('question_bank_id',$request->question_bank_id)->pluck('question_id')->toArray();
            $question=array_unique($questionsIds);
        }else{
            $question=explode(',',$request->questions);
        }
        $quiz=Quiz::create($input);

        $quiz->questions()->sync($question);
        if($request->classroom_id){
            $classrooms=ClassroomStudent::where('instructor_id', $request->user_id)->where('classroom_id',$request->classroom_id)->pluck('user_id')->toArray();
            $classroom=Classroom::find($request->classroom_id);

            foreach ($classrooms as $user) :
                foreach ($classroom->rooms as $roomFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classroom->id,'instructor_id'=>$classroom->user->id]);
                }
                foreach ($classroom->quizes as $quizFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classroom->id,'instructor_id'=>$classroom->user->id]);
                }
            endforeach;

        }elseif($request->room_id){
            $classrooms=Room::find($request->room_id)->classroom->pluck('id')->toArray();

            $classrooms=ClassroomStudent::where('instructor_id', $request->user_id)->whereIn('classroom_id',$classrooms)->pluck('user_id')->toArray();
        }

        if($classrooms){
            foreach ($classrooms as $class){
                QuizUser::create(['quizzes_id'=>$quiz->id,'user_id'=>$class]);
            }
        }
        return redirect()->route('admin.quiz.list');
    }

    public function edit(Request $request, $id){
        $grades = Grade::all();
        $quiz = Quiz::find($id);
        $questions = $quiz->questions->pluck('id')->toArray();
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();
        // dd($questions);
        $pageTbl = $request->pageTbl ?? null;

        return view('admin.quiz.edit',get_defined_vars());
    }

    public function update(QuizRequest $request,Quiz $quiz)
    {
        // dd($request->request);

        $input=$request->except(['room_id','questions','_token','select_all','question_bank_id','show_answer','retake','randomize_question','randomize_answer','questions', "checkbox_all_question",'totalRecord',"questions_table_length","pageTbl"]);
        $input['question_bank_id']=implode(',',$request->question_bank_id);
        $input['show_answer']=($request->show_answer)?1:0;
        $input['retake']=($request->retake)?1:0;
        $input['randomize_question']=($request->randomize_question)?1:0;
        $input['randomize_answer']=($request->randomize_answer)?1:0;
        $quiz->update($input);
        $quiz->questions()->sync(explode(',',$request->questions));
        if($request->classroom_id){
            $instructor_id = $request->user_id;
            $classrooms=ClassroomStudent::where('instructor_id',$instructor_id)->where('classroom_id',$request->classroom_id)->pluck('user_id')->toArray();

            $classroom = Classroom::find($request->classroom_id);
            foreach ($classrooms as $user) :
                foreach ($classroom->rooms as $roomFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classroom->id,'instructor_id'=>$classroom->user->id]);
                }
                foreach ($classroom->quizes as $quizFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classroom->id,'instructor_id'=>$classroom->user->id]);
                }
            endforeach;

        }elseif($request->room_id){
            // dd(Room::find($request->room_id));
            $classrooms=Room::find($request->room_id)->classroom->pluck('id')->toArray();
            $instructor_id = $request->user_id;
            $classrooms=ClassroomStudent::where('instructor_id',$instructor_id)->whereIn('classroom_id',$classrooms)->pluck('user_id')->toArray();
        }
        if($classrooms) {
            foreach ($classrooms as $class) {
                QuizUser::create(['quizzes_id' => $quiz->id, 'user_id' => $class]);
            }
        }
        return redirect()->route('admin.quiz.list')->with(['message'=>"Updated success",'alert-type'=>'success','pageTbl' => $request->pageTbl ?? null]);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }

    public function delete_selected(Request $request){
        Quiz::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }

    public function get_room($grade){
       $room=Room::whereHas('classroom',function ($q) use($grade){
           $q->whereHas('grade',function ($e) use($grade){
               $e->where('grade_id',$grade);
           });
       })->get();
        return response()->json(['status'=>true,'data'=>$room],200);

    }

    public function get_classroom($grade){
        $classroom=Classroom::whereHas('grade',function ($q) use($grade){
                $q->where('grade_id',$grade);
        })->get();
        return response()->json(['status'=>true,'data'=>$classroom],200);
    }

    public function get_questionBank(){
        $questions=QuestionBank::get();
        return response()->json(['status'=>true,'data'=>$questions],200);
    }

    public function get_questionBank_quiz(Request $request){
         $questionsIds= QuestionQuestionBank::whereIn('question_bank_id',$request->ids)->pluck('question_id')->toArray();
        $question = Question::whereIn('id',$questionsIds)->filter($request)->orderBy('id','desc')->get();
        $questions= QuestionResource::collection($question);
        return datatables($questions)->make(true);
    }

    public function get_questions_quiz(Request $request,$id){
           $quiz=Quiz::find($id);
           if($quiz->randomize_question){
               $questions=$quiz->questions()->inRandomOrder('1234')->paginate(10,['*'],'page',$request->page);
           }else{
               $questions=$quiz->questions()->paginate(10,['*'],'page',$request->page);

           }
        return response()->json(['status'=>true,'data'=>$questions],200);
    }


}
