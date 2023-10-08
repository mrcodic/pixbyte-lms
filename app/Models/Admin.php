<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class Admin extends Authenticatable
{
 use HasRoles,AuthenticationLoggable;
    protected $guard = 'admin';

    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status'
    ];
    public function scopeFilter($query, $request){
        return $query->when((isset($request['user_role']) && $request['user_role'] != null),function($query) use ($request){
                    $query->whereHas('roles',function ($q) use($request){
                        return $q->where('id',$request['user_role']);
                    });
                })->when((isset($request['name']) && $request['name'] != null),function($query) use ($request){
                    $query->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$request['name']."%")
                    ->orWhere('name_id','Like','%'.$request['name']."%");
                });
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

}
