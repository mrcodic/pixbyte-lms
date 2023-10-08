<?php

namespace App\Http\Resources;

use App\Models\CompleteLesson;
use App\Models\Result;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RankStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $classroom=$this->classroom_id;
        $user=User::find($this->user_id);
        $data = CompleteLesson::where('classroom_id',$this->classroom_id)->where('user_id',$this->user_id)->get()
            ->groupBy('room_id');
//        $settingPointsComplete=Setting::where('type','=','points')->where('name','=','point_completed_room')->first()->value;
//        $settingPointsPassedQuiz=Setting::where('type','=','points')->where('name','=','point_passed_quiz')->first()->value;
//        $settingPointsFullMark=Setting::where('type','=','points')->where('name','=','full_mark_point_exam')->first()->value;
//        $settingPointsPassedExam=Setting::where('type','=','points')->where('name','=','pass_point_exam')->first()->value;
//
//        $points=0;
//        $roomComplete=0;
//        $examNum=0;
//        $quizNum=0;
//        foreach ($data as $comp){
//            $lesson=$comp[0]->room->lessons;
//            $completeRoom=getProgress(count($comp),count($lesson));
//            if($completeRoom==100){
//                $points=$points+(int)$settingPointsComplete;
//                $roomComplete=$roomComplete+1;
//            }
//        }
//        $exams=Result::with('quiz')->where('student_id',$this->user_id)->get();
//
//        foreach ($exams  as $exam ){
//            $fullmark=(int)checkScore($exam->total_correct_answer,$exam->quiz?count($exam->quiz->questions):0) ==(int)$exam->quiz->score?true:false;
//            if($exam->quiz->type=="1"){
//                $quizNum=$quizNum+1;
//                $points=$points+(int)$settingPointsPassedQuiz;
//            }else{
//                $examNum=$examNum+1;
//                if($fullmark){
//                    $points=$points+(int)$settingPointsFullMark;
//                }else{
//                    $points=$points+(int)$settingPointsPassedExam;
//                }
//            }
//        }
        return [
            'length'=>"",
          'id'=>$user->id,
          'name'=>$user->name,
          'grade'=>$user->grade,
          'profile_image'=>$user->profile_image,
          'points'=>$user->student->exp ??0,
        ];
    }
}
