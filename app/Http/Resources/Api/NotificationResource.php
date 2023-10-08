<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data=strip_tags($this->data['text_phone']);
        return [
            "id"=>$this->id,
            "text"=>$this?->data['text_phone']??'',
            'date'=>$this->created_at->diffForHumans(),
            'read'=>!empty($this->read_at)?true:false,
            'type'=>$this->data['case'],
            "name"=>$this?->data['room_name']??'',
            'title'=>$this->data['case']=='4'?"Announcement 📣":''
        ];
    }
}
