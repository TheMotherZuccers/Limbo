<?php

namespace App\Providers;

use App\Repositories\EloquentItemRepository;
use App\Repositories\ItemRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ItemRepository::class, function () {
            return new EloquentItemRepository();
        });
    }

}
