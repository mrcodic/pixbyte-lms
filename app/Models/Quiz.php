<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class Quiz extends Model
{
    use HasFactory;
    protected $table='quizzes';
    protected $guarded=[];
    public function questions(){
        return $this->belongsToMany(Question::class,'questions_quiezes','quiz_id','question_id');

    }
    public function room(){
        return $this->belongsTo(Room::class,'room_id');

    }
    public function classroom(){
        return $this->belongsTo(Classroom::class,'classroom_id');

    }
    public function instructor(){
        return $this->belongsTo(User::class,'user_id');

    }
    public function usedCoupon(){//student
        return $this->morphMany(CouponUsed::class, 'coupon_used')->where('user_id',auth()->id());
    }
    public function quizUser(){
        return $this->hasOne(Attendance::class,'attendance_id')->where('student_id',auth()->id());
    }
    public function result(){
        return $this->hasOne(Result::class,'quiz_id')->where('student_id',auth()->id());
    }
    public function users(){
        return $this->hasMany(Result::class,'quiz_id');
    }
    public function scopeFilter($query,$req){

        return $query->when((isset($req['searchTerm']) && $req['searchTerm'] !=="null"),function($query) use ($req){
            $query->where( 'title', 'LIKE', '%' . $req['searchTerm'] . '%' );
        })->when((isset($req['status_filter'])&&$req['status_filter']!=="null"),function ($query) use($req){
            $query->where( 'status',$req['status_filter']);

        })->when((isset($req['name']) && $req['name'] != null),function($query) use ($req){
            $query->where('title','Like','%'.$req["name"].'%');
        })->when((isset($req['type']) && $req['type'] != null),function($query) use ($req){
            $query->where('type',$req["type"]);
        });;
    }
    public function scopeStudentFilter($query, $request){
        $quizIds=QuizUser::where('user_id',$request->student_id)->pluck('quizzes_id')->toArray();
        return $query->when((isset($request['student_id']) && $request['student_id'] != null),function($query) use ($request,$quizIds){
            $query->whereIn('id',$quizIds);
        })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
            $query->where('title','Like','%'.$request["name"].'%');
        });
    }


    public function Coupon(){//student
        return $this->hasMany(Coupon::class, 'coupon_used_id');
    }
}
