<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class settingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Setting::truncate();
        $arr=[
            // [
            //     "main_name"=>'Point Full Mark Exam',
            //     "name"=>'full_mark_point_exam',
            //     'type'=>'points',
            //     'value'=>'100'
            // ],
            // [
            //     "main_name"=>'Point Passed Exam',
            //     "name"=>'pass_point_exam',
            //     'type'=>'points',
            //     'value'=>'50'
            // ],
            // [
            //     "main_name"=>'Point Passed Quiz',
            //     "name"=>'point_passed_quiz',
            //     'type'=>'points',
            //     'value'=>'10'
            // ],
            // [
            //     "main_name"=>'Point Completed Room',
            //     "name"=>'point_completed_room',
            //     'type'=>'points',
            //     'value'=>'20'
            // ],
            // [
            //     "main_name"=>'Point First Login',
            //     "name"=>'point_first_login',
            //     'type'=>'points',
            //     'value'=>'10'
            // ],
            // [
            //     "main_name"=>'Number Device Login',
            //     "name"=>'num_device_login',
            //     'type'=>'site',
            //     'value'=>'4'
            // ],
            // [
            //     "main_name"=>'Date Accept Redemptions',
            //     "name"=>'date_accept_redemptions',
            //     'type'=>'store',
            //     'value'=>'2023-04-04 11:08:53'
            // ],
            [
                "main_name"=>'Point Assignment',
                "name"=>'point_assignment',
                'type'=>'points',
                'value'=>'5'
            ],
        ];
        foreach ($arr as $a){
            Setting::firstOrCreate(['main_name'=>$a['main_name'],'name'=>$a['name'],'type'=>$a['type'],'value'=>$a['value']]);
        }
    }
}
