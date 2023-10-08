<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentWorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(isset($this['lessons'])){
            $type='Room';
            $title=$this['title'];

        }else{
            $type='Quiz';
            $title=$this['quiz']['title'];
            $quiz=Quiz::find($this['quiz']['id']);
            $passed=(int)checkScore($this['total_correct_answer'],count($quiz->questions))>=(int)$quiz->score?'Yes':'no';
        }

        return[
           'type'=>$type,
            'title'=>$title,
            'passed'=>(isset($passed))?$passed:'',
            'created_at'=>Carbon::parse($this['updated_at'])->format('Y-m-d')
        ];
    }
}
