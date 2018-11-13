<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;

class ItemController extends Controller {

    /**
     * Returns the view to add items to the database
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function add_item($test) {
        $senario = $test;
        return View('item_report', compact('senario'));
    }

    /**
     * Gets data for all items and shows them on the map
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function show_items_on_map() {
        $items = Item::all();
        return View('item', compact('items'));
    }

    public static function get_items() {
        return Item::where('hidden', false)->get();
    }

    /**
     * Gets the data of one item and passed it to the map blade for display
     *
     * @param $id Integer ID of the item to get data for
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function get_item_data($id) {
        $item = Item::find($id);
        return View('item', compact('item'));
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return
     */
    public function edit($id)
    {
        $item = Item::find($id);

        return View::make('item')->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return
     */
    public function update(Request $request)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
//        $rules = array(
//            'name'       => 'required',
//            'email'      => 'required|email',
//            'nerd_level' => 'required|numeric'
//        );
//        $validator = Validator::make(Input::all(), $rules);
//
//        // process the login
//        if ($validator->fails()) {
//            return Redirect::to('nerds/' . $id . '/edit')
//                ->withErrors($validator)
//                ->withInput(Input::except('password'));
//        } else {
            // store
            $item = Item::find($request->id);
            $item->description = $request->description;
            $item->notable_damage = $request->notable_damage;
            $item->environment_found = $request->environment_found;
            $item->position_found = new Point($request->pos_x, $request->pos_y);
            $item->position_radius =  $request->position_radius;
            $item->position_comment = $request->position_comment;

            // Special case to allow admins to hide items. Will still be kept in the DB but won't be shown
            if (Auth::user()->type == 'admin') {
                if ($request->hidden == null){
                    $item->hidden = 0;
                } else{
                    $item->hidden = $request->hidden;
                }
            }

            $item->save();

        return redirect('/item/' . $request->id);
    }

}