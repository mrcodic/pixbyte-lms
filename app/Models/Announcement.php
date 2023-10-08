<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Announcement extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $guarded=[];
    public function users() {
        return $this->belongsToMany(User::class,'announcement_users')->withTimestamps();
    }

    public function scopeFilter($query, $request){
//        return $query->when((isset($request['grade_id']) && $request['grade_id'] != null),function($query) use ($request){
//            $query->whereHas('grades',function ($q) use($request){
//                $q->where('grades.id',$request['grade_id']);
//            });
//        })->when((isset($request['subcategory_id']) && $request['subcategory_id'] != null),function($query) use ($request){
//            $query->whereHas('subCats',function ($q) use($request){
//                $q->where('sub_categories.id',$request['subcategory_id']);
//            } );
//        });
    }

}
