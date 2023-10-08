<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassroomStudent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class quizEditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $attendances=Attendance::all();
          foreach ($attendances as $att){
              $classroom_student=ClassroomStudent::where('user_id',$att->student_id)->first();
              if($classroom_student){
                  if(($classroom_student->classroom_id)!==($att->classroom_id)){
                      $att->delete();
                  }
              }

          }
    }
}
