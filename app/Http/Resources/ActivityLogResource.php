<?php

namespace App\Http\Resources;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user=User::where(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%".$this['properties']['attributes']['student']."%")->first();
        return [
            "student"=>$this['properties']['attributes']['student'],
            "name_id"=>@$user->name_id,
            "instructor"=>$this['properties']['attributes']['take_action'],
            "room"=>$this['properties']['attributes']['title'],
            'classroom'=>Classroom::find($this['properties']['attributes']['classroom'])->title,
            'take_action'=>User::find($this->causer_id)->name,
            'desc'=>$this->description,
            'created_at'=>$this->created_at->format('Y-m-d h:i a')

        ];
    }
}
