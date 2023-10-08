<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonsResource extends JsonResource
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
            "instructor"    => $this->user->first_name.' '. $this->user->last_name,
            "video"         => $this->url_iframe,
            "duration"      => $this->duration,
            "description"   => $this->description,
            "status"        => $this->status,
        ];
    }
}
