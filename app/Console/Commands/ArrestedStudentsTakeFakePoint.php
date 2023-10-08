<?php

namespace App\Console\Commands;

use App\Models\CompleteLesson;
use App\Models\PointDetails;
use App\Models\Room;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Console\Command;

class ArrestedStudentsTakeFakePoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'return:fakePoint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $students = Student::get();

        foreach ($students as $student) {

            $user = $student->user;

            $completedLessons = CompleteLesson::where('user_id', $user->id)->get();
            $completedRoom    = array_values(array_unique($completedLessons->pluck('room_id')->toArray()));

            $points = $user->pointDetails()->whereIn('name', ['Point Completed Room','Completed Room'])->get();

            $fakePoints = 0;
            if(count($points) > count($completedRoom) ):

                $fakePointsRow = $points->skip(count($completedRoom));

                foreach ($fakePointsRow as $point) :
                    $fakePoints += $point->value;
                    $point->delete();
                endforeach;

            endif;

            if (count($completedRoom) > 0) :

               $pointEnd = $user->pointDetails()->limit(count($completedRoom))->whereIn('name', ['Point Completed Room','Completed Room'])->get();

                foreach ($pointEnd as $key => $point) :
                    $point->update([
                        'name'    => Setting::where('name', 'point_completed_room')->first()->main_name,
                        'model'   => Room::class,
                        'model_id'=> $completedRoom[$key],
                    ]);
                endforeach;
            endif;

            $pointFinalValue = 0;
            foreach($user->pointDetails as $pointFinal ):
                $pointFinalValue += $pointFinal->value;
            endforeach;

            $student->update([
                'completed_lessons' => count($completedRoom),
                'exp' => $pointFinalValue,
            ]);
        }


        return Command::SUCCESS;
    }
}
