<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AuthLogResource;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog as Log;
use Yajra\DataTables\Facades\DataTables;

class AuthLogsController extends Controller
{

    public function __construct()    {
        $this->middleware('permission:read-students|create-students|update-students|delete-students', ['only' => ['index', 'show', 'export']]);
        $this->middleware('permission:create-students', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-students', ['only' => ['edit', 'update', 'parentUpdate']]);
        $this->middleware('permission:delete-students', ['only' => ['destroy', 'delete_device']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $logs = Log::all();

        return view('admin.login_logs.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_authenticate_logs(Request $request)
    {
        $start = request('start');
        $limit = ((request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10);

        $authenticate_log = Log::where('authenticatable_type', User::class);

        $authLogs = AuthLogResource::collection($authenticate_log->skip($start)->take($limit)->get());

        return DataTables::of($authLogs)->with([
            'recordsTotal'      =>  $authenticate_log->count(),
            'recordsFiltered'   =>  $authenticate_log->count(),
            'start'             =>  $start
        ])->make(true);
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

}
