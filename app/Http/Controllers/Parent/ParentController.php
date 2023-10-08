<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParentStudentResource;
use App\Http\Resources\StudentWorkResource;
use App\Models\Attendance;
use App\Models\ClassroomStudent;
use App\Models\CompleteLesson;
use App\Models\PointDetails;
use App\Models\Result;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Classroom;
use App\Models\ParentStudent;

class ParentController extends Controller
{
    public function memberShowRedirect() {
        $userId   = Auth::user()->name_id;
        $lower_id = Str::lower($userId);
        return redirect()->route('parent.dashboard', $lower_id);
    }
    public function get_mywork_parent_data(Request $request){

            $user=\auth('parent')->user()->user->id;
            $points=PointDetails::where('user_id',$user)->whereNotNull('model_id')->get();
        $rooms= ParentStudentResource::collection($points);
        return datatables($rooms)->make(true);
    }

        public function get_mywork_data(Request $request){

    }

    public function membershipShow($name_id) {

        $parent     = ParentStudent::where('name_id', $name_id)->firstOrFail();
        $data       = $parent->user;
        $date       = Carbon::parse($data->created_at)->diffForHumans();
        $complete=CompleteLesson::where('user_id',\auth()->user()->id)->get()->groupBy('classroom_id');
        $completed=0;
        foreach ($complete as $comp){
            $lesson=$comp[0]->room->lessons;

            $completeRoom=getProgress(count($comp),count($lesson));
            if($completeRoom==100){
                $completed=$completed+1;
            }
        }


        if($data->type === 3 || $data->type=== 4) {

            $myclassStudent = ClassroomStudent::where('user_id',$data->id)->pluck('classroom_id')->toArray();
            $myclassroom=Classroom::whereIn('id',$myclassStudent)->take(3)->get();

            return view('parent.student', compact('data','date','completed','myclassroom'));

        }

    }

}
