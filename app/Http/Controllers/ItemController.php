<?php

namespace App\Http\Controllers;

use App\Item;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{
    /**
     * Returns the view to add items to the database
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function add_item($test)
    {
        $senario = $test;

        return View('item_report', compact('senario'));
    }

    public static function get_items()
    {
        return Item::where('hidden', false)->get();
    }

    public static function paginate_five()
    {
        $items = Item::where('hidden', false)->orderBy('updated_at', 'desc')->paginate(5);

        return View('welcome', compact('items'));
    }

    /**
     * Paginates items responsively based on the number of items requested
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function responsive_pagination(Request $request)
    {
        // Validates the data and redirects the user if they entered data incorrectly
        $validator = Validator::make($request->all(), [
            'n' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        $items = Item::where('hidden', false)->orderBy('updated_at', 'desc')->paginate($request['n']);
        $n = $request['n'];

        return View('responsive_pagination', compact('items', 'n'));
    }

    /**
     * Gets the data of one item and passed it to the map blade for display
     *
     * @param $id Integer ID of the item to get data for
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function get_item_data($id)
    {
        $item = Item::find($id);

        return View('item', compact('item'));
    }

    /**
     * Create a new Item instance
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // Validates the data and redirects the user if they entered data incorrectly
        $request->validate([
            'description' => 'required',
            'pos_x' => 'required|numeric',
            'pos_y' => 'required|numeric',
            'finder_email' => 'sometimes|email',
            'hidden' => 'sometimes|boolean',
        ]);

        $item = new Item;

        $item->description = $request->description;
        $item->notable_damage = $request->notable_damage;
        $item->environment_found = $request->environment_found;
        $item->position_found = new Point($request->pos_x, $request->pos_y);
        $item->position_radius = $request->position_radius;
        $item->position_comment = $request->position_comment;
        $item->finder_id = User::all()->where('email', $request->finder_email)->first()->id;
        $item->admin_id = Auth::id();
        $item->hidden = 0;
        $item->lost = $request->lost;
        // Adds the item to the DB and to elasticsearch (if elasticsearch is enabled)
        $item->save();

        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id ID of the item to be edited
     * @return View of the item to edit with filled data
     */
    public function edit($id)
    {
        $item = Item::find($id);

        return View::make('item')->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request to this function
     * @return \Redirect to page of the updated item
     */
    public function update(Request $request)
    {
        // Validates the data and redirects the user if they entered data incorrectly
        $request->validate([
            'description' => 'required',
            'position_found' => 'required|numeric',
            'position_radius' => 'sometimes|numeric',
            'finder_email' => 'sometimes|email',
            'hidden' => 'sometimes|boolean',
        ]);
        // store
        $item = Item::find($request->id);
        $item->description = $request->description;
        $item->notable_damage = $request->notable_damage;
        $item->environment_found = $request->environment_found;
        $item->position_found = new Point($request->pos_x, $request->pos_y);
        $item->position_radius = $request->position_radius;
        $item->position_comment = $request->position_comment;

        // Special case to allow admins to hide items. Will still be kept in the DB but won't be shown
        if (Auth::user()->type == 'admin') {
            if ($request->hidden == null) {
                $item->hidden = 0;
            } else {
                $item->hidden = $request->hidden;
            }
        }

        $item->save();

        return redirect('/item/'.$request->id);
    }
}