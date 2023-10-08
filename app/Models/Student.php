<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Student extends Model
{
    use HasFactory ;
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    protected $casts=[
        'ip'=>'array'
    ];
}
