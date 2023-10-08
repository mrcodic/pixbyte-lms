<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsed extends Model
{
    use HasFactory;
    protected $table='coupon_used';
    protected $guarded=[];
    public function room(){
        return $this->belongsTo(Room::class, 'coupon_used_id')->where('coupon_used_type','room');
    }
    public function quiz(){
        return $this->belongsTo(Room::class, 'coupon_used_id')->where('coupon_used_type','quiz');
    }
    public function coupon(){
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }public function student(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function scopeFilter($query, $request){
        return $query->when((isset($request['class_room_id']) && $request['class_room_id'] != null),function($query) use ($request){
            $query->whereHas('classroom',function ($q) use($request){
                $q->where('id',$request['class_room_id']);
            } );
        });
    }
}
