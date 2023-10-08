<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $activity = $this->model && $this->model_id ? @$this->model::find($this->model_id)->title :null;

        return [
            'title'     => @$this->name,
            'value'     => $this->value,
            'date'      => $this->created_at->diffForHumans(),
            'activity'  => $activity,
        ];
    }
}
