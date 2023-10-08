<?php

namespace Database\Seeders;

use App\Models\PointDetails;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointEditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Student::all() as $key => $value) {
            $poinexp=PointDetails::where('user_id',$value->user_id)->sum('value');
            $value->update(['exp'=>$poinexp]);

        }
    }
}
