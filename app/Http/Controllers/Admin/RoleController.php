<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-roles|create-roles|update-roles|delete-roles', ['only' => ['index','show','export'],]);
        $this->middleware('permission:create-roles', ['only' => ['create','store']]);
        $this->middleware('permission:update-roles', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-roles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles=Role::all();
        return  view('admin.roles_and_permissions.index',get_defined_vars());
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
    public function store(RoleRequest $request)
    {

        if($request->type=="1"){
            $role = Role::create(['name'=>$request['name'],'type'=>$request['type'],'guard_name'=>'admin']);
        }else{
            $role = Role::create(['name'=>$request['name'],'type'=>$request['type'],'guard_name'=>'web']);
        }
        $role->givePermissionTo(explode(',',$request['ids']));
        return response()->json(['status'=>true,'message'=>'Created Successfully'],200);

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
       $roles= Role::find($id);
     return view('admin.roles_and_permissions.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
       $role= Role::findOrFail($id);
       $role->update(['name'=>$request->name,'type'=>$request->type]);
       $role->syncPermissions($request['ids']);
       return  redirect()->route('roles.index')->with(['success'=>'update success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role=Role::findOrFail($id);
        $role->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function get_permissions($type)
    {
        if ($type == '1') {
            $permisstions = adminDbTablesPermissions();
            $arr = [];
            foreach ($permisstions as $pre) {
                $tblPermissions = Permission::where('name', 'LIKE', "%{$pre}%")
                    ->where('type',$type)
                    ->where('guard_name', 'admin')->pluck('name')->toArray();
                $arr[$pre] = $tblPermissions;
            }
        }elseif($type==3){
            $permisstions = studentOnlineDbTablesPermissions();
            $arr = [];
            foreach ($permisstions as $pre) {
                $tblPermissions = Permission::where('name', 'LIKE', "%{$pre}%")
                    ->where('type',$type)
                    ->where('guard_name', 'web')->pluck('name')->toArray();
                $arr[$pre] = $tblPermissions;
            }
        }elseif ($type==4){
            $permisstions = studentOfflineDbTablesPermissions();
            $arr = [];
            foreach ($permisstions as $pre) {
                $tblPermissions = Permission::where('name', 'LIKE', "%{$pre}%")
                    ->where('type',$type)
                    ->where('guard_name', 'web')->pluck('name')->toArray();
                $arr[$pre] = $tblPermissions;
            }
        }
        else {
            $permisstions = instructorDbTablesPermissions();
            $arr = [];
            foreach ($permisstions as $pre) {
                $tblPermissions = Permission::where('name', 'LIKE', "%{$pre}%")
                    ->where('type',$type)
                    ->where('guard_name', 'web')->pluck('name')->toArray();
                $arr[$pre] =$tblPermissions;
            }
        }
        return response()->json(['status'=>true,'data'=>$arr],200);
    }
    public function get_role($id){
        $roles = Role::where('type',$id)->select('id','name')->get();
        return response()->json(['status'=>true,'data'=>$roles],200);

    }


}
