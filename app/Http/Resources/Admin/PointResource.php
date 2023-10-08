<?php

namespace App\Http\Resources\Admin;

use App\Models\Quiz;
use App\Models\Room;
use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->model=='App\Models\Quiz'){
            $value=Quiz::find($this->model_id);
        }elseif($this->model=='App\Models\Room'){
            $value=Room::find($this->model_id);
        }else{
            $value='';
        }
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "value"=>$this->value,
            "point"=>@$value->title,
            "model_id"=>$this->model_id,
        ];
    }
}
