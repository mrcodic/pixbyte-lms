<?php

namespace App\Console\Commands;

use App\Models\ParentStudent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class ParentPassReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parent:repass';

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
        $parents = ParentStudent::all();
        $pass = Str::random(10);

        foreach ($parents as $parent) {
            $parent->update([
                "password"  => Crypt::encrypt($pass)
            ]);
        }

        return $this->info('Password : '.$pass);
    }
}
