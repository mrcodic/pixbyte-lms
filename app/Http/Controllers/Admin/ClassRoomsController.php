<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomCreateRequest;
use App\Http\Resources\Admin\ClassRoomsResource;
use App\Http\Resources\Admin\RoomsResource;
use App\Models\Classroom;
use App\Models\Gradable;
use App\Models\Grade;
use App\Models\Instructor;
use App\Models\Photo;
use App\Models\Room;
use App\Models\RoomSchedule;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ClassRoomsController extends Controller
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
    public function index()
    {
        $grades      = Grade::all();
        $subjects    = Subject::all();
        $schedules   = RoomSchedule::all();
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();

        $classrooms = Classroom::select('id', 'title')->get();
        $classRoomDemo = Classroom::DemoClassRoom();
        $classRoomDemoID = Setting::DemoRoom();
        // dd($classRoomDemo);

        return view('admin.classroom.index', get_defined_vars());
    }

    public function get_classrooms(Request $request)
    {
        $classrooms = Classroom::all();
        $classrooms = ClassRoomsResource::collection($classrooms);

        return datatables($classrooms)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomCreateRequest $request)
    {
        $input      = $request->all();
        $userId     = $request->userId;
        $grade      = $request->grade_id;
        $user       = User::findOrFail($userId);
        // dd($request->request);
        $user->classrooms()->create($input);
        $classId    = Classroom::latest()->first();
        if (!empty($classId)) {
            $nextClass = +$classId->id;
        } else {
            $nextClass = 1;
        }
        if ($file = $request->file('cover')) {
            $this->saveImage($classId, $file);
        }
        Gradable::create(['grade_id' => $grade, 'gradable_id' => $nextClass, 'gradable_type' => 'App\Models\Classroom']);

        $classId->update(['is_draft' => 1]);

        return response()->json(['status' => true, 'message' => 'Create Successfully'], 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $class      = Classroom::findOrFail($id);
        $grades     = Grade::all();
        $subjects   = Subject::all();
        $schedules  = RoomSchedule::all();
        $grade_id   = $class->grade->grade_id;
        $instructors= User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();

        $page_tbl    = $request->page_tbl ?? null;

        return view('admin.classroom.edit', compact('class', 'grade_id', 'grades', 'subjects', 'schedules','instructors','page_tbl'));
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
            // 'grade_id'      => 'required',
            // 'subject_id'    => 'required',
            'room_scheduel' => 'required',
            'absence_times' => 'required',
            'description'   => 'required',

        ]);
        $class  = Classroom::findOrFail($id);
        $class->update([
            'title'         =>  $request->title,
            'description'   =>  $request->description,
            'subject_id'    =>  $request->subject_id,
            'absence_times' =>  $request->absence_times,
            'room_scheduel' =>  $request->room_scheduel,
            'setting_missed'=>  $request->setting_missed?1:0,
            'instructor_id' =>  $request->user_id,
        ]);

        if ($file = $request->file('cover')) {
            foreach ($class->photos as $photo) {
                $photo_id = $photo->id;
            }
            $fileName   = date('YmdHi').$file->getClientOriginalName();
            $img        = Image::make($file)->resize(375,200);
            $img->save('uploads/media/'.$fileName, 60);
            $photo      = Photo::findOrFail($photo_id);
            $photo->update([
                'path'=>$fileName,
            ]);
        }

        if ($grade_id = $request->grade_id) {
            $old_id = $class->grade->grade_id;
            Gradable::where('grade_id', $old_id)->where('gradable_id',$class->id)
                        ->update(['grade_id' => $grade_id]);
        }

        $page_tbl    = $request->page_tbl ?? null;

        return redirect()->route('admin.classrooms')->with(['message'=>"Updated Success",'alert-type'=>'success', 'page_tbl' => $page_tbl]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function set_demo(Request $request)
    {
        $request->validate([
            'demo_class_room_id'=>'required',
        ]);

        $setting = Setting::where('name','demo_room')->first();

        if($setting):
            $setting->update([
                'value' => $request->demo_class_room_id
            ]);
        else:
            Setting::create([
                'name'      =>  'demo_room',
                'main_name' =>  'Demo Room',
                'type'      =>  'rooms',
                'value'     => $request->demo_class_room_id
            ]);
        endif;

        return response()->json(['status' => true, 'message' => 'Create Successfully'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $class = Classroom::findOrFail($id);
        $class->delete();

        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }
}
