<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Http\Resources\Admin\LessonsResource;
use App\Models\Lesson;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-classroom|create-classroom|update-classroom|delete-classroom', ['only' => ['index', 'show', 'export'],]);
        $this->middleware('permission:create-classroom', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-classroom', ['only' => ['edit', 'update', 'parentUpdate']]);
        $this->middleware('permission:delete-classroom', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id=null)
    {
        $room = $id ? Room::findOrFail($id) :null;
        $rooms =!$id  ?  Room::select('id','title')->get()  :null;
        $instractors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();
        return view('admin.classroom.lessons.index', get_defined_vars());
    }


    public function get_lessons(Request $request, Room $room = null)
    {
        $lessons = $room->lessons ?? Lesson::all();
        $lessons = LessonsResource::collection($lessons);

        return datatables($lessons)->make(true);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonRequest $request)
    {
        // dd( json_decode($request->room_ids));
        $input = $request->except('video','duration');
        $request->duration = json_decode($request->duration);
        $input['duration'] = ($request->duration[0]||$request->duration[0]!=null?$request->duration[0]:0).'h'.($request->duration[1]&&$request->duration[1]!=null?$request->duration[1]:0).'m';

        $lessonCreate = Lesson::create($input);

        if($lessonCreate):

            if($request->file('video')){
                $lessonCreate->addMedia($request->file('video'))->toMediaCollection('video');
            }

            if($request->room_ids) :
                foreach(json_decode($request->room_ids) as $room_id):
                    $room = Room::findOrFail($room_id);
                    $max=DB::table('lesson_rooms')->where('lesson_id',$lessonCreate->id)->selectRaw('Max(lesson_order) as max');
                    $lesson=[$lessonCreate->id=>['lesson_order'=>$max->first()->max+1]];
                    $room->lessons()->syncWithoutDetaching($lesson);
                endforeach;
            endif;

            $lessonCreate->update(['is_draft'=>1]);
            return response()->json(['status' => true, 'message' => 'Created Successfully'], 200);
        else:
            return response()->json(['status' => false, 'message' => 'Something Wrong'], 404);
        endif;

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$id)
    {
        $lesson = Lesson::findOrFail($id);
        $page_tbl    = $request->page_tbl ?? null;
        return view('admin.classroom.lessons.show',get_defined_vars());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roomFromInstructors(Request $request)
    {
        if ($request->ajax()) {

            $cities = Room::select('id','title as text')
                        ->where('title', 'LIKE',  '%' . trim($request->search). '%')
                        ->where('user_id', 'LIKE',  '%' . trim($request->instructor). '%')
                        ->orderBy('title', 'asc')->simplePaginate(10);

            $morePages=true;

            if (empty($cities->nextPageUrl())){
                $morePages=false;
            }

            $results = array(
                "results" => $cities->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lesson = Lesson::with('rooms:id,title')->findOrFail($id);
        $rooms  = Room::select('id','title')->where('user_id',$lesson->user->id)->get();
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();

        $page_tbl = $request->page_tbl ?? null;

        return view('admin.classroom.lessons.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        $input=$request->except('video','duration', 'page_tbl');
        $input['duration']=($request->duration[0]||$request->duration[0]!=null?$request->duration[0]:0).'h'.($request->duration[1]&&$request->duration[1]!=null?$request->duration[1]:0).'m';

        $lesson->update($input);
       if($request->file('video')){
           unlink($lesson->getFirstMediaPath('video'));
           $lesson->clearMediaCollection('video');
           $lesson->addMedia($request->file('video'))->toMediaCollection('video');
       }
       if($request->room_id){
           $lesson->rooms()->syncWithoutDetaching($request->room_id);
       }

       $page_tbl = $request->page_tbl ?? null;

       return redirect()->route('admin.room.lessons')->with(['success' => 'Update Success','page_tbl' => $page_tbl]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }
}
