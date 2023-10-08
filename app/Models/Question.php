<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Question extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $guarded=[];
    protected $casts=['answers'=>'array'];
    public function scopeFilter($query,$req){
        return $query->when((isset($req['searchTerm']) && $req['searchTerm'] !=="null"),function($query) use ($req){
            $query->where( 'title', 'LIKE', '%' . $req['searchTerm'] . '%' );
        })->when((isset($req['status_filter'])&&$req['status_filter']!=="null"),function ($query) use($req){
            $query->where( 'status',$req['status_filter']);
        })->when((isset($req['name']) && $req['name'] != null),function($query) use ($req){
            $query->where('name','Like','%'.$req["name"].'%');
        })->when((isset($req['search']['value']) && $req['search']['value'] !=="null"),function($query) use ($req){
            $query->where( 'name', 'LIKE', '%' . $req['search']['value'] . '%' );
        });
    }
    public function grade() {
        return $this->belongsTo(Grade::class,'grade_id');
    }
    public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function sub_category() {
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }
    public function questionBank(){
        return $this->belongsToMany(QuestionBank::class,'question_question_banks');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
