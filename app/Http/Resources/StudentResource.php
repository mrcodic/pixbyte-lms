<?php

namespace App\Http\Resources;

use App\Http\Requests\StudentRequest;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\CompleteLesson;
use App\Models\RequestChange;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $relatedClass=null;
        $missed=null;
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id :auth()->id();

        $classRoom=ClassroomStudent::where('user_id',$this->id)->where('instructor_id',get_instructor() )->first();
           if($classRoom){
               $relatedClass=Classroom::where('id',$classRoom->classroom_id)->first();
           }

           if($relatedClass)
//           $missed=$relatedClass->rooms()->whereDate('created_at','>=',Carbon::make($classRoom->created_at))->count();

           $studentRequest=RequestChange::where("student_id",$this->id)->where('instructor_id',get_instructor())->where('status',0)->first();
           $complete=CompleteLesson::where('user_id',$this->id)->where('classroom_id',$relatedClass->id)->get()->groupBy('room_id');
//           dd($complete);
            $countComplete=0;
            foreach ($complete as $comp){
               $lesson=$comp[0]->room->lessons;
               $completeRoom=getProgress(count($comp),count($lesson));
               if($completeRoom==100){
                   $countComplete=$countComplete+1;
               }
            }
           $missed=Attendance::where('student_id',$this->id)->where('classroom_id',$relatedClass->id)->where('instructor_id',get_instructor() )->whereIn('status',[0,2,3])->count();
           $block=\App\Models\ClassroomStudent::where('user_id',$this->id)->where('classroom_id',$classRoom->classroom_id)->where('instructor_id',get_instructor())->first();
        $attendacne=Attendance::where('student_id',$this->id)->where('attendance_type','room')->where('instructor_id',get_instructor())->where('status',0)->pluck('attendance_id')->toArray();
        $rooms=Room::find($attendacne)->pluck('title')->toArray();
           return [
            "id"=>$this->id,
            "name"=>$this->name,
            "email"=>$this->email,
            "enroll_class"=>$classRoom->classroom->title??'---',
            "completed_room"=>$countComplete??'0',
            "missed_room"=>  (isset($relatedClass))?($missed)??'--':'0',
            "total_room"=> ($relatedClass) ? count($classRoom->classroom->rooms):0,
            "room"=> implode(' , ',$rooms) ,
            "name_id"=>$this->name_id,
            "phone"=>$this->student->phone??'---',
            "ip"=>$this->student->ip??'---',
            "created_at"=>$this->created_at->format('d/m/Y'),
            "grade"=>$this->grade->name,
            "request"=>($studentRequest)?true:false,
            "block"=>($block->block==1)?true:false
        ];
    }
}
