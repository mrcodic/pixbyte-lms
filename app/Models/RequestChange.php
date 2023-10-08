<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestChange extends Model
{
    use HasFactory;

    protected $guarded=[];
    const STATUS_PENDING=0;
    const STATUS_ACCEPT=1;
    const STATUS_Reject=2;
    public function currentClass(){
        return $this->belongsTo(Classroom::class,'current_class');
    }
    public function anotherClass(){
        return $this->belongsTo(Classroom::class,'another_class');
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y');
    }


}
