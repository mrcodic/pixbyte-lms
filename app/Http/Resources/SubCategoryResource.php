<?php

namespace App\Http\Resources;

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
            'name'=>$this->name,
            'updated_at'=>$this->updated_at->format('d/m/Y'),
            'category_id'=>@$this->category->id,
            'category'=>$this->category->name??'--',
            "id"=>$this->id
        ];
    }
}
