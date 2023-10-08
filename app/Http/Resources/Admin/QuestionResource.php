<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $questionBank=$this->questionBank->map(function ($item){
            return $item->name;
        })->toArray();

        return [
            "id"                =>  $this->id,
            'name'              =>  $this->name,
            'category'          =>  $this->category->name,
            'sub_category'      =>  $this->sub_category->name??'--',
            'questionBank'      =>  implode(',',$questionBank),
            'right_answer'      =>  $this->right_answer,
            'answers'           =>  $this->answers ,
            'question_status'   =>  $this->question_status ,
            'instructor'        =>  $this->user_id ? $this->user->name :null,
            'created_at'        =>  $this->created_at->format('Y-m-d') ,
        ];
    }
}
