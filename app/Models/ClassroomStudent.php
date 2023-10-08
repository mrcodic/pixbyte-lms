<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClassroomStudent extends Model
{
    use HasFactory;
    protected $table='classroom_students';
    protected $guarded=[];
    public function classroom(){
        return $this->belongsTo(Classroom::class,'classroom_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function scopeFilter($query, $request){
        return $query->when((isset($request['user_id']) && $request['user_id'] != null),function($query) use ($request){
            $query->where('user_id',$request['user_id']);
        })->when((isset($request['classroom_id']) && $request['classroom_id'] != null),function($query) use ($request){

            $query->where('classroom_id',$request['classroom_id']);
        });
    }
}
