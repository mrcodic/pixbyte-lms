<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /* define a user role */

        Gate::define('Instructor', function($user) {
            return $user->type == 2;
        });
        Gate::define('Student', function($user) {
            return $user->type == 3 || $user->type == 4 ;
        });
        Gate::define('Assistant', function($user) {
            return $user->whereNotHas('instructor') ;
        });


    }
}
