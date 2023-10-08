<?php

namespace App\Http\Resources;

use App\Models\Attendance;
use http\Env\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(Auth::getDefaultDriver()=='admin'){
            $instructor_id = $request->instructor ;
        }else{
            $instructor_id = get_instructor();

        }
        $attendence=Attendance::where('attendance_id',$request->room)->where('classroom_id',$request->classroom)->where('student_id',$this->id)->where('instructor_id',$instructor_id)->first();
//        dd($attendence);
        return [
            "id"=>$this->id,
            "attendence_id"=>@$attendence->attendance_id,
            "attendence_type"=>@$attendence->attendence_type,
            "name_id"=>$this->name_id,
            "name"=>$this->name,
            "type"=>$attendence->type??0,
            "status"=>attendanceStatus(@$attendence->status),
            "reason"=>$attendence->comment??'---',
            "first_attendance"=>$attendence->first_attendance??0,
            "second_attendance"=>$attendence->second_attendance??0,
            "block"=>''
        ];
    }
}
