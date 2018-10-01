<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use Illuminate\Support\Facades\Auth;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;

class ItemController extends Controller {

    public static function add_item() {
        return View('item_form');
    }

    public static function show_items_on_map() {
        $items = Item::all();
        return View('map', compact('items'));
    }

    public static function get_item_data($id) {
        $item = Item::find($id);
        return View('map', compact('item'));
    }

    /**
     * Create a new Item instance
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {

        $item = new Item;

        $item->description = $request->description;
        $item->notable_damage = $request->notable_damage;
        $item->environment_found = $request->environment_found;
        $item->position_found = new Point($request->pos_x, $request->pos_y);
        $item->position_radius =  $request->position_radius;
//        TODO Set position comment correctly if instead of selecting a predefined location, the admin clicks a building
        $item->position_comment = $request->position_comment;
        $item->finder_id = User::all()->where('email', $request->finder_email)->first()->id;
        $item->admin_id = Auth::id();

        $item->save();

        return redirect('/');
    }

}