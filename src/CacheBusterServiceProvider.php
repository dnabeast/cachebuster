<?php

namespace DNABeast\CacheBuster;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use DNABeast\CacheBuster\CacheBuster;

class CacheBusterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('cachebuster', function($expression){
            return "<?= '/'. app('CacheBuster')->fire($expression) ;?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CacheBuster', function()
        {
            return new CacheBuster;
        });
    }
}
