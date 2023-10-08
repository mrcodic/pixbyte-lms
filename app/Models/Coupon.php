<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $casts=[
        'date_from'=>'date',
        'date_to'=>'date',
    ];
    public function classroom(){
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
    public function couponUsed(){
        return $this->hasOne(CouponUsed::class, 'coupon_id');
    }
    public function used(){
        return $this->hasMany(CouponUsed::class, 'coupon_id');
    }
    public function grade(){
        return $this->belongsTo(Grade::class, 'grade_id');
    }
    public function quiz(){
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
    public function room(){
        return $this->belongsTo(Room::class, 'room_id');

    }
    public function instructor(){
        return $this->belongsTo(User::class, 'instructor_id');

    }
    public function student(){
        return $this->belongsTo(User::class, 'student_id');

    }
    public function scopeFilter($query, $request){
        $couponsUsedArray=   CouponUsed::pluck('coupon_id')->toArray();

        return $query->when((isset($request['class_room_id_filter']) && $request['class_room_id_filter'] != null),function($query) use ($request){
            $query->whereHas('classroom',function ($q) use($request){
                $q->where('id',$request['class_room_id_filter']);
            } );
        })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
            $query->Where('code','Like','%'.$request['name']."%");
        })->when((isset($request['room_id_filter']) && $request['room_id_filter'] != null),function($query) use ($request){
            $query->whereHas('room',function ($q) use($request){
                $q->where('rooms.id',$request['room_id_filter']);
            } );
        })->when((isset($request['grade_id_filter']) && $request['grade_id_filter'] != null),function($query) use ($request){
            $query->whereHas('grade',function ($q) use($request){
                $q->where('id',$request['grade_id_filter']);
            } );
        })->when((isset($request['couponUsed']) && $request['couponUsed'] != null),function($query) use ($request,$couponsUsedArray){
            if ($request['couponUsed']==1){
                $query->whereIn('id',$couponsUsedArray);
            }else{
                $query->doesntHave('couponUsed');
            }
        });
    }
    public function setRoomIdAttribute($value)
    {
        if($value){
            $this->attributes['room_id'] = implode(',',$value);
        }

    }
    public function setClassroomIdAttribute($value)
    {
        if($value) {
            $this->attributes['classroom_id'] = implode(',', $value);
        }
    }
    public function setGradeIdAttribute($value)
    {

        if($value) {
            $this->attributes['grade_id'] = implode(',', $value);
        }
    }
    public function setQuizIdAttribute($value)
    {
        if($value) {
            $this->attributes['quiz_id'] = implode(',', $value);
        }
    }
}
