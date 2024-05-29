<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PageRankProviderInterface::class, OpenPageRankProvider::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
