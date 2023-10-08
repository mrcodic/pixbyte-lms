<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
             if(request('class_room_id')){
                 $classRoom=$this->classroom()->where('classroom_id',request('class_room_id'))->get()->map(function ($item){
                     return $item->title;
                 })->toArray();
                 $classRoomIds=$this->classroom()->where('classroom_id',request('class_room_id'))->get()->map(function ($item){
                     return $item->id;
                 })->toArray();
             }else{
                 $classRoom=$this->classroom->map(function ($item){
                     return $item->title;
                 })->toArray();
                 $classRoomIds=$this->classroom->map(function ($item){
                     return $item->id;
                 })->toArray();
             }
       $order_id='';
       if($class_room_id=request('class_room_id')){
          $order_id=$this->classroom()->where('classroom_id',$class_room_id)->where('room_id',$this->id)->withPivot('room_order')->first()->pivot->room_order;
          $draft=$this->classroom()->where('classroom_id',$class_room_id)->where('room_id',$this->id)->withPivot('is_draft')->first()->pivot->is_draft;
        }else{
          $draft=$this->is_draft;
       }
        return [
            'title'=>$this->title,
            'created_at'=>$this->updated_at->format('d/m/Y'),
            'class_room'=> implode(',',$classRoom),
            'class_room_ids'=> implode(',',$classRoomIds),
            'readingOrder'=> $order_id,
            'price'=> $this->price??'--',
            "id"=>$this->id,
            "is_draft"=>(int)$draft
        ];
    }
}
