<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Lesson extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'display_video',
        'url_iframe',
        'is_draft',
        'duration'
    ];
    public function rooms() {
        return $this->belongsToMany(Room::class,'lesson_rooms');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
//    public function completeLesson(){
//        return $this->hansMany(CompleteLesson::class, 'lesson_id');
//    }
//    public function completed(){
//
//        return $this->hasMany(CompleteLesson::class, 'lesson_id')->where('')->where('user_id', auth()->id());
//
//    }
    public function scopeFilter($query, $request){
        return $query->when((isset($request['room_id']) && $request['room_id'] != null),function($query) use ($request){
            $query->whereHas('rooms',function ($q) use($request){
                $q->where('rooms.id',$request['room_id']);
            } );
        })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
            $query->where('title','Like','%'.$request["name"].'%');
        });;
    }
}
