<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentQuizResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentRoomResource;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\RequestChange;
use App\Models\Room;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-students|create-students|update-students|delete-students', ['only' => ['index','show','export']]);
        $this->middleware('permission:create-students', ['only' => ['create','store']]);
        $this->middleware('permission:update-students', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-students', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
        $grades=Grade::select('id','name')->get();
        $classrooms=Classroom::where('instructor_id',get_instructor())->select('id','title')->get();
        return view('students.index',get_defined_vars());
    }
    public function get_student_data(Request $request){
        $request['instructor']= get_instructor();
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;

        $students = User::filter($request)->whereIn('type',[3,4])->whereHas('studentOnlyInstructor')->orderBy('id','desc')->skip($start)->take($limit)->get();
        $students= StudentResource::collection($students);
        return datatables($students)
            ->setOffset($start)->with(['recordsTotal'=>User::whereIn('type',[3,4])->whereHas('studentOnlyInstructor')->count(), "recordsFiltered" => User::filter($request)->whereIn('type',[3,4])->whereHas('studentOnlyInstructor')->count(),'start' => $start])->
            make(true);

    }
    public function get_room_student_data(Request $request){

        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;

        $rooms = Room::whereHas('lessons')->studentFilter($request)->orderBy('created_at','desc')->skip($start)->take($limit)->get();
        $rooms= StudentRoomResource::collection($rooms);
        return datatables($rooms)->setOffset($start)->with(['recordsTotal'=>Room::whereHas('lessons')->count(), "recordsFiltered" => Room::whereHas('lessons')->studentFilter($request)->count(),'start' => $start])->
        make(true);
    }
    public function get_quiz_student_data(Request $request){
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;

        $rooms = Quiz::studentFilter($request)->orderBy('created_at','desc')->skip($start)->take($limit)->get();
        $rooms= StudentQuizResource::collection($rooms);
    return datatables($rooms)->setOffset($start)->with(['recordsTotal'=>Quiz::studentFilter($request)->count(), "recordsFiltered" => Quiz::studentFilter($request)->count(),'start' => $start])->
        make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $grades=Grade::all();
       return view('students.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        $data=$request->except('name_id','profile_image','password');
        $name_id = IdGenerator::create(new User, 'name_id', 5, 'mvs');
        $data['name_id']=$name_id;
        $data['password']=Hash::make($request->password);
        if ($request->file('profile_image')) {
            $file       = $request->file('profile_image');
            $fileName   = $name_id.date('YmdHi').$file->getClientOriginalName();
            $img = Image::make($file)->resize(108,108);
            $img->save('uploads/profile_images/'.$fileName, 60);
            $data['profile_image'] = $fileName;
        }
        $user=User::create($data);
        $mac=substr(shell_exec('getmac'), 238,17);
        Student::create([
            "ip"=>$mac,
            "user_id"=>$user->id,
            "phone"=>$request->phone
        ]);
        Attendance::create(['student_id'=>$user->id]);

        DB::table('role_users')->insert(['user_id'=>$user->id,'role_id'=>'3']);
        return redirect()->route('student.index')->with(['message'=>"created Success",'alert-type'=>'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student=User::with('student')->findOrFail($id);
        $grades=Grade::select('id','name')->get();

        return view('students.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, $id)
    {
        $student=User::findOrFail($id);
        $data=$request->except('profile_image','password');
       if($request->password){
        $data['password']=Hash::make($request->password);
       }
       $student->update($data);
        if ($request->file('profile_image')) {
            Storage::delete('uploads/profile_images/'. $student->name_id);
            $file       = $request->file('profile_image');
            $fileName   = $student->name_id.date('YmdHi').$file->getClientOriginalName();
            $img = Image::make($file)->resize(108,108);
            $img->save('uploads/profile_images/'.$fileName, 60);
            $data['profile_image'] = $fileName;
        }
        if($request->phone){
            $student->student->update(['phone'=>$request->phone]);
        }
        return redirect()->route('student.index')->with(['message'=>"update Success",'alert-type'=>'success']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::findOrFail($id);
        $user->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function delete_selected(Request $request){
        User::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function get_request_student($id){
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
        $studentRequest=RequestChange::where("student_id",$id)->with('currentClass:title,id','anotherClass:title,id')->where('instructor_id',get_instructor())->get();
        return response()->json(['status'=>true,'data'=>$studentRequest],200);

    }
    public function replay_request(Request $request){
        $requestChange=RequestChange::findOrFail($request->id);
        $currentClass=Classroom::find($requestChange->current_class);
        $class=Classroom::find($requestChange->another_class);
        $user=User::find($request->student_id);
        if($request->type=='accept'){
            RequestChange::where('student_id',$request->student_id)->where('status',1)->update(['status'=>0]);
            $requestChange->update(['status'=>1]);
            $text=' Your request to change From the <bold><i>'.$currentClass->title.'</i></bold> to the <bold><i>'.$class->title.'</i></bold> is approved. ';
            $textPhone=' Your request to change From the '.$currentClass->title.' to the '.$class->title.' is approved. ';
            \App\Jobs\NotificationJop::dispatch($class,null,$text,$user,$redirect=null,$textPhone,'2');//students

            $textParent=' Your Student classroom  has been changed from  <bold><i>'.$currentClass->title.'</i></bold> to  <bold><i>'.$class->title.'</i></bold>';
            $textParentPhone=' Your Student classroom  has been changed from '.$currentClass->title.' to '.$class->title.'';
            \App\Jobs\NotificationJop::dispatch($class,null,$textParent,$user->parent,$redirect=null,$textParentPhone,'2');//students

        }else{
            RequestChange::where('student_id',$request->student_id)->where('status',1)->update(['status'=>0]);
            $requestChange->update(['status'=>1]);
            $text=' Your request to change From the <bold><i>'.$currentClass->title.'</i></bold> to the <bold><i>'.$class->title.'</i></bold> is refused. ';
            $textPhone=' Your request to change From the '.$currentClass->title.' to the'.$class->title.' is refused. ';
            \App\Jobs\NotificationJop::dispatch($class,null,$text,$user,$redirect=null,$textPhone,'2');//students
            $requestChange->update(['status'=>2]);
        }
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
        ClassroomStudent::where(['user_id'=>$requestChange->student_id,'instructor_id'=>get_instructor(),'classroom_id'=>$requestChange->current_class])->update(['classroom_id'=>$requestChange->another_class]);
        Attendance::where('student_id',$requestChange->student_id)->where('instructor_id',get_instructor())->update([
            "student_id"=>$requestChange->student_id,
            "instructor_id"=>get_instructor(),
            "classroom_id"=>$requestChange->another_class
        ]);
        return response()->json(['status'=>true],200);

    }

    public function get_classes_student($id){
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
       $classIds=ClassroomStudent::where('user_id',$id)->where('instructor_id',get_instructor())->pluck('classroom_id')->toArray();
           $classes=Classroom::whereIn('id',$classIds)->select('id','title')->get();
        return response()->json(['status'=>true,'data'=>$classes],200);

    }
    public function get_another_classes_student($id,$student_id){
        $class=Classroom::where('id',$id)->first();
        $user=User::findOrFail($student_id);
        $classessRequset=Classroom::whereHas('grade',function ($q) use($user){
            $q->where('grade_id',$user->grade->id);
        })->where('id','!=',$class->id)->where('subject_id',$class->subject_id)->where('instructor_id',$class->instructor_id)->select('id','title')->get();
        return response()->json(['status'=>true,'data'=>$classessRequset],200);
    }
    public function save_instuctor_request(Request $request){
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
        RequestChange::where('student_id',$request->student_id)->update(['status'=>RequestChange::STATUS_Reject]);
        $_request =RequestChange::UpdateOrCreate(['student_id'=>$request->student_id,
            'current_class'=>$request->current_classroom_id,'another_class'=>$request->another_classroom_id],['student_id'=>$request->student_id,'current_class'=>$request->current_classroom_id,'another_class'=>$request->another_classroom_id,'instructor_id'=>auth()->id(),'status'=>RequestChange::STATUS_ACCEPT]);
        ClassroomStudent::where(['user_id'=>$_request->student_id,'instructor_id'=>get_instructor(),'classroom_id'=>$request->current_classroom_id])->update(['classroom_id'=>$request->another_classroom_id]);
        $currentClass=Classroom::find($request->current_classroom_id)->rooms()->where('rooms.is_draft',0)->pluck('id')->toArray();
        $anotherClass=Classroom::find($request->another_classroom_id)->rooms()->where('rooms.is_draft',0)->pluck('id')->toArray();
        $classme=Classroom::find($request->another_classroom_id);
        $diff=0;
        if(count(array_diff(array_map('intval',$currentClass),$anotherClass))>0){
            $diff=1;
        }
        $data=['student_id'=>$request->student_id,'current_class'=>$request->current_classroom_id,'another_class'=>$request->another_classroom_id];


        \App\Jobs\CheckAttendance::dispatch($data,null,$classme->user->id,'request_room',$diff,auth()->user(),null);


        return response()->json(['status'=>true],200);

    }
    public function unBlock(Request $request){
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
       ClassroomStudent::where('user_id',$request->id)->where('instructor_id',get_instructor())->update(['block'=>0]);
//       dd(Attendance::where(['student_id'=>$request->id,'instructor_id'=>auth()->id()])->get());
       Attendance::where(['student_id'=>$request->id,'instructor_id'=>get_instructor()])->whereIn('status',[0,2,3])->update(['status'=>4,'created_at'=>Carbon::now()]) ;
        $text='<i><bold>Congratulations</bold></i>'. ' your account is active again continue learning. ';
        $textPhone='Congratulations'. ' your account is active again continue learning. ';
        $user=User::find($request->id);
        \App\Jobs\NotificationJop::dispatch('class','room',$text,$user,$redirect=null,$textPhone,'2');//students

        $textParent='<i><bold>Congratulations</bold></i>'. ' your student account is active again ';
        $textparentPhone='Congratulations your student account is active again ';
        \App\Jobs\NotificationJop::dispatch('class','room',$textParent,$user->parent,$redirect=null,$textparentPhone,'2');//students

        return response()->json(['status'=>true],200);

    }
}
