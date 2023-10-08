<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->insert([
            'name' => 'first grade'
        ]);
        DB::table('grades')->insert([
            'name' => 'socand grade'
        ]);
        DB::table('grades')->insert([
            'name' => 'third grade'
        ]);
    }
}
