<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Classroom extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'instructor_id',
        'subject_id',
        'absence_times',
        'room_scheduel',
        'is_draft',
        'setting_missed'
    ];

    public function photos(){
        return $this->morphMany(Photo::class, 'imageable');
    }

    public function user() {
        return $this->belongsTo(User::class,'instructor_id');
    }

    public function grade() {
        return $this->morphOne(Gradable::class, 'gradable');
    }
    public function grades() {
        return $this->morphMany(Gradable::class, 'gradable');
    }

    public function schedule() {
        return $this->hasOne(RoomSchedule::class);
    }

    public function rooms() {
        return $this->belongsToMany(Room::class,'class_rooms')->withTimestamps();
    }
    public function subject() {
        return $this->belongsTo(Subject::class,'subject_id');
    }
    public function requests() {
        return $this->hasMany(RequestChange::class,'current_class')->where('student_id',auth()->id());
    }
    public function quizes() {
        return $this->hasMany(Quiz::class,'classroom_id');
    }
    public function coupon() {
        return $this->hasMany(Coupon::class,'classroom_id')->where('user_id',auth()->id());
    }
    public function students(){
        return $this->hasMany(ClassroomStudent::class,'classroom_id');

    }




    public function scopeDemoClassRoom()
    {
        // dd(Setting::DemoRoom());`
        $classroom = Setting::DemoRoom() ?Classroom::where('id' , Setting::DemoRoom())->first() : null;
        return  $classroom ?? null ;
    }

}
