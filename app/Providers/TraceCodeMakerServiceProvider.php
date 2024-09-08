<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Plopster\TraceCodeMaker\TraceCodeMaker;

class TraceCodeMakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tracecodemaker', function ($app) {
            return new TraceCodeMaker();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
