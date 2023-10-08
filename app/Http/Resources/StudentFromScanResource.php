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

class StudentFromScanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $relatedClass = null;
        $missed = null;
        $instructor_id = auth()->user()->instructor ? auth()->user()->instructor->instructor_id : auth()->id();

        $classRoom = ClassroomStudent::where('user_id', $this->id)->where('instructor_id', get_instructor())->get();
        if ($classRoom) {
            $relatedClass = Classroom::where('id', $classRoom->first()->classroom_id)->first();
        }

        $missed = Attendance::where('student_id', $this->id)->where('classroom_id', $relatedClass->id)->where('attendance_type','room')->where('instructor_id', get_instructor())->where('status', [0,2,3])->get();
        $block=\App\Models\ClassroomStudent::where('user_id',$this->id)->where('classroom_id',$relatedClass->id)->where('instructor_id',get_instructor())->first();

        return [
            "name_id" => $this->name_id,
            "phone"=>$this->student->phone,
            "ParentPhone"=>$this->parent->phone,
            "name" => $this->name,
            "email" => $this->email,
            "missed_room_name" => (isset($relatedClass)) ? (Room::whereIn('id', $missed->pluck('attendance_id')->toArray())->pluck('title')) ?? '--' : '--',
            "classrooms" => Classroom::whereIn('id', $classRoom->pluck('classroom_id')->toArray())->pluck('title'),
            "block"=>($block->block==1)?"Yes":"No"

        ];
    }
}
