<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=\App\Models\Admin::create([
            'name'=>'super',
            'email'     =>'super_admin@app.com',
            'password'  =>'123456',
            'phone'     =>"01066273085"

        ]);
    }
}
