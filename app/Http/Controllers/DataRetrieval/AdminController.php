<?php

namespace Limbo\Http\Controllers\DataRetrieval;

use Limbo\Http\Controllers\Controller;

/**
 *
 * @author William Kluge <klugewilliam@gmail.com>
 */
class AdminController extends Controller {

    public static function admin_home() {
        return View('admin');
    }

}