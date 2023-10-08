<?php

namespace App\Http\Controllers;

class IdGenerator extends Controller
{
    public static function create($model, $trow, $length = 4, $prefix) {
        $data = $model::where('name_id','like', "%".$prefix."%")->orderBy('id','desc')->first();
        if(!$data) {
            $id_length = $length;
            $last_num = '';

        }else {
            $code = substr($data->$trow, strlen($prefix)+1);
            $actual_last_num = ($code/1)*1;
            $increment_last_num = $actual_last_num + 1;
            $last_num_length = strlen($increment_last_num);
            $id_length = $length - $last_num_length;
            $last_num = $increment_last_num;
        }

        $zeros = "";
        for($i=0;$i<$id_length;$i++){
            $zeros.="0";
        }

        return $prefix.'-'.$zeros.$last_num;

    }
}
