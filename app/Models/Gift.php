<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded=[];

    public function users(){
        return $this->belongsToMany(User::class,'redemptions')->withTimestamps();
    }
    public function favorites(){
        return $this->belongsToMany(User::class,'favorites')->withTimestamps();
    }


    public function scopeFilter($query,$req){
        return $query->when((isset($req['searchTerm']) && $req['searchTerm'] !=="null"),function($query) use ($req){
            $query->where( 'title', 'LIKE', '%' . $req['searchTerm'] . '%' );
        })->when((isset($req['status_filter'])&&$req['status_filter']!=="null"),function ($query) use($req){
            $query->where( 'status',$req['status_filter']);
        })->when((isset($req['name']) && $req['name'] != null),function($query) use ($req){
            $query->where('name','Like','%'.$req["name"].'%');
        });;
    }
}
