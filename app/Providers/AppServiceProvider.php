<?php

namespace App\Providers;

use ezavalishin\LaravelJsonApiPaginator\LaravelJsonApiPaginatorServiceProvider;
use Fruitcake\Cors\CorsServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\LaravelDeployer\LaravelDeployerServiceProvider;
use SwooleTW\Http\LumenServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->register(RedisServiceProvider::class);
        $this->app->register(CorsServiceProvider::class);

        $this->app->register(LaravelDeployerServiceProvider::class);

        $this->app->register(LaravelJsonApiPaginatorServiceProvider::class);
        $this->app->register(LumenServiceProvider::class);
    }
}
