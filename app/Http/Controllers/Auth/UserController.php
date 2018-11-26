<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public static function paginate_five()
    {
        $items = User::all()->paginate(5);

        return View('welcome', compact('items'));
    }

    public static function edit($id)
    {
        $user = User::find($id);

        return View('auth/user', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);

        // Special case to allow admins to hide items. Will still be kept in the DB but won't be shown
        if (Auth::user()->type == 'admin') {
            $user->type = $request->type;
        }

        $user->save();

        return redirect('/user/'.$request->id);
    }
}