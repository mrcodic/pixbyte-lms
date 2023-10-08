<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IdGenerator;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Requests\Admin\EditProfileRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\Admin\StudentResource;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
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
        $roles=Role::where('type',1)->select('id','name')->get();
        return view('admin.admins.index',get_defined_vars());
    }

    public function get_admins(Request $request){

        $students = Admin::filter($request)->orderBy('created_at','desc')->get();
        $students= AdminResource::collection($students);
        return datatables($students)->make(true);
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
    public function store(AdminRequest $request)
    {
        $data=$request->except('phone');
        $data['phone']='2215554';
        $user=Admin::create($data);

            $role=Role::findOrFail($request->role_id);
            $user->syncRoles($role);

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
        $admin=Admin::findOrFail($id);
        $roles=Role::where('type',1)->select('id','name')->get();
        return view('admin.admins.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, $id)
    {
        $admin=Admin::findOrFail($id);
        $admin->update($request->all());
        $role=Role::findOrFail($request->role_id);
        $admin->syncRoles($role);
        return redirect()->route('admins.index')->with(['success'=>'Update Success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {

        $admin->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function edit_profile(){
        return view('admin.admins.profile.edit');
    }
    public function update_profile(EditProfileRequest $request){
        $inputs=$request->except('password');
        if($request->has('password')){
          auth()->user()->update(['password'=>bcrypt($request->password)]);
        }
        auth()->user()->update($inputs);
        return  redirect()->route('profile.edit')->with(['success'=>'Updated Successfully']);
    }
}
