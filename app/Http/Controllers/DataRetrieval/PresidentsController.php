<?php

namespace Limbo\Http\Controllers\DataRetrieval;

use Limbo\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Handles getting data about presidents from app's DB
 *
 * @author William Kluge <klugewilliam@gmail.com>
 */
class PresidentsController extends Controller {

    /**
     * Gets information on all presidents from the DB's _presidents_ table, sorted by number in descending order
     *
     * @example "routes/web.php"  22 4 Adds a route _presidents_ to limbo, which will show president information from the DB
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function get_presidents() {
        $presidents = DB::table('presidents')->select(['num', 'fname', 'lname'])->orderBy('num', 'desc')->get();

        return View('presidents', compact('presidents'));
    }

}