<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Console\Command;

class FixPointDetailsQuiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'point:passQuiz';

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

            $completedQuiz = Result::where('student_id', $user->id)->get();
            $completedQuiz = array_values(array_unique($completedQuiz->pluck('quiz_id')->toArray()));

            $points = $user->pointDetails()->whereIn('name', ['Point Passed Quiz', 'Passed Quiz', 'Point Passed Exam', 'Passed Exam', 'Point Full Mark Exam', 'Full Mark Exam'])->get();

            if (count($points) > count($completedQuiz)) :

                $fakePointsRow = $points->skip(count($completedQuiz));

                foreach ($fakePointsRow as $point) :
                    $point->delete();
                endforeach;

            endif;

            // Point Passed Exam
            if (count($completedQuiz) > 0) :

                $pointEnd = $user->pointDetails()->limit(count($completedQuiz))->whereIn('name', ['Point Passed Quiz', 'Passed Quiz', 'Point Passed Exam', 'Passed Exam', 'Point Full Mark Exam', 'Full Mark Exam'])->get();

                foreach ($pointEnd as $key => $point) :
                    $keyPoint = in_array($point->name, ['Point Passed Quiz', 'Passed Quiz']) ? 'point_passed_quiz' :null;
                    $keyPoint = in_array($point->name, ['Point Passed Exam', 'Passed Exam']) ? 'pass_point_exam' :null;
                    $keyPoint = in_array($point->name, ['Point Full Mark Exam', 'Full Mark Exam']) ? 'full_mark_point_exam' :null;

                    $namePoint = Setting::where('name', $keyPoint)->first()->main_name;
                    $point->update([
                        'name'    => $namePoint,
                        'model'   => Quiz::class,
                        'model_id'=> $completedQuiz[$key],
                    ]);
                endforeach;
            endif;

            $pointFinalValue = 0;
            foreach ($user->pointDetails as $pointFinal) :
                $pointFinalValue += $pointFinal->value;
            endforeach;

            $student->update([
                'exp' => $pointFinalValue,
            ]);
        }


        return Command::SUCCESS;
    }
}
