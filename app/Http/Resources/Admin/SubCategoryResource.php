<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'name'          => $this->name,
            'instructor'    => $this->user_id ? $this->user->name :'--' ,
            'category_id'   => $this->category->id,
            'category'      => $this->category->name??'--',
            'updated_at'    => $this->updated_at->format('d/m/Y'),
        ];
    }
}
