<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityStudentResource;
use App\Http\Resources\RankAllStudentResource;
use App\Models\ClassroomStudent;
use App\Models\CompleteLesson;
use App\Models\Room;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Classroom;
use App\Models\PointDetails;

class StudentProfile extends Controller
{
    public function memberShowRedirect() {
        $userId = Auth::user()->name_id;
        $lower_id = Str::lower($userId);
        return redirect()->route('dashboard', $lower_id);
    }

    public function membershipShow($name_id) {


        $data       = User::where('name_id', $name_id)->firstOrFail();
        $date       = Carbon::parse($data->created_at)->diffForHumans();


        // select('login_at','logout_at')->groupBy('login_at','logout_at')->havingRaw('count("login_at") >= 1')->
            if($data->type === 3 || $data->type=== 4) {
                $myclassStudent=ClassroomStudent::where('user_id',$data->id)->pluck('classroom_id')->toArray();
                $myclassroom=Classroom::whereIn('id',$myclassStudent)->take(3)->get();
                $classroom=ClassroomStudent::where('user_id',$data->id)->first();
                $completed=0;
                if($classroom){
                $complete=CompleteLesson::where('user_id',get_instructor())->where('classroom_id',$classroom->classroom_id)->get()->groupBy('room_id');

                    foreach ($complete as $comp){
                        $lesson=$comp[0]->room->lessons;
                        $completeRoom=getProgress(count($comp),count($lesson));
                        if($completeRoom==100){
                            $completed=$completed+1;

                        }
                    }
                }

                return view('student', compact('data','date','completed','myclassroom'));
            }else {
                if(\auth()->user()->type==3){
                    $myclassroom=Classroom::where('instructor_id',$data->id)->whereHas('grade',function ($q){
                        $q->where('grade_id',\auth()->user()->grade->id);
                    })->take(3)->get();
                }else{
                    $myclassroom=Classroom::where('instructor_id',$data->id)->take(3)->get();
                }


                return view('instructor', compact('data','date','myclassroom'));
            }

    }

    public function getActivityData(Request $request)
    {
        // dd($request->student_id);
        $activities = PointDetails::where('user_id',$request->student_id)->orderBy('created_at', 'desc')->get();
        $activities = ActivityStudentResource::collection($activities);

        return datatables($activities)->make(true);
    }

    public function get_classrank_classes(Request $request)
    {
        return view('student_rank');
    }

    public function get_classrank_classes_data(Request $request)
    {
        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        $classess=Student::with('user')->orderBy('exp','desc')->skip($start)->take($limit)->get();
        $students=  RankAllStudentResource::collection($classess);
        return datatables($students)
            ->setOffset($start)->with(['recordsTotal'=>Student::get()->count(), "recordsFiltered" => Student::get()->count(),'start' => $start])
            ->make(true);
    }
    public function instructor_page(Request $request)
    {
        $instructors = User::where('type',2)->doesntHave('instructor');
        $instructorsCount = User::where('type',2)->doesntHave('instructor')->count();

        if($request->ajax()){
            if($request->search){
                $instructors=$instructors->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$request->search."%")->paginate(8);
            }else{
                $instructors= $instructors->paginate(8);
            }
            $html = '';
            foreach ($instructors as $instructor){
                    $photo=(!empty($instructor->profile_image))? url('uploads/profile_images/'. $instructor->profile_image) : url('uploads/no-image/no-profile-picture.png');;


                $html.='
                            <div class="uk-card uk-card-default uk-width-1-3@m" style="background: #FFFFFF;margin-left:16px">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <img class="uk-border-circle" width="40" height="40" src="'.$photo.'" alt="Avatar">
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom" style="color: #000000">'.$instructor->name.'</h3>
                                <p class="uk-text-meta uk-margin-remove-top" style="color: #000000"><time datetime="2016-04-01T19:00">'.$instructor->created_at->diffForHumans().'</time></p>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body" style="color: #000000">
                        <p>'.$instructor->bio??'---'.'</p>
                    </div>

                </div>


             ';

            }
            return $html;
        }else{
            $instructors=$instructors->paginate(8);
        }
       return view('instructor.instructorPage',get_defined_vars());
    }

}
