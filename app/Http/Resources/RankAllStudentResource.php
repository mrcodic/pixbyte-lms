<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RankAllStudentResource extends JsonResource
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
            'length'=>"",
            'id'=>$this->user->id,
            'user_id'=>$this->user->name_id,
            'name'=>$this->user->name,
            'grade'=>$this->user->grade,
            'profile_image'=>$this->user->profile_image,
            'points'=>$this->exp ??0,
        ];
    }
}
