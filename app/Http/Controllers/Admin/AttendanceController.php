<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Resources\ActivityLogResource;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\CouponUsed;
use App\Models\Quiz;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class AttendanceController extends Controller
{

    public function __construct()
    {
       $this->middleware('permission:read-attendance|create-attendance|update-attendance|delete-attendance', ['only' => ['index','show','export']]);
        $this->middleware('permission:create-attendance', ['only' => ['create','store']]);
        $this->middleware('permission:update-attendance', ['only' => ['edit','update','parentUpdate']]);
        $this->middleware('permission:delete-attendance', ['only' => ['destroy', 'delete_device']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->room_id){
            $totalAttendance=Attendance::where(['instructor_id'=>$request->instructor,'attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom,'attendance_type'=>'room'])->get()->groupBy('student_id');
            $totalPresent=Attendance::where(['instructor_id'=>$request->instructor,'attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom,'status'=>1,'attendance_type'=>'room'])->get()->groupBy('student_id');
            $totalabsent=Attendance::where(['instructor_id'=>$request->instructor,'attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom,'attendance_type'=>'room'])->whereIn('status',[0,2,3])->get()->groupBy('student_id');
            $totalnewstudent=Attendance::where(['instructor_id'=>$request->instructor,'attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom,'status'=>4,'attendance_type'=>'room'])->get()->groupBy('student_id');

        }
        $instructors = User::where('type',2)->doesntHave('instructor')->get();
        return view('admin.attendance.index',get_defined_vars());
    }
    public function get_room_with_class($id){
        $classroom=Classroom::with('rooms:id,title')->findOrFail($id);
        return response()->json(['status'=>true,'data'=>$classroom->rooms],200);

    }
    public function get_instructor_with_class($id){
        $classroom=Classroom::where('instructor_id',$id)->get();
        return response()->json(['status'=>true,'data'=>$classroom],200);

    }
    public function get_quiz_with_class($id){
        $classroom=Quiz::where('classroom_id','like',"%{$id}%")->get();
        return response()->json(['status'=>true,'data'=>$classroom],200);

    }
    public function get_student_attendance_data(Request $request){
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        $rooms = User::whereIn('type',[3,4])->studentFilter($request)->orderBy('created_at','desc')->skip($start)->take($limit)->get();
        $rooms= AttendanceResource::collection($rooms);
        return datatables($rooms)->setOffset($start)->with(['recordsTotal'=>User::whereIn('type',[3,4])->studentFilter($request)->count(), "recordsFiltered" => User::whereIn('type',[3,4])->studentFilter($request)->count(),'start' => $start])->
        make(true);
    }
    public function get_logs_attendance_data(Request $request){
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
        $user=User::where('name_id','LIKE',"%".$request->name_id."%")->first();
        $activities=Activity::
        when($request->name_id, function ($query) use($user) {
            return $query->whereJsonContains('properties->attributes', ['student' => @$user->name]);
        })->
        where('subject_type','attendance')->where('causer_id',$instructor_id)->orderBy('created_at','desc')
            ->skip($start)->take($limit)->get();

        $activities= ActivityLogResource::collection($activities);
        return datatables($activities)->setOffset($start)->with(['recordsTotal'=>Activity::where('subject_type','attendance')->where('causer_id',$instructor_id)->count(),
            "recordsFiltered" => Activity::when($request->name_id, function ($query) use($user) {
                return $query->whereJsonContains('properties->attributes', ['student' => @$user->name]);
            })->where('subject_type','attendance')->where('causer_id',$instructor_id)->count(),'start' => $start])->
        make(true);
    }
    public function changeStatus(Request $request){

        $data=[];
        $data['attendance_id']=$request->room_id;
        $data['classroom_id']=$request->classroom_id;
        $data['attendance_type']=$request->attendance_type;
        $data['status']=$request->status;
        if(Auth::getDefaultDriver()=='admin'){
            $data['instructor_id'] = $request->instructor ;
            $instructor_id = $request->instructor ;

        }else{
            $data['instructor_id']=auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
            $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();

        }
        if($request->name_id!=='false'){
            $user_id=User::where('name_id',$request->student_id)->first()->id;
            $request->merge(['student_id'=>$user_id]);
        }
        if(filter_var($request->select_all, FILTER_VALIDATE_BOOLEAN)) return $this->changeStatusAll($request);

        if($request->status=="2" || $request->status=="3" ){
            $data['comment']=$request->comment;
            // log with excuse or left early


        }else{
            $data['comment']=Null;
//          $data['created_at']=now();
        }
        if($request->attendance_type=='room'){
            $couponType='room';
            $room=Room::find($request->room_id);//room
        }else{
            $room=Quiz::find($request->room_id);//quiz
            $couponType='quiz';
        }

        $user=User::find($request->student_id);
        $newAttendance=Attendance::updateOrCreate(['instructor_id'=>$instructor_id,'classroom_id'=>$request->classroom_id,'student_id'=>$request->student_id,'attendance_type'=>$request->attendance_type,'attendance_id'=>$request->room_id],$data);
        if($request->status=="2" || $request->status=="3" ) {
            $desc=$request->status=="2"?'change status absent with excuse':"change status left early";
            activity()
                ->performedOn($newAttendance)
                ->causedBy($instructor_id)
                ->withProperties(['attributes' => ["id" => $room->id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $request->classroom_id]])
                ->log($desc);
        }


        $attendanceCheckNull=Attendance::where(['instructor_id'=>$instructor_id,'classroom_id'=>$request->classroom_id,'student_id'=>$request->student_id,'attendance_type'=>$request->attendance_type])->whereDate('created_at','<',$newAttendance->created_at)->whereNull('status');
        if($attendanceCheckNull->count()>0){
            foreach ($attendanceCheckNull->get() as $attend){
                $attend->update(['status'=>0]);
                $exRoom=Room::find($attend->attendance_id);
                $desc='Use single row when take attendance room: '.$room->title.' change status absent';
                activity()
                    ->performedOn($attend)
                    ->causedBy($instructor_id)
                    ->withProperties(['attributes' => ["id" => $exRoom->id, 'title' => $exRoom->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $request->classroom_id]])
                    ->log($desc);
            }

        }

        if($request->status=="1"){

            $attendance_missed=Attendance::where(['instructor_id'=>$instructor_id,'classroom_id'=>$request->classroom_id,'student_id'=>$request->student_id,'attendance_type'=>$request->attendance_type])->whereIn('status',[0,2,3])->get();

            if($attendance_missed->count()==0 && $attendanceCheckNull->count()==0 ){
                CouponUsed::create(['coupon_id'=>0,'coupon_used_type'=>$couponType,'coupon_used_id'=>$request->room_id,'user_id'=>(int)$request->student_id,'classroom_id'=>$request->classroom_id]);
                $text='<i><bold>'.$room->title.'</bold></i>'. ' is opened Keep learning. ';
                $redirect='/room/'.$room->id;
                $user=User::find($request->student_id);
//                \App\Jobs\NotificationJop::dispatch('room_opened',$room,$text,$user,$redirect);//students
            }
            activity()
                ->performedOn($newAttendance)
                ->causedBy($instructor_id)
                ->withProperties(['attributes' => ["id" => $room->id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $request->classroom_id]])
                ->log('change status present use single row');

        }elseif($request->status=="0"){
            $user=User::find($request->student_id);
            $class=Classroom::find($request->classroom_id);
            $missed=Attendance::where('student_id',$request->student_id)->where('classroom_id',$class->id)->where('instructor_id',$class->user->id)->whereIn('status',[0,2,3])->count();
//            if($missed >= (int)$class->absence_times){
//                \App\Models\ClassroomStudent::where('user_id',$request->student_id)->where('classroom_id',$class->id)->where('instructor_id',$instructor_id)->first()->update(['block'=>1]);
//                $text='Bad News you have been suspended due to the absence limitation being exceeded! Please get in touch with your Instructor Immediately to prevent <i><bold>Permanent Removal</bold></i>.';
//
//                \App\Jobs\NotificationJop::dispatch($class,null,$text,$user,$redirect=null);
//                $textInstructor='<i><bold>'.$user->name.'</bold></i>'." has been suspended ";
//                \App\Jobs\NotificationJop::dispatch($class,null,$textInstructor,$class->user,$redirect=null);//instructor
//            }


//            $text='<i><bold>'. $room->title.'</bold></i>'. ' has been missed Hurry up and contact your instructor to prevent suspension. ';
//            $redirect='/room/'.$room->id;
//            \App\Jobs\NotificationJop::dispatch("roomMissed",$room,$text,$user,$redirect);//students
//            $textInstructor='<i><bold>'. $room->title.'</bold></i>'."has been missed by "."<i><bold>".$user->name."</bold></i>";
//            \App\Jobs\NotificationJop::dispatch("classRoomMissed",$room,$textInstructor,auth()->user(),$redirect);//instructor

            activity()
                ->performedOn($newAttendance)
                ->causedBy($instructor_id)
                ->withProperties(['attributes' => ["id" => $room->id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $request->classroom_id]])
                ->log('change status absent use single row');
        }
        $totalAttendance=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id])->get()->groupBy('student_id');
        $totalPresent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id,'status'=>1])->get()->groupBy('student_id');
        $totalabsent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id])->whereIn('status',[0,2,3])->get()->groupBy('student_id');
        $totalnewstudent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id,'status'=>4])->get()->groupBy('student_id');


        return response()->json(['status'=>true,'totalAttendance'=>count($totalAttendance),'totalPresent'=>count($totalPresent),'totalabsent'=>count($totalabsent),'totalnewstudent'=>count($totalnewstudent)],200);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatusAll($request)
    {
        $attendances= Attendance::where('classroom_id',$request->classroom_id)
            ->where('attendance_id',$request->room_id)
            ->whereIn('student_id', json_decode($request->student_id))->get();

        foreach ($attendances as $attendance) :
            $attendanceCheckNull=Attendance::where(['instructor_id'=>$attendance->instructor_id,'classroom_id'=>$attendance->classroom_id,'student_id'=>$attendance->student_id,'attendance_type'=>$attendance->attendance_type])->whereNull('status');
            $user=User::find($attendance->student_id);
            $room=Room::find($attendance->attendance_id);

            if($attendanceCheckNull->count()>0){
                foreach ($attendanceCheckNull->whereDate('created_at','<',$attendance->created_at)->get() as $attend){
                    $attend->update(['status'=>0]);
                    $exRoom=Room::find($attend->attendance_id);
                    $desc='Use select all when take attendance room: '.$room->title.'change status absent';
                    activity()
                        ->performedOn($attend)
                        ->causedBy($attend->instructor_id)
                        ->withProperties(['attributes' => ["id" => $exRoom->id, 'title' => $exRoom->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $request->classroom_id]])
                        ->log($desc);
                }
            }

            if($request->status=="1") {
                $attendance->update(['status'=>1]);

                $attendance_missed=Attendance::where(['instructor_id'=>$attendance->instructor_id,'classroom_id'=>$attendance->classroom_id,'student_id'=>$attendance->student_id,'attendance_type'=>$attendance->attendance_type])->whereIn('status',[0,2,3])->get();

                if($attendance_missed->count()==0 && $attendanceCheckNull->count()==0 ){
                    CouponUsed::create(['coupon_id'=>0,'coupon_used_type'=>'room','coupon_used_id'=>$request->room_id,'user_id'=>$attendance->student_id,'classroom_id'=>$request->classroom_id]);
                }
                activity()
                    ->performedOn($attendance)
                    ->causedBy($attendance->instructor_id)
                    ->withProperties(['attributes' => ["id" => $attendance->attendance_id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $attendance->classroom_id]])
                    ->log('change status present use select all');

            }else {
                activity()
                    ->performedOn($attendance)
                    ->causedBy($attendance->instructor_id)
                    ->withProperties(['attributes' => ["id" => $attendance->attendance_id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $attendance->classroom_id]])
                    ->log('change status absent use select all');
                $attendance->update(['status'=>0]);
            }
        endforeach;


        $totalAttendance=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id])->get()->groupBy('student_id');
        $totalPresent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id,'status'=>1])->get()->groupBy('student_id');
        $totalabsent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id])->whereIn('status',[0,2,3])->get()->groupBy('student_id');
        $totalnewstudent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id,'status'=>4])->get()->groupBy('student_id');


        return response()->json(['status'=>true,'totalAttendance'=>count($totalAttendance),'totalPresent'=>count($totalPresent),'totalabsent'=>count($totalabsent),'totalnewstudent'=>count($totalnewstudent)],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function request_qrcode(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
    public function get_activity()
    {
        return view('attendance.logs');
    }
    public function changeAttendanceStatus(Request $request)
    {
       $request->status=="true"? $status=1: $status=0;
        $classroomStudent=Attendance::where('id',$request->attendance_id)->where('student_id',$request->student_id)->where('attendance_id',$request->room_id)->where('attendance_type','room')->first()->update(['status'=>$status]);
        return response()->json(['status'=>true],200);
    }
    public function change_status_selected(Request $request){
        $attendance= Attendance::whereIn('student_id',$request->idds)->first();
        $attendance->update(['status'=>$request->status]);
        return response()->json(['status'=>true],200);
    }
    public function make_first_attendance(Request $request){
        $attendance= Attendance::where('classroom_id',$request->classroom)->where('student_id',$request->student)->where('attendance_id',$request->room)->first();

        $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        $attendance->update(['first_attendance'=> $status == true ? true : null]);
        return response()->json(['status'=>true],200);
    }
    public function make_second_attendance(Request $request){
        $attendance= Attendance::where('classroom_id',$request->classroom)->where('student_id',$request->student)->where('attendance_id',$request->room)->first();

        $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        $attendance->update(['second_attendance'=> $status == true ? true : null]);
        // if($attendance->first_attendance==1){
        //     $attendance->update(['status'=>1]);
        //     CouponUsed::create(['coupon_id'=>0,'coupon_used_type'=>'room','coupon_used_id'=>$request->room,'user_id'=>(int)$request->student,'classroom_id'=>$request->classroom]);
        // }
        return response()->json(['status'=>true],200);
    }
    public function collect_absence(Request $request){
        $attendances= Attendance::where('classroom_id',$request->classroom_id)->where('attendance_id',$request->room_id)->get();

        foreach ($attendances as $attendance) :

            $attendanceCheckNull=Attendance::where(['instructor_id'=>$attendance->instructor_id,'classroom_id'=>$attendance->classroom_id,'student_id'=>$attendance->student_id,'attendance_type'=>$attendance->attendance_type])->whereNull('status');
            $user=User::find($attendance->student_id);
            $room=Room::find($attendance->attendance_id);

            if($attendanceCheckNull->count()>0){
                foreach ($attendanceCheckNull->whereDate('created_at','<',$attendance->created_at)->get() as $attend){
                    $attend->update(['status'=>0]);
                    $exRoom=Room::find($attend->attendance_id);
                    $desc='Use collect absence when take attendance room: '.$room->title.'change status absent';
                   if($exRoom){
                       activity()
                           ->performedOn($attend)
                           ->causedBy($attend->instructor_id)
                           ->withProperties(['attributes' => ["id" => $exRoom->id, 'title' => $exRoom->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $request->classroom_id]])
                           ->log($desc);
                   }

                }
            }
            if($request->type == 1){
                if($attendance->first_attendance == 1 && $attendance->second_attendance == 1) {
                    $attendance->update(['status'=>1]);
                    $attendance_missed=Attendance::where(['instructor_id'=>$attendance->instructor_id,'classroom_id'=>$attendance->classroom_id,'student_id'=>$attendance->student_id,'attendance_type'=>$attendance->attendance_type])->whereIn('status',[0,2,3])->get();
                    if($attendance_missed->count()==0 && $attendanceCheckNull->count()==0) {
                        CouponUsed::create(['coupon_id' => 0, 'coupon_used_type' => 'room', 'coupon_used_id' => $request->room_id, 'user_id' => $attendance->student_id, 'classroom_id' => $request->classroom_id]);
                    }

                    activity()
                        ->performedOn($attendance)
                        ->causedBy($attendance->instructor_id)
                        ->withProperties(['attributes' => ["id" => $attendance->attendance_id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $attendance->classroom_id]])
                        ->log('change status present use collect absence');

                }else {
                    $attendance->update(['status'=>0]);

                    activity()
                        ->performedOn($attendance)
                        ->causedBy($attendance->instructor_id)
                        ->withProperties(['attributes' => ["id" => $attendance->attendance_id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $user->name, 'classroom' => $attendance->classroom_id]])
                        ->log('change status absent use collect absence');
                }
            }else {

                $checkNull = $attendance->where('classroom_id',$attendance->classroom_id)->where('attendance_id',$attendance->attendance_id)->where("status",null);
                if($checkNull->count()>0){
                    foreach ($checkNull->whereDate('created_at','<=',$attendance->created_at)->get() as $attend){
                        $attend->update(['status'=>0]);
                        $desc='Use collect absence when take attendance room: '.$room->title.'change status absent';
                        $exuser=User::find($attend->student_id);
                        activity()
                            ->performedOn($attend)
                            ->causedBy($attend->instructor_id)
                            ->withProperties(['attributes' => ["id" => $room->id, 'title' => $room->title,'take_action'=>auth()->user()->name, 'student' => $exuser->name, 'classroom' => $attend->classroom_id]])
                            ->log($desc);
                    }
                }
            }


        endforeach;


        $totalAttendance=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id])->get()->groupBy('student_id');
        $totalPresent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id,'status'=>1])->get()->groupBy('student_id');
        $totalabsent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id])->whereIn('status',[0,2,3])->get()->groupBy('student_id');
        $totalnewstudent=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom_id,'status'=>4])->get()->groupBy('student_id');


        return response()->json(['status'=>true,'totalAttendance'=>count($totalAttendance),'totalPresent'=>count($totalPresent),'totalabsent'=>count($totalabsent),'totalnewstudent'=>count($totalnewstudent)],200);
    }
    public function make_room_check(Request $request){

        $classRoom = Classroom::find($request->classroom);
        $users = ClassroomStudent::where('classroom_id',$request->classroom)->pluck('user_id')->toArray();

        foreach ($users as $user) :
            foreach ($classRoom->rooms as $roomFromClass){
                Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id]);
            }
            foreach ($classRoom->quizes as $quizFromClass){
                Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id]);
            }
        endforeach;

        $attendance=Attendance::where(['attendance_id'=>$request->room_id,'classroom_id'=>$request->classroom])->first();

        if($attendance){
            if(isset($attendance->type) && $attendance->type==0 &&$request->type==0){
                $attendance->update(['type'=>0]);
            }else{
                $attendance->update(['type'=>1]);
            }

            return response()->json(['status'=>true,'type'=>$attendance->type,'room_id'=>$request->room_id,'classroom'=>$request->classroom],200);
        }
        return response()->json(['status'=>true,'type'=>$attendance->type,'room_id'=>$request->room_id,'classroom'=>$request->classroom],200);


    }
}
