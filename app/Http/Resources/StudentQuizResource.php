<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentQuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $quizresult=$this->users()->where('student_id',request()->student_id)->first();
        $score=0;
         if($quizresult){
             $score=checkScore($quizresult->total_correct_answer,count($this->questions));
             $print_score = $quizresult->total_correct_answer=="0"? 0 : $score.'%';
             if($quizresult->total_correct_answer=="0"){
                 $quizPassed='--';

             }else{
                 $quizPassed=(int)checkScore($quizresult->total_correct_answer,count($this->questions)) >=(int)$this->score ?'Yes':'No';
             }

         }else{
             $quizPassed='--';
             $print_score = '--';
         }

         if($this->type==1){
            $type='quiz';
         }elseif($this->type==2){
            $type="Exam";
         }else{
            $type="Assignment";
         }
        return [
            "id"=>$this->id,
            "name"=>$this->title,
            "type"=>$type,
            "passed"=> $quizPassed,
            "score"=> $print_score,
            "num_retake"=>@$quizresult->num_retake ? @$quizresult->num_retake : '--',
            "enter"=>$quizresult?1:0
        ];
    }
}
