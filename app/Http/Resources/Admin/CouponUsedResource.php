<?php

namespace App\Http\Resources\Admin;

use App\Models\Quiz;
use App\Models\Room;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponUsedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $usage = $this->room_id ? $this->room->title : ( $this->quiz_id ? $this->quiz->title : null);
        $usage = null;
        if($this->couponUsed):
            $usedIn = false;
            // $this->couponUsed ? dd($this->couponUsed->coupon_used_type) :null;
            if($this->couponUsed->coupon_used_type == 'quiz'):
                $usedIn = Quiz::find($this->couponUsed->coupon_used_id);
                $usedIn = $usedIn ? $usedIn->title :null;
            elseif($this->couponUsed->coupon_used_type == 'room'):
                $usedIn = Room::find($this->couponUsed->coupon_used_id);
                $usedIn = $usedIn ? $usedIn->title :null;
            endif;
            
            $usage = $usedIn;
        endif;

        return [
            "id"=>$this->id,
            'code'=>$this->code,
            'price'=>$this->price,
            'created_date'=>$this->created_at->format('Y-m-d'),
            'used_date'=> $this->couponUsed?$this->couponUsed->created_at->format('Y-m-d'):'not-used',
            "student"=>@$this->couponUsed->student->name??'---',
            "usage"=> $usage ?? '---',
            "instructor"=>$this->instructor->name
        ];
    }
}
