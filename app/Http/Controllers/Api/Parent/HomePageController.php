<?php

namespace App\Http\Controllers\Api\Parent;

use App\Enums\Http;
use App\Helpers\MessageResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\NotificationResource;
use App\Http\Resources\Api\RoomAttendanceResource;
use App\Http\Resources\Api\RoomResource;
use App\Models\Attendance;
use App\Models\ParentStudent;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HomePageController extends Controller
{
    public function get_notifications_parent(Request $request){
           $notifications =auth()->user();
           if($request->has('unread') && $request->unread){
            $notifications=$notifications->unreadNotifications();
           }else{
            $notifications=$notifications->notifications();
           }

           $notifications= NotificationResource::collection($notifications->paginate($request->per_page??10));
           return new MessageResponse(
            message: 'Get Notification Data',
            code: Http::OK,
            body: [
                'notifications' => $notifications,
                "pagination"=>getPagination($notifications),

            ]
        );
       }
       public function markAllRead(Request $request){
        $notification = auth()->user()->notifications;
        if ($notification) {
            $notification->markAsRead();
        }
        return new MessageResponse(
            message: 'notification read success',
            code: Http::OK,
        );
      }

    public function homePage() {
        $student=auth()->user()->user;
        $student_id = $student->id;
        $parent = ParentStudent::where('user_id', $student_id)->first();
        $parent_name = $parent->name;
        $student_name=$student->name;
        $grade=$student->grade->name;
        $roomAvailable=Attendance::where('student_id',$student->id)->count();
        $roomsMissed=Attendance::where('student_id',$student->id)->whereIn('status',[0,2,3])->orderBy('id','desc')->take(2)->get();
        $roomsRecent=Attendance::where('student_id',$student->id)->where('status',1)->orderBy('id','desc')->take(2)->get();
        $roomAbsence=Attendance::where('student_id',$student->id)->whereIn('status',[0,2,3])->count();

        $block=$student->classroomStudent()->where('block',1)->count();
        $examPercentage=[];
        $values=[];
        foreach(Result::where('student_id',$student->id)->whereHas('quiz',function($q){
            $q->where('type','!=',3);
        })->get() as $key => $exam){
            if($exam->total_correct_answer){
                $mainExam=Quiz::find($exam->quiz_id);
                if($mainExam){
                    $degree=($exam->total_correct_answer / count($mainExam->questions))*100;
                    $examPercentage[]=floor($degree);
                    $values[]=$key + 1;
                }
            }
        }
        $data=[
            "parent_name"=>$parent_name,
            "student_name"=>$student_name,
            "grade"=>$grade,
            "absence_room"=>$roomAbsence,
            "available_room"=>$roomAvailable,
            "missed_or_recent_rooms"=>RoomAttendanceResource::collection($roomAbsence>0?$roomsMissed:$roomsRecent)->resolve(),
            "chart"=>['result'=>$examPercentage,'x'=>$values],
            'block'=>$block>0?true:false
        ];

        return new MessageResponse(
            message: 'Get homepage Data',
            code: Http::OK,
            body: [
                'data' => $data,
            ]
        );

    }
}
