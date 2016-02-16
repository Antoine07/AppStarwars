<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ScoreProvider extends ServiceProvider
{

    protected $defer=true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->bind('App\Score\IScore', 'App\Score\ScoreModel');
    }

    /**
     * @description reference namespaces use service
     */
    public function provides()
    {
        return ['App\Score\IScore'];
    }
}
