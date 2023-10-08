<?php

namespace App\Http\Resources;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\Room;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        switch ($this->type){
            case "2":
                $room=Room::whereIn('id',explode(',',$this->room_id))->get()->map(function ($item){
                    return $item->title;
                })->toArray();
                $value=implode(',',$room);
                break;

            case "3":
                $classroom=Classroom::whereIn('id',explode(',',$this->classroom_id))->pluck('title')->toArray();
                $value=(count($classroom)>0)?implode(',',$classroom):'--';
                break;

            case "4":

                $grade=Grade::whereIn('id',explode(',',$this->grade_id))->pluck('name')->toArray();
               $value=(count($grade)>0)?implode(',',$grade):'--';
                break;
            case "5":
                $quiz=Quiz::whereIn('id',explode(',',$this->quiz_id))->pluck('title')->toArray();
                $value=(count($quiz)>0)?implode(',',$quiz):'--';
                break;
            case "6":
                $classroom=Classroom::whereIn('id',explode(',',$this->classroom_id))->pluck('title')->toArray();
                $value=(count($classroom)>0)?implode(',',$classroom):'--';
                    break;

        }
        if($this->status==1){
            if($this->couponUsed){
                $usage_type=$this->couponUsed->coupon_used_type;
                if($this->couponUsed->coupon_used_type=='room'){
                    $usage=Room::find($this->couponUsed->coupon_used_id)->title??'Room Deleted';
                }else{
                    $usage=Quiz::find($this->couponUsed->coupon_used_id)->title??'Quiz Deleted';

                }
            }

        }

        return[
            "id"=>$this->id,
            "code"=>$this->code,
            "type"=>couponType($this->type),
            "value"=>$value,
            'expired'=>count($this->used).'/'.$this->num_used,
            'price'=>$this->price??'--',
            'user'=>$this->student->name_id??'--',
            'updated_at'=>$this->created_at->format('d/m/Y'),
            'checkedIn'=>@($this->status==1)?$this->updated_at->format('d/m/Y'):'--',
            'usage'=>$usage??'--',
            'usage_type'=>$usage_type??'--',
            'num_used'=>$this->num_used

        ];
    }
}
