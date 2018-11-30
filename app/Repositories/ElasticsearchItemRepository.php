<?php

namespace App\Repositories;

use App\Item;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ElasticsearchItemRepository implements ItemRepository
{
    private $search;

    public function __construct(Client $client)
    {
        $this->search = $client;
    }

    public function search(string $query = ""): Collection
    {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    public function json_search(string $query)
    {
        $items = $this->searchOnElasticsearch($query);

        $hits = array_column($items['hits']['hits'], '_source');

        $sources = array_map(function ($source) {
            return Item::find($source['id']);
        }, $hits);


        return $sources;
    }

    private function searchOnElasticsearch(string $query): array
    {
        $instance = new Item;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'query' => (! empty($query) ? [
                    'match_phrase_prefix' => [
                        'description' => [
                            'query' => $query,
                            'slop' => 10,
                        ],
                    ],
                ] : ['match_all' => ['boost' => 1],]),
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items): Collection
    {
        /**
         * The data comes in a structure like this:
         *
         * [
         *      'hits' => [
         *          'hits' => [
         *              [ '_source' => 1 ],
         *              [ '_source' => 2 ],
         *          ]
         *      ]
         * ]
         *
         * And we only care about the _source of the documents.
         */
        $hits = array_column($items['hits']['hits'], '_source');

        $sources = array_map(function ($source) {
            return Item::find($source['id']);
        }, $hits);

        // We have to convert the results array into Eloquent Models.
        return new Collection($sources);
    }
}