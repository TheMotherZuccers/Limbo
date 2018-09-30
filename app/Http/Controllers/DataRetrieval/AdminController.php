<?php

namespace App\Http\Controllers\DataRetrieval;

use App\Http\Controllers\Controller;

/**
 *
 * @author William Kluge <klugewilliam@gmail.com>
 */
class AdminController extends Controller {

    public static function admin_home() {
        return View('admin');
    }

}