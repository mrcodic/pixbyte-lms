<?php

namespace App\Http\Resources\Admin;

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
        $grades         = $this->grades->map(fn($item)  => $item->name)->toArray();
        $subCategory    = $this->subCats->map(fn($item) => $item->name)->toArray();
        $subCategory_ids= $this->subCats->map(fn($item) => $item->id)->toArray();
        $grade_ids      = $this->grades->map(fn($item)  => $item->id)->toArray();

        return [
            "id"              => $this->id,
            'title'           => $this->name,
            'instructor'      => $this->user_id ? $this->user->name :'--' ,
            'grades'          => implode(',',$grades) ,
            'subcategories'   => implode(',',$subCategory) ,
            'subcategory_ids' => implode(',',$subCategory_ids) ,
            'grade_ids'       => implode(',',$grade_ids) ,
            'updated_at'      => $this->updated_at->format('d/m/Y'),
        ];
    }
}
