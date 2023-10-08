<?php

namespace Database\Seeders;

use App\Models\QuestionBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AddUserIdQuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $que=QuestionBank::all();
        foreach ($que as $q){
            $q->update(['user_id'=>2]);
        }
        $this->command->line('Updating instructor...');
        Log::info('Updating instructor...');

    }
}
