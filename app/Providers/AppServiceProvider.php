<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WebService\WebServiceClientInterface;
use App\Services\WebService\FakeWebServiceAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(WebServiceClientInterface::class, FakeWebServiceAdapter::class);
    }
}
