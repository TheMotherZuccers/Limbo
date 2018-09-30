<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 9/26/18
 * Time: 2:46 PM
 */

namespace Limbo\Http\Controllers\DataRetrieval;

use Limbo\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;


class StuffController extends Controller {

    public static function get_stuff()
    {
//        $pres = DB::select('select * from presidents WHERE id=1');
        $pres = DB::table('presidents')->where('id', 1)->first();

        $president = array(
            'fname'=>$pres->fname,
            'lname'=>$pres->lname,
            'dob'=>$pres->dob
        );

//        $president = 'test';

//        return compact($president);
//        return $president;
        return View('welcome', compact('president'));
//        return View::make("welcome")->with(array('presidents'=>$presidents));
    }

}