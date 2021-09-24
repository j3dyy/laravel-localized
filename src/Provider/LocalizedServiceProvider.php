<?php

namespace J3dyy\LaravelLocalized\Provider;

use Illuminate\Support\ServiceProvider;
use J3dyy\LaravelLocalized\Console\MakeModelCommand;

class LocalizedServiceProvider extends ServiceProvider
{

    function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/localized.php' => config_path('localized.php')
        ]);

        if ($this->app->runningInConsole()){
            $this->commands([
                MakeModelCommand::class
            ]);
        }

    }

}
