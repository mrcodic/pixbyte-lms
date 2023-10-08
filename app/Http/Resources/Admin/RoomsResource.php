<?php

namespace App\Http\Resources\Admin;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomsResource extends JsonResource
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
            "instructor"    => $this->user->name,
            "lessons"       => $this->lessons->count(),
            "price"         => $this->price,
            "status"        => $this->status,
            "unlock_after"  => $this->unlock_after,
        ];
    }
}
