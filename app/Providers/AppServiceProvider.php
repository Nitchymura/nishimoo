<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {        
        Paginator::useBootstrap();

        Gate::define('admin', function($user){
            return $user->role_id == User::ADMIN_ROLE_ID;
        });

        if($this->app->environment('production')){
            URL::forceScheme('https');
        }
        // if (env('APP_ENV') === 'production') {
        //     URL::forceScheme('https');
        // }
    }


}
