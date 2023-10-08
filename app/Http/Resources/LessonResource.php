<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $rooms=$this->rooms->map(function ($item){
            return $item->title;
        })->toArray();

        $order_id='';

        if($room_id=request('room_id')){
            $order_id=$this->rooms()->where('room_id',$room_id)->where('lesson_id',$this->id)->withPivot('lesson_order')->first()->pivot->lesson_order;
        }
        return [
            'title'=>$this->title,
            'updated_at'=>$this->updated_at->format('d/m/Y'),
            'room'=>implode(',',$rooms) ,
            'readingOrder'=> $order_id,
            "id"=>$this->id,
            "is_draft"=>$this->is_draft
        ];
    }
}
