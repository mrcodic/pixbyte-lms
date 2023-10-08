<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankCreateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $categories=$this->categories->map(function ($item){
            $subCat=$item->subCats->map(function ($i){
                 return ["id"=>$i->id,'name'=>$i->name,'questions'=>$i->questions,'selected'=>false];
            })->toArray();
            return ["id"=>$item->id,"name"=>$item->name,'selected'=>false,"subsub"=>$subCat];
        })->toArray();

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'selected'=>false,
            'subFilters'=>$categories
        ];
    }
}
