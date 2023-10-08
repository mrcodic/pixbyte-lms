<?php

namespace Database\Seeders;

use App\Models\Attendance;
use http\Client\Curl\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attendance::truncate();
        $users=\App\Models\User::whereHas('roles',function ($q){
            $q->where('id',3);
        })->get();
        foreach ($users as $user){
             Attendance::create(['student_id'=>$user->id]);
        }

    }
}
