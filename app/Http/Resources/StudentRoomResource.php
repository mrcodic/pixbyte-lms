<?php

namespace App\Http\Resources;

use App\Models\Attendance;
use App\Models\CompleteLesson;
use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $coupns=$this->Coupon->pluck('coupon_id')->toArray();
        $coupon=Coupon::whereIn('id',$coupns)->get()->map(function($ite){
            return $ite->code;
        })->toArray();
       $complete= CompleteLesson::where('room_id',$this->id)->where('user_id',$request->student_id)->first();
        $attendance=Attendance::where(['student_id'=>$request->student_id,'attendance_id'=>$this->id])->first();
       $order_id=$this->classroom()->where('classroom_id',User::find($request->student_id)->classroom->classroom_id)->where('room_id',$this->id)->withPivot('room_order')?->first()?->pivot->room_order;
        $lastCoupon= $this->Coupon->last();
       return [
            "id"=>$this->id,
            "attendance_id"=>$attendance->id,
            "attendance_status"=>$attendance->status,
            "name"=>$this->title,
            "status_val"=>attendanceStatus(@$attendance->status),
            "join"=>$lastCoupon|| count($this->Coupon)>0?(isset($lastCoupon->created_at))?$lastCoupon->created_at->format("Y-m-d"):'--':'No',
            "coupon"=>(count($coupon)>0)?implode(',',$coupon):(count($this->completed)>0 || count($this->Coupon)>0?"Free":"--"),
            "completed"=>getProgress(count($this->completed),count($this->lessons))==100?'Yes':'No',
            "button_coupon"=>@$this->Coupon->last()->id,
            "status"=>@$this->Coupon->last()->id,
            'readingOrder'=> $order_id,
            "end_date"=>isset($lastCoupon->created_at) ?Carbon::parse($lastCoupon->created_at)->addDay($this->unlock_after)->format('Y m d'):'--'
        ];
    }
}
