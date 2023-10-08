<?php

namespace App\Http\Resources\Api\Student;

use App\Models\ClassroomStudent;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $join_at= ClassroomStudent::where('classroom_id',$this->id)->where('user_id',auth()->id())->first() ? ClassroomStudent::where('classroom_id',$this->id)->where('user_id',auth()->id())->first()->created_at:'';
        return [
            "id"=>$this->id,
            "title"=>$this->title,
            "subject"=>$this->subject->name,
            "instructor"=>$this->user->name,
            "desc"=>$this->description,
            "instructor_image"=>url('uploads/profile_images/'.$this->user->profile_image),
            "image"=>url('uploads/media/'.$this->photos()->first()->path),
            "grade"=>$this?->grade?->grade->name,
            "created_at"=>$this->created_at->format('Y M d'),
            "join_at"=>$join_at?$join_at->format('Y M d'):'--'

        ];
    }
}
