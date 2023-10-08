<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Quiz;
use App\Models\QuizUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserQuizeesSeedr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances=Attendance::where('attendance_type','quiz')->get();
        foreach ($attendances as $att) {
            $quiz=Quiz::find($att->attendance_id);
            $user=User::find($att->student_id);
            if(!empty($quiz) && !empty($user)){
            QuizUser::firstOrCreate(['quizzes_id'=>$att->attendance_id,'user_id'=>$att->student_id]);
            }
        }

    }
}
