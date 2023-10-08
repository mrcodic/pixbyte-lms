<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $grades=$this->grades->map(function ($item){
            return $item->name;
        })->toArray();

        $subCategory=$this->subCats->map(function ($item){
            return $item->name;
        })->toArray();
        $subCategory_ids=$this->subCats->map(function ($item){
            return $item->id;
        })->toArray();
        $grade_ids=$this->grades->map(function ($item){
            return $item->id;
        })->toArray();
        return [
            'title'=>$this->name,
            'updated_at'=>$this->updated_at->format('d/m/Y'),
            'grades'=>implode(',',$grades) ,
            'subcategories'=>implode(',',$subCategory) ,
            'subcategory_ids'=>implode(',',$subCategory_ids) ,
            'grade_ids'=>implode(',',$grade_ids) ,
            "id"=>$this->id
        ];
    }
}
