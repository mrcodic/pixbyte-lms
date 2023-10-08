<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function grades() {
        return $this->belongsToMany(Grade::class,'category_grades');
    }
    public function scopeFilter($query, $request){
        return $query->when((isset($request['grade_id']) && $request['grade_id'] != null),function($query) use ($request){
            $query->whereHas('grades',function ($q) use($request){
                $q->where('grades.id',$request['grade_id']);
            });
        })->when((isset($request['subcategory_id']) && $request['subcategory_id'] != null),function($query) use ($request){
            $query->whereHas('subCats',function ($q) use($request){
                $q->where('sub_categories.id',$request['subcategory_id']);
            } );
        });
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function subCats(){
        return $this->hasMany(SubCategory::class,'category_id','id');
    }

}
