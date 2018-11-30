<?php

namespace App\Providers;

use App\Item;
use App\Repositories\ItemRepository;
use App\Repositories\ElasticsearchItemRepository;
use App\Search\LaravelElasticsearchItemObserver;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
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
        Item::observe(LaravelElasticsearchItemObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ItemRepository::class, function($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            //if (!config('services.search.enabled')) {
            //    return new EloquentItemRepository();
            //}

            return new ElasticsearchItemRepository(
                $app->make(Client::class)
            );
        });
        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }

}
