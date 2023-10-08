<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Http\Resources\RoomShowResource;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\SendNotificationTrait;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\CompleteLesson;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Room;
use App\Models\Student;
use App\Models\User;
use http\Url;
use Illuminate\Http\Request;
use App\Http\Requests\RoomCreateRequest;
use App\Http\Traits\PointsTrait;
use App\Jobs\CheckAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;
use ZipStream\Option\Archive as ArchiveOptions;


class RoomController extends Controller
{
    use HelperTrait ,SendNotificationTrait, PointsTrait;
    public function __construct()
    {
        $this->middleware('permission:read-rooms|create-rooms|update-rooms|delete-rooms', ['only' => ['index','export']]);
        $this->middleware('permission:create-rooms', ['only' => ['create','store']]);
        $this->middleware('permission:update-rooms', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-rooms', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();
        $classRooms=Classroom::select('id','title')->where('instructor_id',$instructor_id)->get();
        return view('rooms.index',get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rooms = Room::where('user_id',auth()->user()->id)->select('id','title')->get();
        $classRooms=Classroom::select('id','title')->get();
        return view('rooms.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomCreateRequest $request)
    {
        try {
            $input=$request->except('cover','action','pass_quiz');
            $roomCreate = Room::create(['user_id'=>get_instructor(),'title'=>$request->title,
                'pass_quiz'=>$request->pass_quiz?1:0
                ,'description'=>$request->description,
                "unlock_after"=>$request->price=="0" || empty($request->price)?Null:$request->unlock_after,
                'room_order'=>($request->room_order??0)
                ,'price'=>$request->price]);
            if($roomCreate->price=='0'){
                $status=1;
            }else{
                $status=Null;
            }
                   $media= Media::where('model_id',0)->where('model_type','room')->get();

                   foreach ($media as $m){
                    $m->model_id=$roomCreate->id;
                    $m->save();
                   }


            if(gettype($request->class_room_id)!='string'){
               if($request->class_room_id){
                $max=DB::table('class_rooms')->whereIn('classroom_id',$request->class_room_id)->selectRaw('Min(room_order) as max');
                $room=[$roomCreate->id=>['room_order'=>$max->first()->max-1]];

                $classRooms=Classroom::whereIn('id',$request->class_room_id)->get();
                foreach ($classRooms as $classRoom) {
                    $classRoom->rooms()->syncWithoutDetaching($room);
                    activity()
                        ->performedOn($roomCreate)
                        ->causedBy(auth()->id())
                        ->performedOn($classRoom)
                        ->withProperties(['attributes' => ["id" => $roomCreate->id, 'title' => $roomCreate->title, 'user.name' => auth()->user()->name, 'classroom' => $classRoom->id]])
                        ->log('added room');
                    // Attendance::updateOrCreate(['room_id',$room->id,'classroom_id'=>$classRoom->id,])
                    $users = ClassroomStudent::where('classroom_id', $classRoom->id)->pluck('user_id')->toArray();

                    foreach ($users as $user) {
                        foreach ($classRoom->rooms as $roomFromClass){
                            Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id],['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id,'status'=>$status]);
                        }
                        foreach ($classRoom->quizes as $quizFromClass){
                            Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id],['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id,'status'=>$status]);
                        }
                    }

                    if ($users) {
                        $getUsers = User::whereIn('id', $users)->get();
                        $redirect = '/room/' . $roomCreate->id;
                        $this->sendSingleUserNotificationInQueue(
                            'New room has been added ' . '<bold><i>' . $roomCreate->title . '</i></bold>',
                            $getUsers,
                            'Room',
                            'Room',
                            [],
                            $redirect,
                            false,
                            false,
                            ['notification_only'],
                            'New room has been added (' .$roomCreate->title. ')' ,
                            $roomCreate->title

                        );
                        // $this->make_previous_room_missed($classRoom,$users,$roomCreate);

                    }
                }
            }
            }else {

                $max=DB::table('class_rooms')->where('classroom_id',$request->class_room_id)->selectRaw('Min(room_order) as max');
            //   dd($max);
                $room=[$roomCreate->id=>['room_order'=>$max->first()->max-1]];
                $classRooms = Classroom::find($request->class_room_id);
                $classRooms->rooms()->syncWithoutDetaching($room);

                activity()
                    ->performedOn($roomCreate)
                    ->causedBy(auth()->id())
                    ->performedOn($classRooms)
                    ->withProperties(['attributes' => ["id"=>$roomCreate->id,'title'=>$roomCreate->title,'user.name'=>auth()->user()->name,'classroom'=>$request->class_room_id]])
                    ->log('added room');

                    $users=ClassroomStudent::where('classroom_id',$classRooms->id)->pluck('user_id')->toArray();

                    foreach ($users as $user) {
                        foreach ($classRooms->rooms as $roomFromClass){
                            Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id],['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id,'status'=>$status]);
                        }
                        foreach ($classRooms->quizes as $quizFromClass){
                            Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id],['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id,'status'=>$status]);
                        }
                    }
                    if($users){
                        $getUsers=User::whereIn('id',$users)->get();
                        $redirect='/room/'.$roomCreate->id;
                            $this->sendSingleUserNotificationInQueue(
                                'New room has been added '. '<bold><i>'.$roomCreate->title.'</i></bold>',
                                $getUsers,
                                'Room',
                                'room_create',
                                [],
                                $redirect,
                                false,
                                false,
                                ['notification_only'],
                                'New room has been added (' .$roomCreate->title. ')' ,
                                $roomCreate->title
                            );
                        // $this->make_previous_room_missed($classRooms,$users,$roomCreate);
                    }



            }
            if(gettype($request->class_room_id)=='string' && $request->action=='continue'){
            return redirect()->route('lessons.create',['classRoomId' =>$request->class_room_id,'room_id'=>$roomCreate->id]);
            }elseif(gettype($request->class_room_id)=='string' && $request->action=='save') {
                $roomCreate->update(['is_draft'=>1]);
                return redirect()->route('room.index')->with(['message' => "created Success And Save In Draft", 'alert-type' => 'success']);
            }else{
                if($request->action=='continue'){
                    return redirect()->route('lessons.create',['room_id'=>$roomCreate->id]);
                }else{
                    $roomCreate->update(['is_draft'=>1]);
                    return redirect()->route('room.index')->with(['message'=>"created Success And Save In Draft",'alert-type'=>'success']);
                }
            }



        }catch (\Exception $e){
            dd($e);
        }



    }
    public function room_existing(Request $request){
        $room=$request->check_room;
        $new=[];
        $index=0;
        foreach($room as $i=> $ro){
            if($i==0){
                $max=DB::table('class_rooms')->where('room_id',$ro)->where('classroom_id',$request->class_room_id)->selectRaw('Min(room_order) as max');
                $index = $max->first()->max-1;
            }
            $new+=[$ro=>['room_order'=>$index]];
            $index=$index-1;
        }
        $room=$new;
        $classRooms = Classroom::findOrFail($request->class_room_id);
        $classRooms->rooms()->syncWithoutDetaching($room);

        $users = ClassroomStudent::where('classroom_id',$classRooms->id)->pluck('user_id')->toArray();
        foreach ($users as $user) :
            foreach ($classRooms->rooms as $roomFromClass){
                Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id]);
            }
            foreach ($classRooms->quizes as $quizFromClass){
                Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id]);
            }
        endforeach;

        return redirect()->route('room.index')->with(['message'=>"created Success",'alert-type'=>'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

         $room=Room::with(['lessons'=>function($q){
             return $q->orderBy('lesson_rooms.lesson_order','asc')->get();
         }])->findOrFail($id);
         $rooms=RoomShowResource::make($room);
         $quizzes=Quiz::with('result')->select('id','title')->where('type',1)->where('room_id',$room->id)->get();
        $assignments=Quiz::with('result')->select('id','title')->where('type',3)->where('room_id',$room->id)->get();
        return Inertia::render('SingleRoom',

            [
             'rooms' => $rooms,
             'quizzes'=>$quizzes,
             'assignments'=>$assignments,
             'request'=>\request()->all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $room=Room::with('attachment')->findOrFail($id);
        $classRooms=Classroom::select('id','title')->get();

        $pageTbl = $request->pageTbl ?? null;

        return view('rooms.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoomCreateRequest $request, Room $room)
    {
        $input = $request->except('cover', 'pass_quiz');
        $input['pass_quiz'] = $request->pass_quiz ? 1 : 0;
        $room->update($input);
        $media = Media::where('model_id', 0)->where('model_type', 'room')->get();
        foreach ($media as $m) {
            $m->model_id = $room->id;
            $m->save();
        }

        $diff=0;
        $classRooms='';
        $flag='';
       if($request->class_room_id){
        $classroomOld = $room->classroom()->pluck('id')->toArray();
       DB::table('class_rooms')->where('room_id',$room->id)->whereIn('classroom_id',$classroomOld)->delete();
        $newClassroom=Classroom::find($request->class_room_id);
        foreach ($newClassroom as $key => $classRoom) {
            $max=DB::table('class_rooms')->where('classroom_id',$classRoom->id)->selectRaw('Min(room_order) as max');
            if(!DB::table('class_rooms')->where('room_id',$room->id)->where('classroom_id',$classRoom->id)->exists()){
                    $roomSync=[$room->id=>['room_order'=>$max->first()->max-1]];
                 }else{
                    $roomSync=[$room->id=>['room_order'=>$max->first()->max]];
                 }
            $classRoom->rooms()->syncWithoutDetaching($roomSync);
        }

        $classRoomsWhenDeleteClass = array_diff($classroomOld,$room->classroom()->pluck('id')->toArray());
        $classRoomsWhenAddClass = array_diff($room->classroom()->pluck('id')->toArray(),$classroomOld);


        if(count($classRoomsWhenDeleteClass)>0||count($classRoomsWhenAddClass)>0){
             $diff=1;
        }

        if(count($classRoomsWhenDeleteClass)>0){
            $classRooms=$classRoomsWhenDeleteClass;
            $flag='delete';
        }else{
            $classRooms=$classRoomsWhenAddClass;
            $flag='add';
        }
        \App\Jobs\CheckAttendance::dispatch($classRooms,$room,$room->user_id,'update_room',$diff,auth()->user(),$flag);

       }
        if($request->action=='save'){
            return redirect()->route('room.index')->with(['message'=>"updated Success",'alert-type'=>'success','pageTbl' => $request->pageTbl ?? null]);
        }else{
            return redirect()->route('lessons.create',['room_id' => $room->id]);

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $room_id=$room->id;
        $instructor_id=$room->user_id;
        if(request('classroom_id')){
            DB::table('class_rooms')->where('room_id',$room->id)->where('classroom_id',request('classroom_id'))->delete();
        }else{
            $room->delete();

        }

        CheckAttendance::dispatch(request('classroom_id'),$room_id,$instructor_id,'delete_room',null,auth()->user(),null);//students
        return redirect()->route('room.index')->with(['message'=>"Deleted Success",'alert-type'=>'success']);

    }
    public function delete_selected(Request $request){
        $room_id=$request->idds;

        $rooms=Room::whereIn('id',$request->idds);
        $instructor_id = $rooms->pluck('user_id')->toArray();
        if(request('classroom_id')){
            DB::table('class_rooms')->whereIn('room_id',$request->idds)->where('classroom_id',request('classroom_id'))->delete();
        }else{
            $rooms->delete();
        }
        \App\Jobs\CheckAttendance::dispatch(request('classroom_id'),$room_id,$instructor_id,'delete_room',null,auth()->user(),null);//students

        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function get_room_data(Request $request){
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
                $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        if($request->class_room_id){
            $room = Room:: where('user_id',$instructor_id)->whereHas('lessons')->filter($request)->with('classroom:id,title')->orderBy('created_at','desc')->skip($start)->take($limit)->get();
        $roomFilter= Room::where('user_id',$instructor_id)->whereHas('lessons')->filter($request)->count();
        }else{
        $room = Room::where('user_id',$instructor_id)->filter($request)->with('classroom:id,title')->orderBy('created_at','desc')->skip($start)->take($limit)->get();
        $roomFilter= Room::where('user_id',$instructor_id)->filter($request)->count();
        }
        $rooms= RoomResource::collection($room);
        return datatables($rooms)->setOffset($start)->with(['recordsTotal'=>Room::count(), "recordsFiltered" => $roomFilter,'start' => $start])->
        make(true);
    }
    public function order_room(Request $request)
    {
        $rooms = Classroom::find($request->class_id)->rooms;
        foreach ($rooms  as $room){
            foreach($request->input('order', []) as $row)
            {
                if($room->id==$row['id']){
                    $room->classroom()->update(['room_order'=>$row['position']]);
                }
            }

         }

        return response()->json(['status'=>true,'message'=>'draggable Success'],200);
    }
    public function room_to_classes(Request $request){
        $roomIDs=explode(',',$request->roomIds);
        $Classroomids=$request->class_room_ids;
        $classRooms=Classroom::whereIn('id',$Classroomids)->get();
        foreach ($classRooms as $classRoom){
            $new=[];
            foreach($roomIDs as $i=> $ro){
                if($i==0){
                    $max=DB::table('class_rooms')->whereIn('classroom_id',$classRoom)->where('room_id',$ro)->selectRaw('Min(room_order) as max');
                    $index = $max->first()->max-1;
                }
                $new+=[$ro=>['room_order'=>$index]];
                $index=$index-1;
            }
            $pivotData = $new;
            $classRoom->rooms()->syncWithoutDetaching($pivotData);


            $users = ClassroomStudent::where('classroom_id',$classRoom->id)->pluck('user_id')->toArray();
            foreach ($users as $user) :
                foreach ($classRoom->rooms as $roomFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id]);
                }
                foreach ($classRoom->quizes as $quizFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id]);
                }
            endforeach;
        }
        return response()->json(['status'=>true,'message'=>'created Success'],200);


    }
    public function uploadMaterial(Request $request){
        if($request->hasFile('files')){
            $room = new Room();
            $room->id=0;
            $room->exists=true;
            $image=$room->addMediaFromRequest('files')->toMediaCollection('attachment');
        }
        return response()->json([
            'uploaded'=>1,
            'url'=>$image->getUrl(),
            "id"=>$image->id,
            'file_name'=>substr($image->name,0,30)
        ]);
    }
    public function deleteImage($id,Request $request){
        $media= Media::where('model_id',$request->roomId)->where('model_type','room')->where('collection_name','attachment')->first();
        $media->delete();
        return response()->json([
            'status'=>true,
        ],200);
    }
    public function getMatrial($id){
       $room=Room::findOrFail($id);
      $download=$room->getMedia('attachment');
        ob_end_clean();
        return MediaStream::create('my-files.zip')
            ->useZipOptions(function(ArchiveOptions $zipOptions) {
                $zipOptions->setZeroHeader(true);
            })
            ->addMedia($download);
    }
    public function make_complete($room,$lesson){

        CompleteLesson::updateOrCreate(['lesson_id'=>$lesson,'user_id'=>auth()->id(),'classroom_id'=>\request()->classroom,'room_id'=>$room],['room_id'=>$room,'lesson_id'=>$lesson,'user_id'=>auth()->id(),'classroom_id'=>\request()->classroom]);
        $room=Room::findOrFail($room);

        $lessons=CompleteLesson::where('user_id',auth()->id())->where('classroom_id',(int)request()->classroom)->where('room_id',$room->id)->get();
        $progress= getProgress(count($lessons),count($room->lessons));
        $classroom=Classroom::findOrFail(request()->classroom);
        if($progress==100){
            $student=Student::where('user_id',auth()->id())->first();
            $completeNum=$student->completed_lessons;
            $student->update(['completed_lessons'=>(int)$completeNum+1]);

            $this->setPoint(auth()->user(), 'point_completed_room', Room::class, $room->id);
        }
        $attendence=Attendance::where('student_id',auth()->id())->where('room_id',$room->id)->where('classroom_id',$classroom->id)->first();
        // if($attendence){
        // $attendence->update(['status'=>1]);
        // }else{
        //     Attendance::create(['student_id'=>auth()->id(),'attendance_type'=>'room','attendance_id'=>$room->id,'classroom_id'=>$classroom->id]);
        // }

        DB::table('classroom_students')->updateOrInsert(['user_id'=>auth()->id(),'classroom_id'=>$classroom->id,'instructor_id'=>$classroom->instructor_id]);
        return response()->json(['status'=>true,'message'=>'created Success','progress'=>$progress],200);

    }
    public function publish_room(Request $request){
        $room=Room::findOrFail($request->id);

            if(count($room->lessons)>0){
               if($request->classroom_id){
                    $roomClass=DB::table('class_rooms')->where('room_id',$room->id)->where('classroom_id',request('classroom_id'))->first();
                     DB::table('class_rooms')->where('room_id',$room->id)->where('classroom_id',request('classroom_id'))->update(['is_draft'=>$roomClass->is_draft?0:1]);
                }else{
                    $room->update(['is_draft'=>$room->is_draft?0:1]);
                }
                return response()->json(['status'=>true,'message'=>'Room published'],200);
            }else{
                return response()->json(['status'=>true,'message'=>'Enter Lesson First'],200);

            }



    }
    private function make_previous_room_missed($class,$users,$roomCreate){
//     افرض كرييت الروووم كلها لسه دلوقتي ورا بعض معنا كده هتبقي ميسد اول ماتنزل
//        $rooms=$class->rooms()->where('id','!=',$roomCreate->id)->get();
//
//        foreach ($rooms as $room) {
//            foreach ($users as $user){
//                $lesson=CompleteLesson::where(['user_id'=>$user,'room_id'=>$room->id,'classroom_id'=>$class->id])->get();
//                $progress=getProgress(count($lesson),count($room->lessons));
//                if($progress!=100){
//                  Attendance::where(['classroom_id'=>$class->id,'instructor_id'=>auth()->id(),'attendance_type'=>'room'])->whereIn('student_id',$users)->where('attendance_id',$room->id)->update(['status'=>0]);
//                }
//
//            }
//
//        }
    }
}
