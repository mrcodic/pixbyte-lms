<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class GiftResource extends JsonResource
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
            "id"            =>  $this->id,
            'name'          =>  $this->name,
            'image'         =>  $this->image,
            'price'         =>  $this->price,
            'count'         =>  $this->count,
            'status'        =>  $this->status ? 'published' : 'drafted',
            'redemptions'   =>  $this->users->count(),
            'created_at'    =>  $this->created_at->format('Y-m-d'),
        ];
    }
}
