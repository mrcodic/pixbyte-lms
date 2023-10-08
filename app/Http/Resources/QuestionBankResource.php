<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankResource extends JsonResource
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
            "name"=>$this->name,
            "type"=>$this->type==0?'static':'dynamic',
            "created_at"=>$this->created_at->format('Y-m-d'),
            "question_num"=>$this->question_num??'--',
            "question_count"=>count($this->questions)??'--',
        ];
    }
}
