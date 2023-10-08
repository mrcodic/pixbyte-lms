<?php

namespace App\Http\Resources;

use App\Models\Classroom;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $classroom=Classroom::find(explode(',',$this->announcement_id))->pluck('title')->toArray();
        return [
            'title'=>$this->name,
            'id'=>$this->id,
            'classroom'=>@implode(',',$classroom),
            'created_at'=>$this->created_at->format('Y-m-d'),
            'desc'=>$this->desc,
        ];
    }
}
