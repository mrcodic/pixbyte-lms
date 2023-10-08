<?php

namespace App\Http\Resources\Admin;

use App\Models\Classroom;
use App\Models\ClassroomStudent;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

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
            "name"      => $this->name,
            "name_id"   => $this->name_id,
            "pass"      => Crypt::decryptString($this->password),

        ];
    }
}
