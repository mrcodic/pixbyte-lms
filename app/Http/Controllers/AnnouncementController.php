<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Models\AnnouncementUser;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Notifications\SendAnnouncemetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('announcement.index');
    }
    public function get_announcement_data(Request $request){
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        $announcement = Announcement::where('user_id',$instructor_id)->filter($request)->get();
        $announcement= AnnouncementResource::collection($announcement);
        return datatables($announcement)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classrooms=Classroom::where('instructor_id',get_instructor())->select('id','title')->get();
        return view('announcement.create',compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnnouncementRequest $request)
    {
        $announcement=Announcement::create([
            "name"=>$request->title,
            "announcement_type"=>"classroom",
            "announcement_id"=>implode(',',$request->classroom_id),
            "desc"=>$request->desc,
            "user_id"=>get_instructor(),
        ]);

            $media= Media::where('model_id',0)->where('model_type','App\Models\Announcement')->get();

            foreach ($media as $m){
                $m->model_id=$announcement->id;
                $m->save();
            }
        $users=ClassroomStudent::where('classroom_id',$request->classroom_id)->pluck('user_id')->toArray();
        if($users){

        $usersAnnouncement=$announcement->users()->sync($users);
        \App\Jobs\AnnouncementJop::dispatch($users,$announcement,auth()->user());
        }
        return redirect()->route('announcement.index')->with(['message'=>"created Success",'alert-type'=>'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        $classrooms=Classroom::where('instructor_id',get_instructor())->select('id','title')->get();
        return view('announcement.edit',compact('classrooms','announcement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update([
            "name"=>$request->title,
            "announcement_id"=>implode(',',$request->classroom_id),
            "desc"=>$request->desc,
        ]);

            $media= Media::where('model_id',0)->where('model_type','App\Models\Announcement')->get();
            foreach ($media as $m) {
                $m->model_id = $announcement->id;
                $m->save();
            }
        $users=ClassroomStudent::where('classroom_id',$request->classroom_id)->pluck('user_id')->toArray();
        if($users){
        $usersAnnouncement=$announcement->users()->sync($users);
        }
        return redirect()->route('announcement.index')->with(['message'=>"update Success",'alert-type'=>'success']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
            AnnouncementUser::where('announcement_id',$announcement->id)->delete();
            $announcement->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function delete_selected(Request $request){
        AnnouncementUser::whereIn('announcement_id',$request->idds)->delete();
        Announcement::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }

    public function my_announcement()
    {
        $announcements=AnnouncementUser::with('announcement')->where('user_id',auth()->id())->orderBy('id','desc')->get();
        $this->markAllRead();
       return view('announcement.my_announcement',compact('announcements'));
    }
    public function markAllRead()
    {
        AnnouncementUser::where('user_id',auth()->id())->update(['read_at'=>now()]);
    }
    public function uploadMaterial(Request $request){
        if($request->hasFile('files')){
            $announcement = new Announcement();
            $announcement->id=0;
            $announcement->exists=true;
            $image=$announcement->addMediaFromRequest('files')->toMediaCollection('file');
        }
        return response()->json([
            'uploaded'=>1,
            'url'=>$image->getUrl(),
            "id"=>$image->id,
            'file_name'=>substr($image->name,0,30)
        ]);
    }
    public function deleteImage($id,Request $request){
        $media= Media::where('model_id',$request->id)->where('model_type','App\Models\Announcement')->where('collection_name','file')->first();
        $media->delete();
        return response()->json([
            'status'=>true,
        ],200);
    }

}
