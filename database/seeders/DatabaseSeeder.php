<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
         $this->call([
            // AttendanceSeeder::class,
            // GradeSeeder::class,
//            settingSeeder::class,
            // RoleSeeder::class,
            AddRoleSeeder::class,
//             quizEditSeeder::class
//             UserQuizeesSeedr::class
//              fixCheckAttendanceSeeder::class
//            AddUserIdQuestionBankSeeder::class
         ]);
    }
}
