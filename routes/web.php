<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
|
*/

// Home page route
Route::get('/', 'ItemController@paginate_five');

// Page to update items (for admins and posters) and to view information on an item (everybody)
Route::get('/item/{id}', function ($id) {
    return \App\Http\Controllers\ItemController::get_item_data($id);
});

Route::put('update_item', ['as' => 'form_url', 'uses' => 'ItemController@update']);

Route::put('update_user', ['as' => 'form_url', 'uses' => 'Auth\UserController@update']);

Route::get('/user/{id}', ['middleware' => 'admin', function ($id) {
    return \App\Http\Controllers\Auth\UserController::edit($id);
}]);

// Generates the nessesary routes for authentication
Auth::routes();

// Homepage for users when they initially login
Route::get('/home', 'HomeController@index')->name('home');

// Admin dashboard
Route::get('admin', ['middleware' => 'admin', function () {
    return \App\Http\Controllers\ItemController::paginate_items_admin();
}]);

// Report item page
Route::get('report_item/{senario}','ItemController@add_item')->where('senario', '(lost)|(found)');

// Report item post url
Route::post('report_item', ['as' => 'form_url', 'uses' => 'ItemController@store']);

Route::post('claim_item', ['as' => 'form_url', 'uses' => 'ItemClaimController@store']);
