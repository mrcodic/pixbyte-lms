<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $missed=checkMissed($this->id,auth()->user()->user->id);
        return [
            "id"=>$this->id,
            "title"=>$this->title,
            "missed"=>$missed,
            "created_at"=>$this->created_at->format('Y M d'),
        ];
    }
}
