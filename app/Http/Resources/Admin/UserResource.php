<?php

namespace App\Http\Resources\Admin;

use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Setting;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // if ($this->type == 1) {
        //     $type = 'Admin';
        // } elseif ($this->type == 2) {
        //     $type = 'Instructor';
        // } elseif ($this->type == 3) {
        //     $type = 'StudentOnline';
        // } else {
        //     $type = "StudentOffline";
        // }


        return [
            "id" => $this->id,
            "name" => $this->name,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "grade_id" => $this->grade_id,
            "email" => $this->email,
            "name_id" => $this->name_id,
            "role" => $this->roles->first()->name ?? null,
            "created_at" => $this->created_at->format('d/m/Y'),

        ];
    }
}
