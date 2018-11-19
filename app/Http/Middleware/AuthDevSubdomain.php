<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 11/15/18
 * Time: 1:30 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthDevSubdomain {

    public function handle($request, Closure $next) {
        if (config('app.env') != 'staging') {
            // If we aren't on staging, send the user wherever they wanted to go
            return $next($request);
        } else if ((Auth::user() && Auth::user()->type == 'admin') || ($request->is('login'))) {
            // (implied on staging) If the user is logged in as an admin, they can go wherever they want
            return $next($request);
        }

        if ($request->is('home')) {
            // User is logged in, but is not an admin
            // Or user directed themself to home, in which case fine, also tell them they need to be an admin
            return response('Access to this domain requires admin authorization.');
        } else {
            // User needs to login to access pages
            return redirect('login');
        }
    }

}