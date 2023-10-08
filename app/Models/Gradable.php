<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gradable extends Model
{
    use HasFactory;

    protected $fillable = ['grade_id','gradable_id','gradable_type'];

    public function gradable() {
        return $this->morphTo();
    }

    public function grade() {
        return $this->belongsTo(Grade::class);
    }

}
