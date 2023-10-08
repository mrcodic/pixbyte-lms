<?php

namespace App\Http\Resources\Admin;

use App\Models\Grade;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassRoomsResource extends JsonResource
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
            "id"            => $this->id,
            "title"         => $this->title,
            "grade"         => $this?->grade?->grade?->name,
            "subject"       => $this->subject->name,
            "instructor"    => $this->user->name,
            "students"      => $this->students->count(),
            "demo"          => $this->id === Setting::DemoRoom() ? true :false,
        ];
    }
}
