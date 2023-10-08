<?php

namespace App\Http\Resources;

use App\Models\CompleteLesson;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

class RoomShowResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

       $seconds= unlockafter($this->price ,$this->unlock_after,@$this->usedCoupon->last()->created_at,$this->created_at);
        $duration_hour=[];
        $duration_min=[];
        $lessons=$this->lessons()->orderBy('lesson_rooms.lesson_order','asc')->get()->map(function ($item,$key) use($duration_hour,$duration_min){
         $completed= CompleteLesson::where(['lesson_id'=>$item->id,'user_id'=>auth()->id(),'room_id'=>$this->id,'classroom_id'=>Session::get('classroom')])->get();

            return [
                "title"=>$item->title,
                "completed"=>count($completed)>0?true:false,
                "id"=>$item->id,
                'lesson_order'=>sprintf('%02d',$key+1),
                'duration'=>!$item->url_iframe ? duration(($item->getMedia('video')->first()->getPath())):$item->duration,
                "url_iframe"=>$item->url_iframe,
                'video'=>$item->hasMedia('video') ? $item->getMedia('video')->first()->getUrl():'',
                "active"=>true,
                'attachment'=>$this->hasMedia('attachment') ? $this->getMedia('attachment')->first()->getUrl():''
                ];
        })->toArray();
      foreach ($lessons as $lesson){
          $duration=$lesson['duration'];
          $hour=strtok($duration, 'h');
          array_push($duration_hour,$hour);
          $min=strtok(ltrim(strstr($duration, 'h'), 'h'),'m');
              array_push($duration_min,$min);
      }
      $hour=array_sum($duration_hour) * 60 * 60;
      $min=array_sum($duration_min)*60;
      $init=$hour+$min;
        $day = floor($init / 86400);
        $hours = floor(($init -($day*86400)) / 3600);
        $minutes = floor(($init / 60) % 60);


            $lesson=CompleteLesson::where(['user_id'=>auth()->id(),'room_id'=>$this->id,'classroom_id'=>Session::get('classroom')])->get();

       $progress= getProgress(count($lesson),count($this->lessons));
        $image='';
        if(auth()->user()->grade_id==1){
            $image=url('uploads/no-image/first-year.png');
        }elseif(auth()->user()->grade_id==2){
            $image=url('uploads/no-image/second-year.png');
        }else{
            $image=url('uploads/no-image/third-year.png');
        }
        return[
            "title"=>$this->title,
            "id"=>$this->id,
            "lessons_num"=>count($this->lessons),
            "duration"=>$hours.'h'.$minutes.'m',
            "unlock_after"=>$seconds,
            'profile_image'=>(!empty(auth()->user()->profile_image))? url('uploads/profile_images/'. auth()->user()->profile_image) : $image,
            "instructor_image"=>(!empty($this->user->profile_image))? url('uploads/profile_images/'. $this->user->profile_image) : url('uploads/no-image/profile.png'),
            "lessons"=>$lessons,
            "progress"=>$progress,
            "user_name"=>$this->user->name,
            "bio"=>$this->user->bio,
            "name_id"=>auth()->user()->name_id,
            "previous"=>url()->previous(),
            "classroom"=>Session::get('classroom'),
        ] ;
    }
}
