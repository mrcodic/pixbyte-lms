<?php

namespace App\Http\Traits;


use App\Models\Attachment;
use App\Models\Photo;
use App\Models\PointDetails;
use App\Models\Setting;
use Intervention\Image\Facades\Image;

trait PointsTrait
{
    public function setPoint($user, $pointName, $model=null, $model_id=null)
    {
        $pointEarn = Setting::where('name', $pointName)->first();
        $modelNotFind = $model_id && $model? ($user->pointDetails->where('model', $model )->where('model_id', $model_id )->first() ? false : true) :true;

        if (in_array($user->type ,[3,4]) && $pointEarn && $modelNotFind) {

            $user->student->update([
                'exp' => $user->student->exp + $pointEarn->value
            ]);

            PointDetails::create([
                'user_id' => $user->id,
                'name'    => $pointEarn->main_name,
                'value'   => $pointEarn->value ,
                'model'   => $model,
                'model_id'=> $model_id,
            ]);

            return ['status'=>true, 'point'=>$pointEarn->value];

        }
        return ['status'=>false];
    }
}
