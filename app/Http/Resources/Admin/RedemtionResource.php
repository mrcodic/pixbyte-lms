<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RedemtionResource extends JsonResource
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
            "id"=>$this->id,
            "user_id"=>$this->user->name_id,
            "name"=>$this->user->name,
            "gift"=>$this->gift->name,
            "point"=>$this->gift->price,
            "created_at"=>$this->created_at->format('Y-m-d')
        ];
    }
}
