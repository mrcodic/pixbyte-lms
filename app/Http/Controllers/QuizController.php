<?php

namespace App\Http\Controllers;

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
use App\Models\PointDetails;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\QuestionQuestionBank;
use App\Models\Quiz;
use App\Models\QuizUser;
use App\Models\Result;
use App\Models\Room;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    use PointsTrait;

    public function __construct()
    {
        $this->middleware('ShowAnswer')->only('show','get_assignment');
        $this->middleware('permission:read-quizes|create-quizes|update-quizes|delete-quizes', ['only' => ['index']]);
        $this->middleware('permission:create-quizes', ['only' => ['create','store']]);
        $this->middleware('permission:update-quizes', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-quizes', ['only' => ['destroy']]);

    }
 public function index(){
  return view('quiz.index');
 }
 public function get_assignment($id){
     $assignment=Quiz::find($id);
     $room=Room::with('lessons')->findOrFail($assignment->room_id);
     $rooms=RoomShowResource::make($room)->resolve();
     $assignments=Quiz::with('result')->select('id','title')->where('type',1)->where('room_id',$room->id)->get();
     $quizes=Quiz::with('result')->select('id','title')->where('type',3)->where('room_id',$room->id)->get();
     return view('quiz.assignment',compact('assignment','rooms','assignments','quizes'));

 }
    public function get_quiz_data(Request $request){
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $question = Quiz::where('user_id',$instructor_id)->filter($request)->orderBy('id','desc')->skip($start)->take($limit)->get();
        $questions= QuizResource::collection($question);
        return datatables($questions)->setOffset($start)->with(['recordsTotal'=>Quiz::where('user_id',$instructor_id)->count()
            , "recordsFiltered" => Quiz::where('user_id',$instructor_id)->filter($request)->count(),'start' => $start])->make(true);
    }
 public function get_answer($que){
     $questions=Question::select('id','title','answers','answer_desc')->find($que);
     return response()->json(['status'=>true,'data'=>$questions],200);
 }
 public function create(){
     $grades=Grade::all();
     return view('quiz.create',get_defined_vars());
 }
 public function reset_quiz(Request $request){
        if($request->retake){
            $retake=Result::where(['quiz_id'=>$request->quiz,'student_id'=>$request->student_id])->first();
            Result::where(['quiz_id'=>$request->quiz,'student_id'=>$request->student_id])->update(['points'=>0,'total_correct_answer'=>0,'num_retake'=>(int)$retake->num_retake+1]);

        }else{
            $points=PointDetails::where(['user_id'=>$request->student_id,'model_id'=>$request->quiz])->first();
            if($points){
                $student=Student::where('user_id',$request->student_id)->first();
                $student->update(['exp'=>(int)$student->exp-(int)$points->value]);
            }

            Result::where(['quiz_id'=>$request->quiz,'student_id'=>$request->student_id])->delete();
            PointDetails::where(['user_id'=>$request->student_id,'model_id'=>$request->quiz])->delete();

        }

        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

 }
 public function store(QuizRequest $request){
     $input=$request->except(['questions','_token','totalRecord','checkbox_all_que','checkbox_all_question','user_id','select_all','question_bank_id','show_answer','retake','randomize_question','randomize_answer','questions','classroom_id', "questions_table_length"]);
     $input['question_bank_id']=implode(',',$request->question_bank_id);
     $input['classroom_id']=$request->classroom_id ?implode(',',$request->classroom_id):Null;
     $input['user_id']=get_instructor();
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
     if($request->classroom_id) {
         $classroom = Classroom::find($request->classroom_id);

         foreach ($classroom as $class) {
             $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();

             $classroomStudent = ClassroomStudent::where('instructor_id', $instructor_id)->where('classroom_id', $class->id)->get();


             foreach ($classroomStudent as $user) :
                 QuizUser::updateOrCreate(['quizzes_id' => $quiz->id, 'user_id' => $user->user_id]);
                 if ($user->classroom_id == $class->id) {
                     foreach ($class->rooms as $roomFromClass) {
                         Attendance::firstOrCreate(['attendance_id' => $roomFromClass->id, 'attendance_type' => 'room', 'student_id' => $user->user_id, 'classroom_id' => $class->id, 'instructor_id' => $class->user->id]);
                     }
                     foreach ($class->quizes as $quizFromClass) {
                         Attendance::firstOrCreate(['attendance_id' => $quizFromClass->id, 'attendance_type' => 'quiz', 'student_id' => $user->user_id, 'classroom_id' => $class->id, 'instructor_id' => $class->user->id]);
                     }

                     activity()
                         ->performedOn($quiz)
                         ->causedBy(auth()->id())
                         ->performedOn($class)
                         ->withProperties(['attributes' => ["id" => $quiz->id, 'title' => $quiz->title, 'user.name' => auth()->user()->name, 'classroom' => $class->id]])
                         ->log('added quiz');
                 }

             endforeach;

         }

     }elseif($request->room_id){
         $classrooms=Room::find($request->room_id)->classroom->pluck('id')->toArray();
         $classrooms=ClassroomStudent::where('instructor_id',get_instructor())->whereIn('classroom_id',$classrooms)->pluck('user_id')->toArray();
         foreach ($classrooms as $user){
           QuizUser::updateOrCreate(['quizzes_id' => $quiz->id, 'user_id' => $user]);
         }

     }
    return redirect()->route('quiz.index');
 }
 public function edit(Request $request, $id){
     $grades=Grade::all();
     $quiz=Quiz::find($id);
     $questions=$quiz->questions->pluck('id')->toArray();

     $pageTbl = $request->pageTbl ?? null;

     return view('quiz.edit',get_defined_vars());
 }
    public function update(QuizRequest $request,Quiz $quiz){
        // dd($request->request);
        $input=$request->except(['questions','classroom_id','_token','select_all','question_bank_id','show_answer','retake','randomize_question','randomize_answer','questions', "questions_table_length","pageTbl"]);
        $input['question_bank_id']=implode(',',$request->question_bank_id);
        $input['show_answer']=($request->show_answer)?1:0;
        $input['classroom_id']=$request->classroom_id?implode(',',$request->classroom_id):'';
        $input['retake']=($request->retake)?1:0;
        $input['randomize_question']=($request->randomize_question)?1:0;
        $input['randomize_answer']=($request->randomize_answer)?1:0;
        $quiz->update($input);
        $quiz->questions()->sync(explode(',',$request->questions));
        if($request->classroom_id){
            $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();

            $classroom = Classroom::find($request->classroom_id);

            foreach ($classroom as $class) {
                $classroomStudent=ClassroomStudent::where('instructor_id',$instructor_id)->where('classroom_id',$class->id)->get();
                foreach ($classroomStudent as $user) :
                    QuizUser::updateOrCreate(['quizzes_id' => $quiz->id, 'user_id' => $user->user_id]);
                    if($user->classroom_id==$class->id){
                        foreach ( $class->rooms as $roomFromClass) {
                            Attendance::firstOrCreate(['attendance_id' => $roomFromClass->id, 'attendance_type' => 'room', 'student_id' => $user->user_id, 'classroom_id' => $class->id, 'instructor_id' => $class->user->id]);
                        }
                        foreach ($class->quizes as $quizFromClass) {
                            Attendance::firstOrCreate(['attendance_id' => $quizFromClass->id, 'attendance_type' => 'quiz', 'student_id' => $user->user_id, 'classroom_id' => $class->id, 'instructor_id' => $class->user->id]);
                        }

                    }

                endforeach;

            }


        }elseif($request->room_id){
            $classrooms=Room::find($request->room_id)->classroom->pluck('id')->toArray();
            $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
            $classrooms=ClassroomStudent::where('instructor_id',$instructor_id)->whereIn('classroom_id',$classrooms)->get();

            foreach ($classrooms as $user){
                QuizUser::updateOrCreate(['quizzes_id' => $quiz->id, 'user_id' => $user->user_id]);
            }
        }

        return redirect()->route('quiz.index')->with(['message'=>"Updated success",'alert-type'=>'success','pageTbl' => $request->pageTbl ?? null]);
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
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
       $room=Room::where('user_id',$instructor_id)->whereHas('classroom',function ($q) use($grade){
           $q->whereHas('grade',function ($e) use($grade){
               $e->where('grade_id',$grade);
           });
       })->get();
        return response()->json(['status'=>true,'data'=>$room],200);

    }
    public function get_classroom($grade){
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $classroom=Classroom::where('instructor_id',$instructor_id)->whereHas('grade',function ($q) use($grade){
                $q->where('grade_id',$grade);
        })->get();
        return response()->json(['status'=>true,'data'=>$classroom],200);
    }
    public function get_questionBank(){
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $questions=QuestionBank::where('user_id',$instructor_id)->get();
        return response()->json(['status'=>true,'data'=>$questions],200);
    }
    public function get_questionBank_quiz(Request $request){
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $questionsIds= QuestionQuestionBank::whereIn('question_bank_id',$request->ids)->pluck('question_id')->toArray();
        $question = Question::where('user_id',$instructor_id)->whereIn('id',$questionsIds)->filter($request)->orderBy('id','desc')->skip($start)->take($limit)->get();
        $questions= QuestionResource::collection($question);
        return datatables($questions)->setOffset($start)->with(['recordsTotal'=>Question::where('user_id',$instructor_id)->whereIn('id',$questionsIds)->count()
            , "recordsFiltered" => Question::where('user_id',$instructor_id)->whereIn('id',$questionsIds)->filter($request)->count(),'start' => $start])->make(true);
    }
    public function show($id){
        $quiz=Quiz::find($id);
        if($quiz->type==1){
        $room=Room::with('lessons')->findOrFail($quiz->room_id);
        $rooms=RoomShowResource::make($room)->resolve();
            $quizzes=Quiz::with('result')->select('id','title')->where('type',1)->where('room_id',$room->id)->get();
            $assignments=Quiz::with('result')->select('id','title')->where('type',3)->where('room_id',$room->id)->get();

            return view('quiz.exam',compact('quiz','rooms','quizzes','assignments'));

        }else{
            $rooms=[];
        return view('quiz.exam',compact('quiz','rooms'));
        }
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
    public function save_quiz(Request $request){
        if($request->questions){
            $question_ids=array_keys($request->questions);
            $questions=Question::whereIn('id',$question_ids)->get();
            $point=0;
            foreach ($questions as $que){
                if($que->type==1){
                    if($que->answers[$request->questions[$que->id][0]]['correct']){
                        $point++;
                    }
                }else{
                    $correct=0;
                    foreach ($request->questions[$que->id] as $key=> $qq){
                        if($que->answers[$request->questions[$que->id][$key]]['correct']){
                            $correct++;
                        }
                    }
                    $correctAnswer=0;
                    foreach ($que->answers as $ans){
                        if($ans['correct']){
                            $correctAnswer++;
                        }
                    }
                    if($correctAnswer==$correct){
                        $point++;
                    }
                }

            }
        }else{
            $point=0;
        }

        $quiz=Quiz::find($request->quiz_id);

        $quiz->quizUser()->update(['status'=>1]);
        $score=checkScore($point,count($quiz->questions));
        $result=Result::updateOrCreate(['student_id'=>auth()->id(),'quiz_id'=>$request->quiz_id],['student_id'=>auth()->id(),'quiz_id'=>$request->quiz_id,'total_correct_answer'=>$point,'points'=>$request->questions??[]]);

        $textInstructor='<i><bold>'. $quiz->title.'</bold></i>'."has been finished "."<i><bold>".auth()->user()->name."</bold></i>";
        $textInstructorPhone='('. $quiz->title.')'."has been finished "."(".auth()->user()->name.")";
        $instructor=User::find($quiz->user_id);

        \App\Jobs\NotificationJop::dispatch($quiz,'',$textInstructor,$instructor,$redirect=null,$textInstructorPhone,'1');//instructor
        $retake=false;
        if( (int)$score<(int)$quiz->score){
            $data=false;
            if($quiz->retake==1){
                $retake=true;
            }
            if($quiz->type==3){
                $pointType='point_assignment';
                 $this->setPoint(auth()->user(),$pointType, Quiz::class ,$quiz->id);
                 $text= "You have finished the assignment successfully ✅"."<i><bold>".$quiz->title."</bold></i>";
                 $textPhone="You have finished the assignment successfully ✅ ( ".$quiz->title." )";
                 \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$text,auth()->user(),$redirect=null,$textPhone,'2');//instructor
                 $textParent= "Your student finished the assignment successfully ✅"."(".$quiz->title.")";
                 $textParentPhone="Your student finished the assignment successfully ✅"."(".$quiz->title.")";
                 \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$textParent,auth()->user()->parent,$redirect=null,$textParentPhone,'1');//instructor

            }else{

                $text= "You failed the exam"."<i><bold>".$quiz->title."</bold></i>" .' with mark '.$score.'%';
                $textPhone="You failed the exam"."(".$quiz->title.")" .' with mark '.$score.'%';
                \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$text,auth()->user(),$redirect=null,$textPhone,'1');//instructor

                $textParent= "Your Student failed the exam"."<i><bold>".$quiz->title."</bold></i>" .' with mark '.$score.'%';
                $textParentPhone="Your Student failed the exam"."(".$quiz->title.")" .' with mark '.$score.'%';
                \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$textParent,auth()->user()->parent,$redirect=null,$textParentPhone,'1');//instructor

            }
        }else{
            $data=true;
                 if($quiz->type==1 || $quiz->type==2){
                    $text= "You Passed the exam"."<i><bold>".$quiz->title."</bold></i>" .' with mark '.round(($score/$quiz->score)*100,2);
                    $textPhone="You Passed the exam"."(".$quiz->title.")" .' with mark '.round(($score/$quiz->score)*100,2);
                    \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$text,auth()->user(),$redirect=null,$textPhone,'2');//instructor
                    $textParent= "Your Student passed the exam"."<i><bold>".$quiz->title."</bold></i>" .' with mark '.$score.'%';
                    $textParentPhone="Your Student passed the exam"."(".$quiz->title.")" .' with mark '.$score.'%';
                    \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$textParent,auth()->user()->parent,$redirect=null,$textParentPhone,'1');//instructor

                 }else{
                    $text= "You have finished the assignment successfully"."<i><bold>".$quiz->title."</bold></i>";
                    $textPhone="You have finished the assignment successfully ( ".$quiz->title." )";
                    \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$text,auth()->user(),$redirect=null,$textPhone,'2');//instructor
                    $textParent= "Your student finished the assignment successfully"."(".$quiz->title.")";
                    $textParentPhone="Your student finished the assignment successfully"."(".$quiz->title.")";
                    \App\Jobs\NotificationJop::dispatch($quiz,$quiz,$textParent,auth()->user()->parent,$redirect=null,$textParentPhone,'1');//instructor
                 }

            if(   $quiz->type == 1 ){
                $pointType = 'point_passed_quiz';
            }elseif($quiz->type==2){
                $pointType='pass_point_exam';
            }else{
                $pointType='point_assignment';
            }
            $this->setPoint(auth()->user(),$pointType, Quiz::class ,$quiz->id);

           ($score==100 && $quiz->type== 2) ? $this->setPoint(auth()->user(),'full_mark_point_exam', Quiz::class ,$quiz->id) :null;
        }

        return response()->json(['status'=>true,'data'=>['result'=>$data,'score'=>$score,'retake'=>$retake]]);
 }
 public function show_answer_quiz($id){
     $quiz=Quiz::with('questions')->find($id);
     $room=Room::with('lessons')->findOrFail($quiz->room_id);

     if($quiz->type==1 ||$quiz->type==3 ){
         $room=Room::with('lessons')->findOrFail($quiz->room_id);
         $rooms=RoomShowResource::make($room)->resolve();
         $quizzes=Quiz::with('result')->select('id','title')->where('type',1)->where('room_id',$room->id)->get();
         $assignments=Quiz::with('result')->select('id','title')->where('type',3)->where('room_id',$room->id)->get();

         return view('quiz.show_answer',compact('quiz','rooms','quizzes','assignments'));

     }else{
         $rooms=[];
         $assignments=Quiz::with('result')->select('id','title')->where('type',3)->where('room_id',$room->id)->get();
         return view('quiz.show_answer',compact('quiz','rooms'));
     }
 }

    public function student_answer_quiz($id){
        $quiz=Quiz::with('questions')->find($id);
        if(!$quiz->result){
            abort(404);
        }else{
            if($quiz->type==1 ||$quiz->type==3){
                $room=Room::with('lessons')->findOrFail($quiz->room_id);
                $rooms=RoomShowResource::make($room)->resolve();
                $quizzes=Quiz::with('result')->select('id','title')->where('type',1)->where('room_id',$room->id)->get();
                $assignments=Quiz::with('result')->select('id','title')->where('type',3)->where('room_id',$room->id)->get();

                return view('quiz.answer_student',compact('quiz','rooms','quizzes','assignments'));

            }else{
                $rooms=[];
                return view('quiz.answer_student',compact('quiz','rooms'));
            }
        }

    }
}
