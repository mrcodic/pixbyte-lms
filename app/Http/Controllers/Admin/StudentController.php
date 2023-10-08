<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IdGenerator;
use App\Http\Requests\Admin\UserAddToRoomRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Admin\AuthLogResource;
use App\Http\Resources\Admin\PointResource;
use App\Http\Resources\Admin\StudentResource;
use App\Http\Resources\Admin\RankStudentResource;
use App\Http\Resources\StudentQuizResource;
use App\Http\Resources\StudentRoomResource;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Grade;
use App\Models\Instructor;
use App\Models\ParentStudent;
use App\Models\PointDetails;
use App\Models\Quiz;
use App\Models\Room;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog as Log;


class StudentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-students|create-students|update-students|delete-students', ['only' => ['index','show','export']]);
        $this->middleware('permission:create-students', ['only' => ['create','store']]);
        $this->middleware('permission:update-students', ['only' => ['edit','update','parentUpdate']]);
        $this->middleware('permission:delete-students', ['only' => ['destroy', 'delete_device']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studentsCount = User::whereIn('type', [3, 4])->count();

        $grades = Grade::select('id', 'name')->get();
        // dd($grades->first()->students->count());

        $instructors = User::where('type', 2)->whereHas('roles', fn($query) => $query->where('name', 'Instructor'))->get();
        $classrooms = Classroom::whereNot('id', Setting::DemoRoom())->select('id', 'title')->get();
        $roles = Role::whereIn('type', [3, 4])->select('id', 'name')->get();
        return view('admin.students.index', get_defined_vars());
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
    public function unBlock(Request $request){
        $classroomStudent=ClassroomStudent::where('user_id',$request->id)->first();
        $classroomStudent->update(['block'=>0]);
//       dd(Attendance::where(['student_id'=>$request->id,'instructor_id'=>auth()->id()])->get());
        Attendance::where(['student_id'=>$request->id,'instructor_id'=>$classroomStudent->instructor_id])->whereIn('status',[0,2,3])->update(['status'=>4,'created_at'=>Carbon::now()]) ;
        $text='<i><bold>Congratulations</bold></i>'. ' your account is active again continue learning. ';
        $textPhone='Congratulations '. ' your account is active again continue learning. ';
        $user=User::find($request->id);
        \App\Jobs\NotificationJop::dispatch('class','room',$text,$user,$redirect=null,$textPhone,'2');//students

        return response()->json(['status'=>true],200);

    }
    public function changeBlock(Request $request){
        $block=$request->block=="true"?'1':"0";
        $classroomStudent=ClassroomStudent::where('user_id',$request->student_id)->update(['block'=>$block]);
        return response()->json(['status'=>true],200);

    }
    public function get_students_users(Request $request)
    {
        $start = request('start');
        $limit = ((request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10);

        $request['name'] = $request->search['value'];
        $students = User::whereIn('type', [3, 4])->filter($request)->orderBy('created_at', 'desc')->skip($start)->take($limit)->get();
        $students = StudentResource::collection($students);

        return datatables($students)->setOffset($start)
        ->with([
            'recordsTotal'=>User::whereIn('type',[3,4])->count(),
            'recordsFiltered' => User::filter($request)->whereIn('type',[3,4])->count(),
            'start' => $start
        ])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticateLogs(Request $request)
    {
        $authenticate_log = Log::where('authenticatable_type', User::class)
            ->where('authenticatable_id', $request->user_id)->take(5)->get();

        $authLogs = AuthLogResource::collection($authenticate_log);

        return datatables($authLogs)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $role = Role::findOrFail($request->type);
        $request->type = $role->type;

        $data = $request->except('name_id', 'profile_image', 'password');
        //    dd($request->all());
        $name_id = $request->name_id ?? IdGenerator::create(new User, 'name_id', 5, 'mvs');

        $data['name_id'] = $name_id;
        $data['password'] = Hash::make($request->password);
        if ($request->file('profile_image')) {
            $file       = $request->file('profile_image');
            $fileName   = $name_id . date('YmdHi') . $file->getClientOriginalName();
            $img = Image::make($file);
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save('uploads/profile_images/'.$fileName, 60);
            $data['profile_image'] = $fileName;
        }

        $user = User::create($data);
        Student::create([
            "ip" => '',
            "user_id" => $user->id,
            "phone" => $request->phone
        ]);

        ParentStudent::create([
            "name_id"   => $name_id . '@parent',
            "user_id"   => $user->id,
            "name"      => $request->parent__name ?? $data['first_name'] . '\' parent',
            "password"  => Crypt::encrypt($request->parent__pass ?? (Str::random(10))),
            "email"     => $request->parent__email ,
            "phone"     => $request->parent__phone ,
        ]);

        $user->syncRoles($role);

        return response()->json(['status' => true, 'message' => 'create Successfully', 'data' => $user->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addClassRoomToStudent(UserAddToRoomRequest $request)
    {
        $classroom = Classroom::findOrFail($request->classroom);
        if($classroom):
            foreach (json_decode($request->user_ids) as $user_id) {
                $user = User::find((int)$user_id);
                $userClassrooms = $user ? $user->classroomStudent()->pluck('classroom_id')->toArray() :true;
                if(!in_array($request->classroom , $userClassrooms)):
                    ClassroomStudent::create([
                        "user_id"       => $user_id,
                        "classroom_id"  => $request->classroom,
                        "instructor_id" => $classroom->instructor_id,
                        "subject_id"    => $classroom->subject_id,
                    ]);
                    foreach ($classroom->rooms as $room){
                        Attendance::create(['attendance_id'=>$room->id,'attendance_type'=>'room','student_id'=>$user_id,'classroom_id'=>$classroom->id,'instructor_id'=>$classroom->user->id, 'status'=>4]);
                    }
                    foreach ($classroom->quizes as $quiz){
                        Attendance::create(['attendance_id'=>$quiz->id,'attendance_type'=>'quiz','student_id'=>$user_id,'classroom_id'=>$classroom->id,'instructor_id'=>$classroom->user->id]);
                    }
                endif;
            }
            return response()->json(['status' => true, 'message' => 'Created Successfully'], 200);
        endif;

        return response()->json(['status' => false, 'message' => 'Created Failed'], 404);
    }

    public function get_classroom_from_instructors(User $user)
    {
        $classroom = $user->classrooms;
        return response()->json(['status'=>true,'data'=>$classroom],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $student = User::findOrFail($id);
        $grades  = Grade::select('id', 'name')->get();
        $roles   = Role::whereIn('type',[3,4])->select('id', 'type', 'name')->get();
        $page    = $request->page ?? null;


        return view('admin.students.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if($request->type):
            $role = Role::findOrFail($request->type);
            $request->type = $role->type;
        endif;

        $data = $request->except('profile_image', 'password');
        if ($request->file('profile_image')) {
            $file       = $request->file('profile_image');
            $fileName   = $user->name_id . date('YmdHi') . $file->getClientOriginalName();
            $img = Image::make($file)->resize(108, 108);
            $img->save('uploads/profile_images/' . $fileName, 60);
            $data['profile_image'] = $fileName;
        }

        if (!empty($request->password)) {
            $request->validate([
                'password'=>['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => 'required|same:password',
            ]);
            $data['password'] = Hash::make($request->password);

        }else {
            $data['password'] = $user->password;
        }

        if ($request->phone != null) {
            $user->student()->update(['phone' => $request->phone]);
        }
        $user->update($data);

        $request->type ? $user->syncRoles($role) :null;

        if($request->name_id && $user->parent):
            $user->parent->update([
                'name_id' => $request->name_id.'@parent'
            ]);
        endif;
        $page = $request->page ?? null;

        return redirect()->route('students.index')->with(['success' => 'Created Success', 'page' => $page]);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function parentUpdate(Request $request, ParentStudent $parent)
    {

        // if ($request->password == Crypt::decrypt($parent->password)) {
        // }

        if ($request->email != $parent->email) {
            $request->validate([
                'email' => ['email', 'max:255', 'unique:parents'],
            ]);
        }

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => [ 'nullable', 'string', 'max:255'],
        ]);

        if (!empty($request->password)) {
            $request->validate([
                'password'=>['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => 'required|same:password',
            ]);
            $password = Crypt::encrypt($request->password);

        }else {
            $password = $parent->password;
        }

        $parent->update([
            'phone'     => $request->phone,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $password,
        ]);


        return redirect()->route('students.index')->with(['success' => 'Edit Success']);
    }


    public function parentPass(Request $request)
    {
        $parent = ParentStudent::where('name_id',$request->name_id)->first();
        if ($parent) {
            try {
                $password = Crypt::decrypt($parent->password);
                return response()->json(['data' => $password,'status' => true, 'message' => 'Created Successfully'], 200);
            } catch (DecryptException $e) {
                // $parent->update(['password'=>Crypt::encrypt($parent->password)]);
                // $password = Crypt::decrypt($parent->password);
                return response()->json(['status' => false, 'message' => 'Some thing is wrong'], 404);
            }
        }
        return response()->json(['status' => false, 'message' => 'Some thing is wrong'], 404);
    }

    public function deleteAllStudent(Request $request)
    {
        $request->validate([
            'idds' => ['required'],
        ]);

        foreach ($request->idds as $id) {
            $user = User::findOrFail($id);
            $user->delete();
        }
        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }
    public function getPoint($id,Request $request)
    {
        $point=PointDetails::where('user_id',$id)->paginate(5,['*'],'page',$request->page);
        $total=PointDetails::where('user_id',$id)->get();
        $data=PointResource::collection($point);
        return response()->json(['status' => true, 'message' => 'get Successfully','data'=>$data,'sum'=>$total->sum('value')], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }

    public function delete_device(Request $request)
    {
        $user = User::find($request->student_id);
        $student = Student::where('user_id', $request->student_id)->first();
        $arr = $student->ip;
        array_splice($arr, $request->index, 1);
        $student->update(['ip' => $arr]);
        $student->update(['device_status' => 1]);
        $user->update(['force_logout'=>1]);
        return response()->json(['status' => true, 'message' => 'deleted Successfully'], 200);
    }
    public function get_classrank(){
        $instructors=User::where('type',2)->doesntHave('instructor')->get();
        $classrooms=Classroom::where('is_draft',0)->whereHas('rooms')->select('id','title')->get();
        $totalStudent=ClassroomStudent::get()->groupBy('user_id')->count();
        return view('admin.students.student_rank',compact('totalStudent','instructors','classrooms'));
    }
    public function get_classrank_data(Request $request){
        $students=ClassroomStudent::filter($request)->get()->groupBy('user_id');
        $students=  RankStudentResource::collection($students);
        return datatables($students)->make(true);

    }
    public function forceLogout($id){
        User::find($id)->update(['force_logout'=>1]);
        User::find($id)->student()->update(['ip'=>[]]);
        return response()->json(['status' => true, 'message' => 'update Successfully'], 200);

    }

    public function forceLogout_selected(Request $request){
        $users=User::find($request->ids);
        foreach($users as $user){
           $user->update(['force_logout'=>1]);
           $user->student()->update(['ip'=>[]]);
        }
        return response()->json(['status' => true, 'message' => 'update Successfully'], 200);

    }
}
