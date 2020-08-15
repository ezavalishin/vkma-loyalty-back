<?php

namespace App\Providers;

use App\Services\OffsetPaginator;
use Fruitcake\Cors\CorsServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\LaravelDeployer\LaravelDeployerServiceProvider;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

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

        $macro = function ($perPage = null, $columns = ['*'], array $options = []) {
            if (! $perPage) {
                $perPage = request('limit') ?? config('offset_pagination.per_page', 15);
            }
            $perPage = $perPage > config('offset_pagination.max_per_page') ? config('offset_pagination.max_per_page') : $perPage;

            // Limit results
            $this->skip(request('offset') ?? 0)
                ->limit($perPage);

            $total = $this->toBase()->getCountForPagination();

            return new OffsetPaginator($this->get($columns), $perPage, $total, $options);
        };

        // Register macros
        QueryBuilder::macro('offsetPaginate', $macro);
        EloquentBuilder::macro('offsetPaginate', $macro);
    }
}
