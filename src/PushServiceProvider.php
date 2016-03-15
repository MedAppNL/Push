<?php
/**
 * Created by PhpStorm.
 * User: Tuupke
 * Date: 15/03/16
 * Time: 19:03
 */

namespace PharmIT\Push;

class PushServiceProvider
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
