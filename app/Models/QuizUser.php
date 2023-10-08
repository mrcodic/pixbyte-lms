<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUser extends Model
{
    protected  $table='user_quiezzes';
    use HasFactory;
    protected $guarded=[];
}
