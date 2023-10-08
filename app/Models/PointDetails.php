<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointDetails extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user(){
        $this->belongsTo(User::class,'user_id');
    }

}
