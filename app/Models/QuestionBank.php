<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function questions(){
        return $this->belongsToMany(Question::class,'question_question_banks');
    }
    public function quizzes(){
        return $this->hasMany(Quiz::class,'question_bank_id');
    }
    public function setSubcatIdsAttribute($val)
    {
       return $this->attributes['subcatIds']=implode(',',$val);
    }
    public function getSubcatIdsAttribute()
    {
       return  explode(',',$this->attributes['subcatIds']);
    }
    public function scopeFilter($query,$req){

        return $query->when((isset($req['searchTerm']) && $req['searchTerm'] !=="null"),function($query) use ($req){
            $query->where( 'name', 'LIKE', '%' . $req['searchTerm'] . '%' );
        })->when((isset($req['status_filter'])&&$req['status_filter']!=="null"),function ($query) use($req){
            $query->where( 'status',$req['status_filter']);

        })->when((isset($req['name']) && $req['name'] != null),function($query) use ($req){
            $query->where('name','Like','%'.$req["name"].'%');
        });;
    }
    public function scopeQuestion($query,$req){
        return $query->when((isset($req['searchTerm']) && $req['searchTerm'] !=="null"),function($query) use ($req){
            $query->where( 'name', 'LIKE', '%' . $req['searchTerm'] . '%' );
        })->when((isset($req['status_filter'])&&$req['status_filter']!=="null"),function ($query) use($req){
            $query->where( 'status',$req['status_filter']);

        })
            ->paginate($req['per_page']);
    }
}
