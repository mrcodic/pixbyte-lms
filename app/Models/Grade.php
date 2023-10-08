<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function categories() {
        return $this->belongsToMany(Category::class,'category_grades');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function studentsCount()
    {
        return $this->users()->whereIn('type',[3,4])->count();
    }

}
