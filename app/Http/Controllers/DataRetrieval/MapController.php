<?php

namespace App\Http\Controllers\DataRetrieval;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 *
 *
 * @author William Kluge <klugewilliam@gmail.com>
 */
class MapController extends Controller {

    public static function get_all_items() {
        $items = DB::table('stuff')->selectRaw('id, description, item_condition, time_found, X(coordinate) AS x, Y(coordinate) AS y')->get();

        return View('map', compact('items'));
    }

    public static function get_item_data($id) {
        $itemdata = DB::table('stuff')->selectRaw('id, description, item_condition, time_found, X(coordinate) AS x, Y(coordinate) AS y')->where("id", $id)->get()->first();

        return View('map', compact('itemdata'));
    }

}