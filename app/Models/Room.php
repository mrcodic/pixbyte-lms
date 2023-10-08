<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use \Spatie\MediaLibrary\InteractsWithMedia;

class Room extends Model implements HasMedia

{
    use InteractsWithMedia;
    use HasFactory;
//    use LogsActivity;
//    protected static $recordEvents = ['created','updated'];
//    public function getActivitylogOptions(): LogOptions
//    {
//        return LogOptions::defaults()
//            ->logOnly(['id','title', 'description','price','unlock_after','user.name'])->useLogName('Room');
//    }
//    public function getDescriptionForEvent(string $eventName): string
//    {
//        if($eventName=='created'){
//            $eventName='Added';
//        }
//        return " {$eventName} A Room ";
//    }
        public function user(){
            return $this->belongsTo(User::class, 'user_id');
        }

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'attachment_id',
        'room_order',
        'unlock_after',
        'status',
        'is_draft',
        'price',
        'pass_quiz'
    ];

    public function classroom() {
        return $this->belongsToMany(Classroom::class,'class_rooms')->withPivot('room_order')->withTimestamps();
    }
    public function instructor() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function lessons() {
        return $this->belongsToMany(Lesson::class,'lesson_rooms');
    }
    public function attachment(){
        return $this->belongsTo(Attachment::class, 'attachment_id');
    }
    public function usedCoupon(){//student
        return $this->hasMany(CouponUsed::class, 'coupon_used_id')->where('user_id',auth()->id())->where('coupon_used_type','room');
    }
    public function completedProgress(){
        return $this->hasMany(CompleteLesson::class, 'room_id')->where('user_id',auth()->id());
    }
    public function quizzes(){
        return $this->hasMany(Quiz::class, 'room_id')->with(['result'=>function ($q){
            $q->where('student_id',auth()->id());
        }]);
    }
    public function scopeFilter($query, $request){
        return $query->when((isset($request['class_room_id']) && $request['class_room_id'] != null),function($query) use ($request){
            $query->whereHas('classroom',function ($q) use($request){
                $q->where('id',$request['class_room_id']);
            } );
        })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
                $query->where('title','Like','%'.$request["name"].'%');
            });
    }
    public function scopeStudentFilter($query, $request){
        $classroomIds=ClassroomStudent::where('user_id',$request->student_id)->pluck('classroom_id')->toArray();
        return $query->when((isset($request['student_id']) && $request['student_id'] != null),function($query) use ($request,$classroomIds){
            $query->whereHas('classroom',function ($q) use($request,$classroomIds){
                $q->whereIn('id',$classroomIds);
            } );
        })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
            $query->where('title','Like','%'.$request["name"].'%');
        });
    }
    public function completed(){
       return $this->hasMany(CompleteLesson::class,'room_id');
    }
    public function Coupon(){//student
        return $this->hasMany(CouponUsed::class, 'coupon_used_id')->where('user_id',request()->student_id)->where('coupon_used_type','room');
    }


    public function scopeDemoRoom()
    {
        $room = Room::find(Setting::DemoRoom()) ?? null;
        return  $room ;
    }

}
