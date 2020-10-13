<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Basecamp;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('HasTasks', function ($app) {
            return new Basecamp;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
