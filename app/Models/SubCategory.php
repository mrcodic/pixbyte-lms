<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function questions(){
        return $this->hasMany(Question::class,'subcategory_id');

    }
    public function scopeFilter($query, $request){
        return $query->when((isset($request['category_id']) && $request['category_id'] != null),function($query) use ($request){
            $query->where('category_id',$request['category_id']);

        });
    }
}
