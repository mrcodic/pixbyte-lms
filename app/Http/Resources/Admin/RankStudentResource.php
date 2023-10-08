<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RankStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user=User::find($this[0]->user_id);

        return [
            'length'=>"",
            'id'=>$user->id,
            'user_id'=>$user->name_id,
            'name'=>$user->name,
            'grade'=>$user->grade,
            'profile_image'=>$user->profile_image,
            'points'=>$user->student->exp ??0,
            'classroom'=>$this[0]->classroom->title ??0,
        ];
    }
}
