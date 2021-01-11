<?php


namespace EasyDispatch;


use Carbon\Laravel\ServiceProvider;

class EasyDispatchServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $configPath = __DIR__ . '/../config/easy_dispatch.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('easy_dispatch.php');
        } else {
            $publishPath = base_path('config/easy_dispatch.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }
}