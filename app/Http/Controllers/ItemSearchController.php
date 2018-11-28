<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 2018-11-27
 * Time: 22:14
 */

namespace App\Http\Controllers;

use App\Repositories\ElasticsearchItemRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemSearchController
{
    /**
     * @var ElasticsearchItemRepository
     */
    private $elastic_search;

    public function __construct(ElasticsearchItemRepository $elasticsearchItemRepository)
    {
        $this->elastic_search = $elasticsearchItemRepository;
    }

    public function search_as_type(Request $request)
    {
        return response()->json($this->elastic_search->json_search(
            empty($request['q']) ? '' : $request['q']));
    }
}