<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IdGenerator;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Http\Resources\Admin\UserResource;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-users|create-users|update-users|delete-users', ['only' => ['index','show','export'],]);
        $this->middleware('permission:create-users', ['only' => ['create','store']]);
        $this->middleware('permission:update-users', ['only' => ['edit','update','parentUpdate']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grades=Grade::select('id','name')->get();
        $classrooms=Classroom::select('id','title')->get();
        $roles=Role::where('type',2)->select('id','name')->get();
        $users=User::where('type',2)->select('id','first_name')->get();
        return view('admin.users.index',get_defined_vars());
    }

    public function get_users(Request $request){
        $start = request('start');
        $limit = ((request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10);

        $request['name'] = $request->search['value'];

        $students = User::where('type',2)->filter($request)->orderBy('created_at','desc')->skip($start)->take($limit)->get();
        $students= UserResource::collection($students);
        return datatables($students)->setOffset($start)
        ->with([
            'recordsTotal'=>User::where('type',2)->count(),
            'recordsFiltered' => User::filter($request)->where('type',2)->count(),
            'start' => $start
        ])->make(true);
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
    public function store(UserRequest $request)
    {
        $data=$request->except('name_id','profile_image','password');
        $data['type']=2;
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

        if($user){
            Instructor::create([
                "user_id"=>$user->id,
                "instructor_id"=>$request->instructor_id,
            ]);

            $role = Role::findOrFail($request->role_id);
            $user->syncRoles($role);
        }
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

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
        $instructor=User::findOrFail($id);
        $grades=Grade::select('id','name')->get();
        $roles=Role::where('type',2)->select('id','name')->get();
        $instructors=User::where('type',2)->select('id','first_name')->get();

        return view('admin.users.edit',get_defined_vars());
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
        // dd($request->all());
        // dd($id);
        $user=User::findOrFail($id);
        $data=$request->except('profile_image','password');
        if ($request->file('profile_image')) {
            $file       = $request->file('profile_image');
            $fileName   = $user->name_id.date('YmdHi').$file->getClientOriginalName();
            $img = Image::make($file)->resize(108,108);
            $img->save('uploads/profile_images/'.$fileName, 60);
            $data['profile_image'] = $fileName;
        }
        if($request->password !=null){

            $data['password']=Hash::make($request->password);
        }
        $user->update($data);
        $user->instructor()->update([ "instructor_id"=>$request->instructor_id]);

        if($request->role_id):
            $role = Role::findOrFail($request->role_id);
            $user->syncRoles($role);
        endif;
        $role = Role::findOrFail($request->role_id);
        $user->syncRoles($role);

      return redirect()->route('users.index')->with(['success'=>'Created Success']);
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
}
