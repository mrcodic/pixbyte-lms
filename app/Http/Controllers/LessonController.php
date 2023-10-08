<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-lessons|create-lessons|update-lessons|delete-lessons', ['only' => ['index','show','export']]);
        $this->middleware('permission:create-lessons', ['only' => ['create','store']]);
        $this->middleware('permission:update-lessons', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-lessons', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms=Room::where('user_id',get_instructor())->select('id','title')->get();
        return  view('lesson.index',get_defined_vars());
    }
    public function get_lesson_data(Request $request){
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        $room = Lesson::where('user_id',get_instructor())->filter($request)->with('rooms:id,title')->orderBy('created_at','desc')->skip($start)->take($limit)->get();
        $rooms= LessonResource::collection($room);
        return datatables($rooms)->setOffset($start)->with(['recordsTotal'=>Lesson::where('user_id',get_instructor())->count(),
            "recordsFiltered" => Lesson::where('user_id',get_instructor())->filter($request)->count(),'start' => $start])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rooms=Room::select('id','title')->where('user_id',get_instructor())->get();
        $lessons=Lesson::select('id','title')->where('user_id',get_instructor())->get();

        return view('lesson.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonRequest $request)
    {
       $input=$request->except('video','duration');
        $input['duration']=($request->duration[0]||$request->duration[0]!=null?$request->duration[0]:0).'h'.($request->duration[1]&&$request->duration[1]!=null?$request->duration[1]:0).'m';
       $lessonCreate=Lesson::create($input);

       if($request->file('video')){
           $lessonCreate->addMedia($request->file('video'))->toMediaCollection('video');
       }
        if(gettype($request->room_id)!='string') {
            if($request->room_id){
                $rooms = Room::findOrFail($request->room_id);
                foreach ($rooms as $room){
                    $max=DB::table('lesson_rooms')->where('lesson_id',$lessonCreate->id)->selectRaw('Min(lesson_order) as max');
                    $lesson=[$lessonCreate->id=>['lesson_order'=>$max->first()->max+1]];
                    $room->lessons()->syncWithoutDetaching($lesson);
                }
            }

        }else{
            $room = Room::findOrFail($request->room_id);
            $max=DB::table('lesson_rooms')->where('lesson_id',$lessonCreate->id)->selectRaw('Min(lesson_order) as max');
            $lesson=[$lessonCreate->id=>['lesson_order'=>$max->first()->max+1]];
            $room->lessons()->syncWithoutDetaching($lesson);
        }

           if($request->action=='save_draft'){
               $lessonCreate->update(['is_draft'=>1]);
           }
        return redirect()->route('lessons.index')->with(['message'=>"created Success",'alert-type'=>'success']);

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
        $lesson=Lesson::with('rooms:id,title')->findOrFail($id);
        $roooms=Room::select('id','title')->where('user_id',get_instructor())->get();
        return view('lesson.edit',get_defined_vars());

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LessonRequest $request, Lesson $lesson)
    {
        $newLesson=clone($lesson);
        $input=$request->except('video','duration');
        $input['duration']=($request->duration[0]||$request->duration[0]!=null?$request->duration[0]:0).'h'.($request->duration[1]&&$request->duration[1]!=null?$request->duration[1]:0).'m';

        $lesson->update($input);
       if($request->file('video')){
           unlink($lesson->getFirstMediaPath('video'));
           $lesson->clearMediaCollection('video');
           $lesson->addMedia($request->file('video'))->toMediaCollection('video');
       }
       if($request->room_id){
        $rooms = Room::findOrFail($request->room_id);
        foreach ($rooms as $room){

            $max=DB::table('lesson_rooms')->where('room_id',$room->id)->selectRaw('Min(lesson_order) as max');

            if(!DB::table('lesson_rooms')->where('room_id',$room->id)->where('lesson_id',$newLesson->id)->exists()){
                $lesson=[$newLesson->id=>['lesson_order'=>$max->first()->max+1]];
             }else{
                $lesson=[$newLesson->id=>['lesson_order'=>$max->first()->max]];
             }
            $room->lessons()->syncWithoutDetaching($lesson);
        }
       }else{
           $lesson->rooms()->delete();

       }
        return redirect()->route('lessons.index')->with(['message'=>"updated Success",'alert-type'=>'success']);

    }

    public function lesson_existing(Request $request){
        $lesson=$this->maxLessonOrder($request->check_lesson,$request->room_id);
        $Rooms = Room::findOrFail($request->room_id);
        $Rooms->lessons()->sync($lesson);
        return redirect()->route('lessons.index')->with(['message'=>"created Success",'alert-type'=>'success']);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function delete_selected(Request $request){

        Lesson::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function getRoomsByClass(Request $request){
        $classroom=Classroom::with('rooms:id,title')->findOrFail($request->id);
     return response()->json(['status'=>true,'data'=>$classroom->rooms,'message'=>'return success'],200);
    }
    private function maxLessonOrder($check_lesson,$room_id){
        $lessons=$check_lesson;
        $new=[];
        $index=0;
        foreach($lessons as $i=> $ro){
            if($i==0){
                $max=DB::table('lesson_rooms')->where('room_id',$room_id)->selectRaw('Min(lesson_order) as max');
                $index = $max->first()->max+1;
            }
            $new+=[$ro=>['lesson_order'=>$index]];
            $index=$index+1;
        }
        return $new;
    }


    public function order_lesson(Request $request){
        $lessons = Room::find($request->room_id)->lessons;
        foreach ($lessons  as $lesson){
            foreach($request->input('order', []) as $row)
            {
                if($lesson->id==$row['id']){
                    $lesson->rooms()->update(['lesson_order'=>$row['position']]);
                }
            }

        }
        return response()->json(['status'=>true,'message'=>'draggable Success'],200);
    }
    public function publish_lesson(Request $request){
        $lesson=Lesson::findOrFail($request->id);
        if($lesson->is_draft){

            $lesson->update(['is_draft'=>0]);
            return response()->json(['status'=>true,'message'=>'Lesson Published'],200);

        }else{
            $lesson->update(['is_draft'=>1]);
            return response()->json(['status'=>true,'message'=>'Lesson Drafted'],200);

        }



    }
    public function lesson_to_rooms(Request $request){
        $lessonIds=explode(',',$request->lessonIds);
        $roomIds=$request->roomIds;
        $rooms=Room::whereIn('id',$roomIds)->get();

        foreach ($rooms as $room){
            $new=[];
            foreach($lessonIds as $i=> $ro){
                if($i==0){
                    $max=DB::table('lesson_rooms')->whereIn('room_id',$room)->where('lesson_id',$ro)->selectRaw('Min(lesson_order) as max');
                    $index = $max->first()->max-1;
                }
                $new+=[$ro=>['lesson_order'=>$index]];
                $index=$index-1;
            }
            $pivotData = $new;
            $room->lessons()->sync($pivotData);
        }
        return response()->json(['status'=>true,'message'=>'created Success'],200);


    }

}
