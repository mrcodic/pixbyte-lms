<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class ParentStudent extends Authenticatable
{
    use HasFactory,Notifiable,HasApiTokens,AuthenticationLoggable;

    protected $table = 'parents';

    protected $guard = "parent";

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function device_tokens():MorphMany
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }
    protected $hidden = [
        // 'password',
    ];

}
