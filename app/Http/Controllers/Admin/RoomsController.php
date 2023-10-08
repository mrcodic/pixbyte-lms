<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomCreateRequest;
use App\Http\Resources\Admin\RoomsResource;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\SendNotificationTrait;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Room;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RoomsController extends Controller
{

    use HelperTrait, SendNotificationTrait;

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
        $class = $id ? Classroom::findOrFail($id) :null;
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();
        // $classRooms = Classroom::select('id','title')->get()    ;
        // $classRoomDemo = Room::DemoRoom();

        return view('admin.classroom.rooms.index', get_defined_vars());
    }


    public function get_rooms(Request $request, ClassRoom $class=null)
    {
        $rooms = $class ? $class->rooms : Room::all();
        $rooms = RoomsResource::collection($rooms);

        return datatables($rooms)->make(true);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomCreateRequest $request)
    {

        // dd($request->class_room_ids);
        if ($request->user_id) :
            $roomCreate = Room::create([
                'user_id'       =>  $request->user_id,
                'title'         =>  $request->title,
                'description'   =>  $request->description,
                "unlock_after"  =>  $request->unlock_after,
                'room_order'    =>  $request->room_order ?? 0,
                'price'         =>  $request->price
            ]);

            if ($request->materialIds) :
                foreach (json_decode($request->materialIds) as $materialId) {
                    $media = Media::findOrFail($materialId);
                    $media->update(['model_id' => $roomCreate->id]);
                }
            endif;


            if($request->class_room_ids) :
                foreach(json_decode($request->class_room_ids) as $classroom_id):
                    $classRoom = Classroom::findOrFail($classroom_id);

                    $max = DB::table('class_rooms')->where('classroom_id', $classroom_id)->selectRaw('Min(room_order) as max');
                    $room = [$roomCreate->id => ['room_order' => $max->first()->max - 1]];

                    $classRoom = Classroom::find($classroom_id);
                    $classRoom->rooms()->syncWithoutDetaching($room);

                    $users = ClassroomStudent::where('classroom_id', $classRoom->id)->pluck('user_id')->toArray();

                    // activity()
                    //     ->performedOn($roomCreate)
                    //     ->causedBy($user->id)
                    //     ->performedOn($classRooms)
                    //     ->withProperties(['attributes' => ["id"=>$roomCreate->id,'title'=>$roomCreate->title,'user.name'=>$user->name,'classroom'=>$request->class_room_id]])
                    //     ->log('added room');


                    if ($users) {
                        $getUsers = User::whereIn('id', $users)->get();
                        $redirect = '/room/' . $roomCreate->id;
                        $this->sendSingleUserNotificationInQueue(
                            'New room has been added ' . '<bold><i>' . $roomCreate->title . '</i></bold>',
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
                    }
                endforeach;


            endif;

            if ($roomCreate) :
                $roomCreate->update(['is_draft' => 1]);
                return response()->json(['status' => true, 'message' => 'Created Successfully'], 200);
            else :
                return response()->json(['status' => false, 'message' => 'Some thing is wrong'], 404);
            endif;
        else :
            return response()->json(['status' => false, 'message' => 'Some thing is wrong'], 404);
        endif;
    }


    public function uploadMaterial(Request $request)
    {
        if ($request->hasFile('file')) {
            $room = new Room();
            $room->id = 0;
            $room->exists = true;
            $file = $room->addMediaFromRequest('file')->toMediaCollection('attachment');

            return response()->json([
                'message' => 'done',
                'uploaded' => 1,
                'url' => $file->getUrl(),
                "id" => $file->id,
                'file_name' => substr($file->name, 0, 30)
            ]);
        }

        return response()->json([
            'message' => 'false',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function classroomFromInstructors(Request $request)
    {
        if ($request->ajax()) {

            $cities = Classroom::select('id','title as text')
                        ->where('title', 'LIKE',  '%' . trim($request->search). '%')
                        ->where('instructor_id', 'LIKE',  '%' . trim($request->instructor). '%')
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
        $room=Room::with('attachment')->findOrFail($id);
        $classRooms=Classroom::select('id','title')->get();
        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();

        $page_tbl = $request->page_tbl ?? null;

        return view('admin.classroom.rooms.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        // $media= Media::where('model_id',0)->where('model_type','App\Models\Room')->get();
        // foreach ($media as $m){
        //     $m->model_id=$room->id;
        //     $m->save();
        // }

        $page_tbl = $request->page_tbl ?? null;

        $room->update([
            'user_id'       =>  $request->user_id,
            'title'         =>  $request->title,
            'description'   =>  $request->description,
            "unlock_after"  =>  $request->unlock_after,
            'room_order'    =>  $request->room_order ?? 0,
            'price'         =>  $request->price
        ]);

        $media= Media::where('model_id',0)->where('model_type','room')->get();
        foreach ($media as $m){
            $m->model_id=$room->id;
            $m->save();
        }

        $room->classroom()->sync($request->class_room_id);

        $classRooms = $room->classroom;

        foreach ($classRooms as $classRoom) :
            $users = ClassroomStudent::where('classroom_id',$classRoom->id)->pluck('user_id')->toArray();
            foreach ($users as $user) :
                foreach ($classRoom->rooms as $roomFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id]);
                }
                foreach ($classRoom->quizes as $quizFromClass){
                    Attendance::firstOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->user->id]);
                }
            endforeach;
        endforeach;

        // if ($request->materialIds) :
        //     foreach (json_decode($request->materialIds) as $materialId) {
        //         $media = Media::findOrFail($materialId);
        //         $media->update(['model_id' => $room->id]);
        //     }
        // endif;


        if ($room) :
            $room->update(['is_draft' => 1]);
            return redirect()->back()->with(['message'=>"updated Success",'alert-type'=>'success','page_tbl' => $page_tbl]);
        else :
            return redirect()->back()->with(['message'=>"Some thing is wrong",'alert-type'=>'error','page_tbl' => $page_tbl]);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $class = Room::findOrFail($id);
        $class->delete();

        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }
}
