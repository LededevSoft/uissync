<?php

namespace LebedevSoft\UisSync;
use LebedevSoft\UisSync\Console\Commands\UisSync;
use Illuminate\Support\ServiceProvider;

class UisSyncServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../database/migrations");
        if ($this->app->runningInConsole()) {
            $this->commands([
                UisSync::class
            ]);
        }
    }

    public function register()
    {
        //parent::register(); // TODO: Change the autogenerated stub
    }
}
