<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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
            "name"=>$this->title,
            "id"=>$this->id,
            "instructor"=>$this->instructor->name??'--',
            "type"=>$this->type==1?'Quiz':(($this->type==2)?'Exam':'assignment'),
            "created_at"=>$this->created_at->format('Y-m-d'),
            "question_count"=>count($this->questions)??'--',
        ];
    }
}
