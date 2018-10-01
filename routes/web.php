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
| TRY TO NOT MOVE ITEMS FROM THE LINES THEY ARE ON, phpDocumentor EXAMPLE LINKS TO THOSE LINES
*/

# Home page route
Route::get('/', function () {
    $items = \App\Http\Controllers\ItemController::get_items();
    return View('welcome', compact('items'));
});


# Route to see information on presidents
Route::get('/presidents', function () {
    return \App\Http\Controllers\DataRetrieval\PresidentsController::get_presidents();
});

# Shows a simple map
Route::get('/items', function () {
    return \App\Http\Controllers\ItemController::show_items_on_map();
});

# Route to see information on presidents
Route::get('/item/{id}', function ($id) {
    return \App\Http\Controllers\ItemController::get_item_data($id);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', ['middleware' => 'admin', function () {
    return \App\Http\Controllers\DataRetrieval\AdminController::admin_home();
}]);

Route::get('item_form', function () {
    return \App\Http\Controllers\ItemController::add_item();
});

Route::post('item_form', ['as' => 'form_url', 'uses' => 'ItemController@store']);

Route::get('map_base', function() {
    return View('map_base');
});
