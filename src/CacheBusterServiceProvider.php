<?php

namespace Typesaucer\CacheBuster;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
            return "<?= '/'. app('TypeSaucer\Cachebuster\Cachebuster')->fire($expression) ;?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('\Typesaucer\Cachebuster\Cachebuster', function()
        {
            return new Cachebuster;
        });
    }
}
