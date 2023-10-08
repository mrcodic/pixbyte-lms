<?php

namespace App\Http\Resources\Admin;

use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Setting;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->type==1){
            $type='Admin';
        }elseif($this->type==2){
            $type='Instructor';
        }elseif ($this->type==3){
            $type='StudentOnline';
        }else{
           $type="StudentOffline";
        }
        $classRoom=ClassroomStudent::where('user_id',$this->id)->first();
if($classRoom){
    $block=\App\Models\ClassroomStudent::where('user_id',$this->id)->where('classroom_id',$classRoom->classroom_id)->where('instructor_id',$classRoom->instructor_id)->first();
}else{
    $block=null;
}

        if($this->parent){
            try {
                Crypt::decrypt($this->parent->password);
            } catch (DecryptException $e) {
                $this->parent->update(['password'=>Crypt::encrypt($this->parent->password)]);
            }
        }

        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "first_name"=>$this->first_name,
            "phone"=>$this->student->phone??'--',
            "last_name"=>$this->last_name,
            "grade_id"=>$this->grade_id,
            "email"=>$this->email,
            "name_id"=>$this->name_id,
            // "role"=>$this->roles->pluck('name')[0]??'--',
            "role"=>$type,
            "qrcode"=> (String)QrCode::encoding('UTF-8')->size(150)->generate($this->name_id),
            "ip"=>@$this->student->ip?json_encode($this->student->ip):json_encode([]),
            "created_at"=>$this->created_at->format('d/m/Y'),
            "parent"=>$this->parent,
            "parent_phone"=>$this->parent->phone,
            "logs"=>$this->previousLoginAt(),
            "classrooms"=>$this->classroomStudent()->whereNot('classroom_id', Setting::DemoRoom())->pluck('title'),
            "block"=>(@$block->block==1)?true:false

        ];
    }
}
