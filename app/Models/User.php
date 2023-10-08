<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles,HasApiTokens,Notifiable,AuthenticationLoggable;
    protected $guard = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name_id',
        'email',
        'password',
        'profile_image',
        'bio',
        'grade_id',
        'type',
        'force_logout',
    ];
    protected $appends = ['name'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name_id',
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name_id',
        'first_name',
        'email',
        'updated_at',
        'created_at',
    ];
    //instructor classroom
    public function classrooms() {
        return $this->hasMany(Classroom::class,'instructor_id');
    }
    public function student(){
        return $this->hasOne(Student::class,'user_id');
    }
    public function instructor(){
        return $this->hasOne(Instructor::class,'user_id');
    }
    public function studentOnlyInstructor(){
        return $this->hasMany(ClassroomStudent::class,'user_id')->where('instructor_id',get_instructor());

    }
    public function announcementes() {
        return $this->belongsToMany(Announcement::class,'announcement_users');
    }
    public function classroomStudent(){
        return $this->belongsToMany(Classroom::class,'classroom_students','user_id','classroom_id');
    }
    public function rooms() {
        return $this->hasMany(Room::class,'instructor_id','id');
    }
    public function photos() {
        return $this->morphMany(Photo::class, 'imageable');
    }
    public function grade() {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function quizes() {
        return $this->belongsToMany(Quiz::class, 'user_quiezzes','user_id','quizzes_id');
    }
    public function attendance() {
        return $this->hasOne(Attendance::class, 'student_id');
    }
    public function attendances() {
        return $this->hasMany(Attendance::class, 'student_id');
    }
    public function getNameAttribute()
    {

        return $this->first_name .' '. $this->last_name;

    }
    public function scopeFilter($query, $request){
        return $query->when((isset($request['grade_id']) && $request['grade_id'] != null),function($query) use ($request){
            $query->where('grade_id',$request['grade_id']);
        })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
                $query->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$request['name']."%")
                ->orWhere('name_id','Like','%'.$request['name']."%");
        })->when((isset($request['classroom_id']) && $request['classroom_id'] != null),function($query) use ($request){

            $query->whereHas('classroomStudent',function ($q) use($request){
              return $q->where('classroom_id',$request['classroom_id']);
            });

        })->when((isset($request['user_role']) && $request['user_role'] != null),function($query) use ($request){
            $query->whereHas('roles',function ($q) use($request){
                 $q->where('id',$request['user_role']);
            });
        });
    }
    public function scopeStudentFilter($query, $request){
//        dd($request->all());
        return $query->when((isset($request['classroom']) && $request['classroom'] != null),function($query) use ($request){
            $query->whereHas('classroomStudent',function ($q) use($request){
                $q->where('classroom_id',$request['classroom']);
            } );
        })->when((isset($request['status']) && $request['status'] != null),function($query) use ($request){
           if($request['status']==1){
               $query->whereHas('attendances',function ($q) use($request){
                   $q->where('status',1)->where('attendance_id',$request->room)->where('classroom_id',$request->classroom);
               } );
           }elseif ($request['status']=="4"){
               $query->whereHas('attendances',function ($q) use($request){
                   $q->where('status',4)->where('attendance_id',$request->room)->where('classroom_id',$request->classroom);
               } );
           }
           else{
               $query->whereHas('attendances',function ($q) use($request){
                   $q->whereIn('status',[0,2,3])->where('attendance_id',$request->room)->where('classroom_id',$request->classroom);
               } );
           }
        })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
            $query->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$request['name']."%")
                ->orWhere('name_id','Like','%'.$request['name']."%")
                ->whereHas('attendances',function ($q) use($request){
                 $q->where('attendance_id',$request->room)->where('classroom_id',$request->classroom);
             });
        });
    }
//    public function receivesBroadcastNotificationsOn()
//    {
//        return 'users.'.$this->id;
//    }

    public function device_tokens():MorphMany
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }
    public function parent(){
        return $this->hasOne(ParentStudent::class);
    }

    public function pointDetails() {
        return $this->hasMany(PointDetails::class);
    }

    public function gifts() {
        return $this->belongsToMany(Gift::class, 'redemptions');
    }
    public function classroom(){
        return $this->hasOne(ClassroomStudent::class,'user_id');
    }

    public function subscription(){
        return $this->hasOne(CouponUsed::class,'user_id')->where('coupon_used_type','subscription')->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);

    }


}
