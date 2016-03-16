<?php

namespace PharmIT\Push;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class PushServiceProvider extends ServiceProvider
{

    protected $defer = true;

    public function register()
    {
        $this->app->singleton('Push', function () {
            return new PushServiceFactory();
        });
    }

    public function provides()
    {
        return array('Push');
    }

    public function boot()
    {
        if (!$this->app instanceof Application || !$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([__DIR__ . '/config/config.php' => config_path('push.php')]);
    }

}
