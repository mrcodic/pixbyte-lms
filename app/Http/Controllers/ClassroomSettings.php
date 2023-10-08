<?php

namespace App\Http\Controllers;

use App\Http\Resources\RankStudentResource;
use App\Http\Resources\StudentWorkResource;
use App\Http\Traits\HelperTrait;
use App\Models\Attendance;
use App\Models\ClassroomStudent;
use App\Models\CompleteLesson;
use App\Models\Quiz;
use App\Models\RequestChange;
use App\Models\Result;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Requests\ClassroomCreateRequest;
use App\Models\User;
use App\Models\Photo;
use App\Models\Classroom;
use App\Models\Gradable;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\RoomSchedule;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ClassroomSettings extends Controller
{
    public function __construct()
    {
        $this->middleware('Instructor')->only('edit','create','update','destroy');

        $this->middleware('permission:read-classroom|create-classroom|update-classroom|delete-classroom', ['only' => ['show','instructor','export']]);
        $this->middleware('permission:create-classroom', ['only' => ['create','store']]);
        $this->middleware('permission:update-classroom', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-classroom', ['only' => ['destroy']]);
    }
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myclasses='';
        if(auth()->user()->type == 3 || auth()->user()->type == 4){
            $classes    = Classroom::whereHas('grade',function ($q){
                $q->where('grade_id',auth()->user()->grade_id);
            })->where('is_draft',0)->whereHas('rooms')->get();

            $myclasses=ClassroomStudent::where('user_id',\auth()->id())->pluck('classroom_id','subject_id')->toArray();
        }else{
            $classes    = Classroom::whereHas('rooms')->get();
        }
        $grades     = Grade::all();
        return view('classroom.index', compact('classes','grades','myclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grades     = Grade::all();
        $subjects   = Subject::all();
        $schedules  = RoomSchedule::all();
        return view('classroom.addNewClass', compact('grades','subjects','schedules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomCreateRequest $request)
    {
        try{
            $input      = $request->except('setting_missed');
            $input['setting_missed']=$request->setting_missed?1:0;
            $userId     = $request->userId;
            $grade      = $request->grade_id;
            $user       = User::findOrFail($userId);
            DB::beginTransaction();
            $user->classrooms()->create($input);
            $classId    = Classroom::latest()->first();
            if (!empty($classId)) {
                $nextClass =+ $classId->id;
            } else {
                $nextClass = 1;
            }

            // if ($file = $request->file('cover')) {
            //     $this->saveImage($classId,$file);
            // }
            $media=Photo::where('imageable_type','App\Models\Classroom')->where('imageable_id',0)->latest()->first();
            if($media)
            {
                $media->imageable_id=$classId->id;
                $media->save();
            }

            Gradable::create(['grade_id'=>$grade, 'gradable_id'=>$nextClass, 'gradable_type'=>'App\Models\Classroom']);
            DB::commit();
            if($request->action=='save'){
                $classId->update(['is_draft'=>1]);
                return redirect()->route('myclassrooms.index')->with(['message'=>"created Success and save In Draft",'alert-type'=>'success']);
            }else{
                return redirect()->route('room.create',['classRoomId' => $classId->id]);

            }
        } catch (\Exception $e) {
           DB::rollBack();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getRooms(Request $request, $id){
        $oid = $id;
        $id = $request->shtc && Setting::DemoRoom()? Setting::DemoRoom() :$id;

        $requests=RequestChange::where(['current_class'=>$id,'student_id'=>\auth()->id()])->get();
        $exams=Quiz::where('type',2)->where('classroom_id','like',"%{$id}%")->orderBy('id','desc')->paginate(5);

        $classessRequset=[];
        $class = Classroom::with(['rooms'=>function($q){
            return $q->whereHas('lessons')->orderBy('class_rooms.room_order','asc')->paginate(5);
        }])->findOrFail($id);
        if(\auth()->user()->type !=2){
            $classessRequset=Classroom::whereHas('grade',function ($q){
                $q->where('grade_id',\auth()->user()->grade->id);
            })->where('id','!=',$class->id)->where('subject_id',$class->subject_id)->where('instructor_id',$class->instructor_id)->select('id','title')->get();
        }

        $request->shtc && Setting::DemoRoom()? Session::put('classroom',$oid) :Session::put('classroom',$class->id);

        if(request()->ajax()){
         if(request('type')!=='exam'){
             $html = '';
             $count='';
             $pages=\request('page');
             if($pages==2){
                 $count=5;
             }elseif ($pages==3){
                 $count=10;

             }elseif ($pages==4){
                 $count=15;

             }elseif ($pages==5){
                 $count=20;

             }elseif ($pages==6){
                 $count=25;

             }else{
                 $count=30;

             }             foreach ($class->rooms as $key => $room) {
                 if($room->usedCoupon->last()){
                     $day=$room->unlock_after;
                     $created_at=($room->usedCoupon?$room->usedCoupon->last()->created_at:null);
                     $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
                     $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
                     $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());

                     $result = $date1->gt($date2);
                 }else{
                     $result=false;
                 }
                 if(auth()->user()->type!==2){
//                     coronJopAttendence($room,auth()->id(),$result,$class);
                     coronJopAbsence(auth()->id(),$class);
                 }

                 $lock='';
                 $lockQuiz=false;
                 if(($room->price!=="0" && !$result) || $room->price){
                     if(!$room->usedCoupon || ( $room->usedCoupon && !$result)){
                         $lockQuiz=true;
                         $lock.='<li><a href="#modal-center" data-roomId="'.$room->id.'" data-roomPrice="'.$room->price.'" data-gradeId="'.getGradeId($class->id).'" id="unlock_code" uk-toggle>Unlock</a></li>';
                     }
                 }
                 $quizDiv='';
                 foreach ($room->quizzes as $k => $qu){
                    $asset="";
                    if( $qu->type==3){
                        $asset="src=' ".asset('img/assignment.svg')."'";
                        $PartName='Assignment';
                    } else{
                        $asset="src=' ".asset('img/quiz.svg')."'";
                        $PartName='Quiz';

                    }
                     $quizDiv.='
                    <div class="collapse-video">
                        <img class="collapse-icon"  '.$asset.'   alt="video-icon" uk-svg>
                        <a href="#" class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">
                            '.$PartName.' '.sprintf('%02d',$k+1). ' '. $qu->title.'
                        </a>
                    </div>
                    ';
                 }

                 $lessonDiv='';
                 foreach ($room->lessons as $k => $less){
                     $lessonDiv.='
                    <div class="collapse-video">
                        <img class="collapse-icon" src=" '.asset('img/video-camera.svg').' "  alt="video-icon" uk-svg>
                        <a href="#" class="dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">
                            Part '.sprintf('%02d',$k+1). ' '. $less->title.'
                        </a>
                    </div>
                    ';
                 }
                 $spanlock='';
                 if(checkMissed($room->id,\auth()->id())){
                     $spanlock.='<span class="danger-text" uk-tooltip="Room is missed" uk-icon="icon: warning" ></span>';

                 }
                 elseif((!$room->usedCoupon) || ( $room->usedCoupon && !$result)){
                     if( !$room->price || (!$result && $room->price=="0")){
                         $spanlock.='<span uk-tooltip="Room is unLocked" uk-icon="icon: unlock"></span>';

                     }else{
                         $spanlock.='<span uk-tooltip="Room is locked" uk-icon="icon: lock"></span>';

                     }
                 }else{
                     $spanlock.='<span uk-tooltip="Room is unLocked"  uk-icon="icon: unlock" ></span>';
                 }
                 $svglock='';
                 $lesson=CompleteLesson::where(['user_id'=>auth()->id(),'room_id'=>$room->id,'classroom_id'=>$class->id])->get();
                 if(getProgress(count($lesson),count($room->lessons))==100){
                     $svglock='<svg uk-tooltip="Completed" class="check-icon rooms-icon viewed">✔<use xlink:href="#tick">';
                 }else{
                     $svglock='<svg uk-tooltip="UnCompleted" class="check-icon rooms-icon">✔<use xlink:href="#tick">';

                 }
                 $price='';
                 if($room->price){
                     $price.='<span class="price"> '.$room->price.' L.E</span>';
                 }else{
                     $price.='<span class="price"> 0 L.E</span>';

                 }
                 $image='';
                 if(checkMissed($room->id,auth()->id()))
                     $image='<img class="lock-time" src="/img/missisng-small.png" alt="missing"    />';
                 else
                     $image='';

                 $span='
                    <div class="uk-width-auto uk-margin-small-top rm-t-s">
                    '.$image.'
                     <span class="lock-time">'.unlockafterRoomDetail($room->price ,$room->unlock_after,$room->usedCoupon->last()->created_at??'').' </span>
                     '.$price.'
                     '.$spanlock.'
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
                        <symbol viewBox="0 0 128 128" id="tick"><path d="M112.639844,0 L35.84,88.575 L17.9203125,69.82 L0,69.82 L35.84,128 L128,0 L112.639844,0 Z"></path></symbol>
                        </svg>
                    '.$svglock.'
                        </div>
                    ';
                 $missed='';
                 if(checkMissed($room->id,auth()->id())){
                     $missed="hover_missed";
                 }
                 $blockDiv='';
                 if(auth()->user()->type!==2 &&\App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block){
                     $blockDiv='block_div';
                 }

                 $Notblock='';
                 if(auth()->user()->type!==2 &&\App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block==0){

                     $Notblock='
             <div class="uk-width-auto">
                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                <div class="uk-border-rounded" uk-dropdown="mode: click">
                    <ul class="uk-nav uk-dropdown-nav">
                      <li><a href="#">Copy</a></li>'.$lock.'
                    </ul>
                   </div>
            </div>
';
                 }

                 if(auth()->user()->type==2){

                     $Notblock='
             <div class="uk-width-auto">
                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                <div class="uk-border-rounded" uk-dropdown="mode: click">
                    <ul class="uk-nav uk-dropdown-nav">
                      <li><a href="#">Copy</a></li>'.$lock.'
                    </ul>
                   </div>
            </div>';
                 }

                 $resultQuiz=checkTakeQuiz($room->quizzes);

                 if($room->pass_quiz && $resultQuiz && !$lockQuiz ){
                    if($resultQuiz['type']==3)
                      $url=route('get_assignment',$resultQuiz['id']);
                    else
                      $url=route('quiz.show',$resultQuiz['id']);
                 }else{
                     $url=route('room.show',$room->id);
                 }
                 $html.='
<div class="uk-card '.$blockDiv.' uk-card-default classroom-card up-hover uk-margin-bottom '.$missed.' " >
                        <div class="uk-padding-small border-radius">
                                    <div class="uk-flex cursor-pointer">
                                             <div class="uk-width-expand collapse-header">
                                                <div class="up-room">
                                                    <img class="icon-activity" src=" '.asset('img/xp-lesson.svg').'" alt="room-icon">
                                                    <h5 class="rm-t-s room-text-data dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">Room <small>'.sprintf('%02d', (count(Classroom::where('id',$class->id)->first()->rooms()->whereHas('lessons')->get())-($key+$count))). '</small><a href="'.$url.'"' . '><span class="unit-title uk-text-bold"> '.$room->title.'</span></a>
                                                    </h5>
                                                  <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left"><time datetime="2016-04-01T19:00"> '.Carbon::parse(DB::table('class_rooms')->where('classroom_id',$class->id)->where('room_id',$room->id)->first()->created_at)->format('M d, Y').'</time></p>
                                                </div>
                                             </div>
                                               '.$span.'

                                            '.$Notblock.'
                                     </div>
                                    <div class="uk-flex collapse-content">
                                       '.$quizDiv.'
                                       '.$lessonDiv.'
                                    </div>
                </div>

        </div>
                ';
             }

             return $html;
            }else{
             $html = '';
             $count='';
             $pages=\request('page');
             if($pages==2){
                 $count=5;
             }elseif ($pages==3){
                 $count=10;

             }elseif ($pages==4){
                 $count=15;

             }elseif ($pages==5){
                 $count=20;

             }elseif ($pages==6){
                 $count=25;

             }else{
                 $count=30;

             }
             foreach ($exams as $key => $room) {
                 if($room->usedCoupon->last()){
                     $day=$room->lock_after;
                     $created_at=($room->usedCoupon?$room->usedCoupon->last()->created_at:null);
                     $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
                     $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
                     $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());

                     $result = $date1->gt($date2);
                 }else{
                     $result=false;
                 }
                 if(auth()->user()->type!==2){
//                     coronJopAttendenceExam($room,auth()->id());
                 }

                 $lockExam='';
                 if(($room->price!=="0" && !$result) || $room->price){

                     if(!$room->usedCoupon || ( $room->usedCoupon && !$result)){
                         $lockExam.='<li><a href="#modal-center" data-roomId="'.$room->id.'" data-roomPrice="'.$room->price.'" data-gradeId="'.getGradeId($class->id).'" id="unlock_code" uk-toggle>Unlock</a></li>';
                     }
                 }
                 $spanlockExam='';
                 if(checkMissedExam($room,\auth()->id())){
                     $spanlockExam.='<span class="danger-text" uk-tooltip="Room is missed" uk-icon="icon: warning" ></span>';

                 }
                 elseif((!$room->usedCoupon) || ( $room->usedCoupon && !$result)){
                     if( !$room->price || (!$result && $room->price=="0")){
                         $spanlockExam.='<span uk-tooltip="Room is unLocked" uk-icon="icon: unlock"></span>';
                     }else{
                         $spanlockExam.='<span uk-tooltip="Room is locked" uk-icon="icon: lock"></span>';
                     }
                 }

                 $price='';
                 if($room->price){
                     $price.='<span class="price"> '.$room->price.' L.E</span>';
                 }else{
                     $price.='<span class="price"> 0 L.E</span>';

                 }
                 $image='';
                 if(checkMissedExam($room,auth()->id()))
                     $image='<img class="lock-time" src="/img/missisng-small.png" alt="missing"    />';
                 else
                     $image='';

                 $span='
                    <div class="uk-width-auto uk-margin-small-top rm-t-s">
                    '.$image.'
                     <span class="lock-time">'.unlockafterRoomDetail($room->price ,$room->lock_after,$room->usedCoupon->last()->created_at??'').' </span>
                     '.$price.'
                     '.$spanlockExam.'
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
                        <symbol viewBox="0 0 128 128" id="tick"><path d="M112.639844,0 L35.84,88.575 L17.9203125,69.82 L0,69.82 L35.84,128 L128,0 L112.639844,0 Z"></path></symbol>
                        </svg>
                        </div>
                    ';
                 $missed='';
                 if(checkMissedExam($room,auth()->id())){
                     $missed="hover_missed";
                 }
                 $blockDiv='';
                 if(auth()->user()->type!==2 &&\App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block){
                     $blockDiv='block_div';
                 }

                 $Notblock='';
                 if(auth()->user()->type!==2 &&\App\Models\ClassroomStudent::where('user_id',auth()->id())->where('classroom_id',$class->id)->first()->block==0){

                     $Notblock='
             <div class="uk-width-auto">
                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                <div class="uk-border-rounded" uk-dropdown="mode: click">
                    <ul class="uk-nav uk-dropdown-nav">
                      <li><a href="#">Copy</a></li>'.$lockExam.'
                    </ul>
                   </div>
            </div>
';
                 }

                 if(auth()->user()->type==2){

                     $Notblock='
             <div class="uk-width-auto">
                <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                <div class="uk-border-rounded" uk-dropdown="mode: click">
                    <ul class="uk-nav uk-dropdown-nav">
                      <li><a href="#">Copy</a></li>'.$lockExam.'
                    </ul>
                   </div>
            </div>';
                 }
                 $html.='
<div class="uk-card '.$blockDiv.' uk-card-default classroom-card up-hover uk-margin-bottom '.$missed.' " >
                        <div class="uk-padding-small border-radius">
                                    <div class="uk-flex cursor-pointer">
                                             <div class="uk-width-expand collapse-header">
                                                <div class="up-room">
                                                    <img class="icon-activity" src=" '.asset('img/exam.svg').'" alt="room-icon">
                                                    <h5 class="rm-t-s room-text-data dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">Room <small>'.sprintf('%02d', (count(\App\Models\Quiz::where('classroom_id','like',"%{$class->id}%")->where('type',2)->get())-($key+$count))). '</small><a href="'.route('quiz.show',$room->id).'"' . '><span class="unit-title uk-text-bold"> '.$room->title.'</span></a>
                                                    </h5>
                                                 <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left"><time datetime="2016-04-01T19:00"> '.\Carbon\Carbon::parse($room->created_at)->format('M d, Y').'</time></p>

                                                </div>
                                             </div>
                                               '.$span.'

                                            '.$Notblock.'
                                     </div>

                </div>

        </div>
                ';

             }
//             dd($html);
             return $html;
         }
        }

        $grade      = Grade::findOrFail($class->grade->grade_id);
        $schedule   = RoomSchedule::findOrFail($class->room_scheduel);
        $schedule   = $schedule->name;
        $roomsCount=count(Classroom::findOrFail($id)->rooms()->whereHas('lessons')->get());
        $examCount=count(Classroom::findOrFail($id)->quizes);

        return view('classroom.singleClass', compact('class','exams','examCount','grade','schedule','roomsCount','classessRequset','requests'));
    }

    public function enter_class_room($id){
        $class = Classroom::findOrFail($id);
        foreach ($class->rooms as $room){
            Attendance::create(['attendance_id'=>$room->id,'attendance_type'=>'room','student_id'=>auth()->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id]);
        }
        foreach ($class->quizes as $quiz){
            Attendance::create(['attendance_id'=>$quiz->id,'attendance_type'=>'quiz','student_id'=>auth()->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id]);

        }
        DB::table('classroom_students')->updateOrInsert(['user_id'=>auth()->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->instructor_id,'subject_id'=>$class->subject_id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        return response()->json(['status'=>true,'message'=>'Request Done Please Wait Approve'],200);

    }
    public function getUpdates($id){


        $requests=RequestChange::where(['current_class'=>$id,'student_id'=>\auth()->id()])->get();

        $classessRequset=[];
        $class = Classroom::with(['rooms'=>function($q){
            return $q->orderBy('class_rooms.room_order','desc')->paginate(5);
        }])->findOrFail($id);
//        DB::table('classroom_students')->updateOrInsert(['user_id'=>auth()->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->instructor_id,'subject_id'=>$class->subject_id]);
        if(\auth()->user()->type !=2) {
            $classessRequset = Classroom::whereHas('grade', function ($q) {
                $q->where('grade_id', \auth()->user()->grade->id);
            })->where('id', '!=', $class->id)->where('subject_id', $class->subject_id)->where('instructor_id', $class->instructor_id)->select('id', 'title')->get();
        }
        $grade      = Grade::findOrFail($class->grade->grade_id);
        $schedule   = RoomSchedule::findOrFail($class->room_scheduel);
        $schedule   = $schedule->name;
        $activities=Activity::where('subject_type','App\Models\Classroom')->where('causer_id',$class->instructor_id)->where('subject_id',$class->id)->orderBy('created_at','desc')->paginate(5);

        if(request()->ajax()) {
            $html = '';
            foreach ($activities as $key =>$activity){
                $divcopy='';
                if((@$activity->description==='added room')){
                    $divcopy.='
                          <div class="uk-width-auto">
                    <span class="more-dots light uk-border-circle" uk-icon="icon:more-vertical;"></span>
                    <div class="uk-border-rounded" uk-dropdown="mode: click">
                        <ul class="uk-nav uk-dropdown-nav">
                            <li><a href="'.route('room.show',$activity['properties']['attributes']['id']).'">Enter room</a></li>
                            <li><a href="#" id="copyLink" data-link="'.route('room.show',$activity['properties']['attributes']['id']).'" >Copy</a></li>
                        </ul>
                    </div>
                </div>
';
                }
                $html.='
                    <div class="uk-card uk-card-default classroom-card up-hover uk-margin-bottom">
        <div class="uk-padding-small border-radius">
            <div class="uk-flex border-radius">
                <div class="uk-width-expand">
                    <a href="#" class="rmv-underline">
                        <div class="up-room">
                            <img class="icon-activity" src="'. asset('img/xp-lesson.svg').'" alt="room-icon">
                            <h5 class="rm-t-s room-text-data dark-font uk-margin-remove-bottom uk-margin-small-top inline-block uk-margin-small-left uk-text-bold">
                                '.$activity['properties']['attributes']['user.name'].' '
                                 .$activity->description.'
                                <a href="'.route('room.show',$activity['properties']['attributes']['id']).'">
                                                                <span class="unit-title uk-text-bold">
                                                                '.
                                                                    $activity['properties']['attributes']['title'].'
                                                                </span>
                                </a>
                            </h5>

                            <p class="date-padding light-dark uk-margin-small-bottom uk-margin-remove-top uk-margin-medium-left">
                                <time datetime="2016-04-01T19:00"> '.$activity->created_at.'</time>
                            </p>
                        </div>
                    </a>
                </div>
                 '.$divcopy.'
            </div>
        </div>
    </div>

                ';
            }

            return  $html;
        }
        return view('classroom.singleClass', compact('class','grade','schedule','activities','classessRequset','requests'));
    }

    public function getMyWork($id){
        $class=Classroom::find($id);
        $classessRequset=[];
        if(\auth()->user()->type !=2) {
            $classessRequset = Classroom::whereHas('grade', function ($q) {
                $q->where('grade_id', \auth()->user()->grade->id);
            })->where('id', '!=', $class->id)->where('subject_id', $class->subject_id)->where('instructor_id', $class->instructor_id)->select('id', 'title')->get();
        }
        return view('classroom.singleClass',compact('class','classessRequset'));
    }

    public function getClassRank($id){
        $class=Classroom::find($id);
        $classessRequset=[];
        $students=$class->students()->where('instructor_id',$class->instructor_id)->get();
        return view('classroom.singleClass',compact('class','students','classessRequset'));
    }


    public function get_mywork_data(Request $request){
        $type=$request->type;
        if($type){
        $user=\auth('parent')->user()->user->id;
        $id=ClassroomStudent::where('user_id',$user)->first()->classroom_id;
        }else{
            $id=$request->classroom_id;
            $user=auth()->id();
        }


        $data=Result::with('quiz')->whereHas('quiz',function ($q) use($id){
            $q->where('classroom_id',$id);
        })->get();
            // dd($arrData);
        $rooms= StudentWorkResource::collection($data->toArray());
        return datatables($rooms)->make(true);
    }
    public function get_classrank_data(Request $request){
        $class=Classroom::find($request->classroom_id);
        $students=$class->students()->where('instructor_id',$class->instructor_id)->get();
        $students= RankStudentResource::collection($students);
        return datatables($students)->make(true);

    }

    public function show($id)
    {
        $class      = Classroom::with(['rooms'=>function($q){
            return $q->orderBy('class_rooms.room_order','desc')->paginate(5);
        }])->findOrFail($id);

        $grade_id   = Gradable::Where('gradable_id', $id)->firstOrFail();
        $grade_id   = $grade_id->grade_id;
        $grade      = Grade::findOrFail($grade_id);
        $schedule_id= $class->room_scheduel;
        $schedule   = RoomSchedule::findOrFail($schedule_id);
        $schedule   = $schedule->name;

        $activities=Activity::where('causer_id',$class->instructor_id)->get();
        return view('classroom.singleClass', compact('class','grade','schedule','activities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $class      = Classroom::findOrFail($id);
        $grades     = Grade::all();
        $subjects   = Subject::all();
        $schedules  = RoomSchedule::all();
        $grade_id   = $class->grade? $class->grade->grade_id:'';
        return view('classroom.update', compact('class','grade_id','grades','subjects','schedules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required',
            'grade_id'      => 'required',
            'subject_id'    => 'required',
            'room_scheduel' => 'required',
            'absence_times' => 'required',
            'description'   => 'required',

        ]);
        $class      = Classroom::findOrFail($id);
        $class->update([
            'title'         =>  $request->title,
            'description'   =>  $request->description,
            'subject_id'    =>  $request->subject_id,
            'absence_times' =>  $request->absence_times,
            'room_scheduel' =>  $request->room_scheduel,
            'setting_missed' =>  $request->setting_missed?1:0,
        ]);

        // if ($file = $request->file('cover')) {
        //     foreach ($class->photos as $photo) {
        //         $photo_id = $photo->id;
        //     }
        //     $fileName   = date('YmdHi').$file->getClientOriginalName();
        //     $img        = Image::make($file)->resize(375,200);
        //     $img->save('uploads/media/'.$fileName, 60);
        //     $photo      = Photo::findOrFail($photo_id);
        //     $photo->update([
        //         'path'=>$fileName,
        //     ]);
        // }
           $media=Photo::where('imageable_type','App\Models\Classroom')->where('imageable_id',0)->latest()->first();
            $media->imageable_id=$class->id;
            $media->save();

        if ($grade_id = $request->grade_id) {
            $old_id = $class->grade?->grade_id;
            Gradable::where('gradable_id',$class->id)
                        ->updateOrCreate(['grade_id' => $grade_id,'gradable_id'=>$class->id,'gradable_type'=>'App\Models\Classroom'],['grade_id' => $grade_id,'gradable_type'=>'App\Models\Classroom']);
        }
        if($request->action=='save'){
            return redirect()->route('myclassrooms.index')->with(['message'=>"Updated Success",'alert-type'=>'success']);
        }else{
            return redirect()->route('room.create',['classRoomId' => $class->id]);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        $class = Classroom::findOrFail($id);
        $class->delete();
        return redirect()->route('myclassrooms.index');
    }
    public function get_student_use_id(Request $request) {
        $student=User::where('name_id',$request->student_id)->first();
        if($student){
            return response()->json(['status'=>true,'data'=>$student],200);
        }else{
            return response()->json(['status'=>false,'data'=>$student],200);
        }
    }
    public function add_student_in_classroom(Request $request) {
        $student=User::where('id',$request->id)->first();

        $class = Classroom::findOrFail($request->classroom_id);
        foreach ($class->rooms as $room){
            // ممكن تكون دي المشكلة بس ب يعدل ب حاله 4
            Attendance::firstOrCreate(['attendance_id'=>$room->id,'attendance_type'=>'room','student_id'=>$student->id,'classroom_id'=>$class->id,'instructor_id'=>$class->user->id],['attendance_id'=>$room->id,'attendance_type'=>'room','student_id'=>$student->id,'classroom_id'=>$class->id,'instructor_id'=>$class->user->id,'status'=>Null]);
        }
        foreach ($class->quizes as $quiz){
            Attendance::firstOrCreate(['attendance_id'=>$quiz->id,'attendance_type'=>'quiz','student_id'=>$student->id,'classroom_id'=>$class->id,'instructor_id'=>$class->user->id]);
        }

        DB::table('classroom_students')->updateOrInsert(['user_id'=>$student->id,'classroom_id'=>$class->id,'instructor_id'=>$class->instructor_id,'subject_id'=>$class->subject_id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        return response()->json(['status'=>true,'data'=>'done'],200);

    }

    public function instructor(Request $request) {


        $classes        = Classroom::where('instructor_id',get_instructor())->whereNot('id', Setting::DemoRoom())->orderBy("id","desc");
        $classesCount   = Classroom::where('instructor_id',get_instructor())->count();
        $grades         = Grade::all();
     if($request->ajax()){
         if($request->search){
             $classes=$classes->where('title','like', '%'.$request->search.'%')->paginate(8);
         }else{
             $classes= $classes->paginate(8);
         }
         $html = '';
         foreach ($classes as $class){
             $photo='';
             foreach ($class->photos as $photo){
                $photo="url(uploads/media/". "$photo->path)";
             }
             $gradeOut='';
             foreach ($grades as $grade){
                 if($grade->id===$class?->grade?->grade_id){
                     $gradeOut=$grade->name;
                 }

             }
             $draft='';
             $hover='';
             if($class->is_draft){
                 $draft='<li><a href="#" data-id="'.$class->id.'" id="published">Publish</a></li>';
                 $hover='title="is Draft" style="background: #fbe3b2;    border-radius: 10px;"';
             }
             $html.='
             <div class="uk-width-1-4@l uk-width-1-3@s">
                    <div class="uk-card uk-card-default classroom-card">

                        <div class="uk-card-header uk-card-media-top classroom-header border-radius" style="background-image:'.$photo.'">

                            <div class="uk-flex uk-padding-small border-radius">
                                <div class="uk-width-3-4">
                                    <a href="/classroom/'.$class->id.'/classwork">
                                        <h3 class="uk-card-title uk-margin-remove-bottom">

                                        '.$class->title.'
                                        </h3>
                                    </a>
                                    <p class="uk-text-meta uk-margin-small-bottom uk-margin-remove-top"><time datetime="2016-04-01"> '.\Carbon\Carbon::parse($class->created_at)->format('j F, Y').'</time></p>
                                    <a href="/u/'.$class->user->name_id.'/">
                                        <p class="uk-margin-remove inline-block light-color">
                                            '.$class->user->first_name.' '.$class->user->last_name.'
                                        </p>
                                    </a>
                                    <br>
                                    <a href="#">
                                        <p class="uk-margin-remove inline-block light-color">
                                           '.@$gradeOut.'
                                        </p>
                                    </a>
                                </div>
                                <div class="uk-width-1-4">
                                    <span class="more-dots uk-border-rounded uk-icon" uk-icon="icon:more-vertical;" tabindex="0" aria-haspopup="true" aria-expanded="false"></span>
                                    <div class="uk-border-rounded uk-dropdown" uk-dropdown="mode: click">
                                        <ul class="uk-nav uk-dropdown-nav">


                                            <li><a class="modal-student"  data-id="{{$class->id}}" href="#modal-student" uk-toggle>Add student</a></li>
                                            <li><a href="/student?classroom='.$class->id.'">Students</a></li>
                                                 '.$draft.'
                                            <li><a href="'.route('classrooms.edit',['classroom'=>$class->id]).'">Edit</a></li>
                                            <li>
                                                    <a class="uk-button uk-button-text delete_class " data-id="{{$class->id}}" >Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                 <div class="free-element instructor-img">
                                    <img class="uk-border-circle" src="'.url('uploads/profile_images/'. $class->user->profile_image).'" width="70" height="70">
                                </div>
                            </div>
                        </div>

                        <div class="uk-card-body">
                            <p>'.$class->description.'</p>
                        </div>

                        <div class="uk-card-footer uk-flex uk-flex-between"  '.$hover.' >
                            <a href="/room?class_room_id='.$class->id.'" class="uk-button uk-borderless uk-button-text">Edit rooms</a>
                            <a href="/classroom/'.$class->id.'/classwork" class="uk-button uk-borderless uk-button-text">Enter the class</a>
                        </div>

                    </div>
                </div>

             ';

         }
         return $html;
     }else{
         $classes=$classes->paginate(8);
     }
        return view('instructor.myclassrooms',compact('classes','grades','classesCount'));
    }
    public function get_classroom_student(Request $request){
        $class=Classroom::findOrFail($request->id);
        $currentClass=ClassroomStudent::where('user_id',\auth()->id())->where('subject_id',$class->subject_id)->where('instructor_id',$class->user->id)->first();
        RequestChange::updateOrCreate(['current_class'=>$currentClass->classroom_id,'another_class'=>$class->id,'student_id'=>\auth()->id()],['current_class'=>$currentClass->classroom_id,'another_class'=>$class->id,'instructor_id'=>$class->user->id,'student_id'=>$currentClass->user_id]);

        $text=' Your request to change From the <bold><i>'.$currentClass->classroom->title.'</i></bold> to the <bold><i>'.$class->title.'</i></bold> under review. ';
        $textPhone=' Your request to change From the ('.$currentClass->classroom->title.') to the ('.$class->title.') under review. ';
        \App\Jobs\NotificationJop::dispatch($class,null,$text,auth()->user(),$redirect=null,$textPhone,'2');//students
        $textInstructor='<bold></boldi>'.\auth()->user()->name.'</i></bold> Make request to change From the <bold><i>'.$currentClass->classroom->title.'</i></bold> to the <bold><i>'.$class->title.'</i></bold> . ';
        $textInstructorPhone='('.\auth()->user()->name.') Make request to change From the ('.$currentClass->classroom->title.') to the ('.$class->title.') . ';

        \App\Jobs\NotificationJop::dispatch('classRoom','',$textInstructor,$class->user,$redirect=null,$textInstructorPhone,'2');//instructor


        return response()->json(['status'=>true,'message'=>'Request Done Please Wait Approve'],200);
    }
    public function publish_class_room(Request $request){
        $class=Classroom::findOrFail($request->id);
        if(count($class->rooms)>0){
            $class->update(['is_draft'=>0]);
            return response()->json(['status'=>true,'message'=>'Classroom published'],200);
        }else{
            return response()->json(['status'=>true,'message'=>'Enter Room First'],200);

        }

    }
    public function uploadCover(Request $request){
        Photo::where('imageable_type','App\Models\Classroom')->where('imageable_id',0)->delete();
        if($request->hasFile('files')){
            $fileName   = date('YmdHi').$request['files']->getClientOriginalName();
            $img        = Image::make($request['files'])->resize(440,140);
            $img->save('uploads/media/'.$fileName, 60);
           $image= Photo::create(['imageable_type'=>'App\Models\Classroom','imageable_id'=>0,'path'=>$fileName]);
        }
        return response()->json([
            'uploaded'=>1,
            'url'=>url('uploads/media/'.$image->path),
            "id"=>$image->id,
            'file_name'=>substr($fileName,0,30)
        ]);
    }
    public function deleteCover($id,Request $request){
        $media= Photo::where('imageable_id',$request->roomId)->where('imageable_type','App\Models\Classroom')->first();
        $filePath = public_path('uploads/media/'.$media->path);
                // Delete the file
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
        Storage::delete('uploads/media/'.$media->path);
        $media->delete();
        return response()->json([
            'status'=>true,
        ],200);
    }

}
