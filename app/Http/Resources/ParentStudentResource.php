<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentStudentResource extends JsonResource
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
            $type='Quiz';
        }elseif($this->model=='App\Models\Room'){
            $value=Room::find($this->model_id);
            $type='Room';
        }else{
            $value='';
            $type='';

        }
        return[
            'type'=>$type,
            'title'=>$value,
            'passed'=>$this->name,
            'created_at'=>Carbon::parse($this['created_at'])->format('Y-m-d')
        ];
    }
}
