<?php

namespace App\Http\Resources\Api;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\CompleteLesson;
use App\Models\RequestChange;
use App\Models\Room;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "name_id"=>$this->name_id,
            "name"=>$this->name,
            "phone"=>$this->phone,
            "student"=>$this->user,
            "email"=>$this->email,
        ];
    }
}
