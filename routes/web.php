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
    return \Limbo\Http\Controllers\DataRetrieval\StuffController::get_stuff();
});


# Route to see information on presidents
Route::get('/presidents', function () {
    return \Limbo\Http\Controllers\DataRetrieval\PresidentsController::get_presidents();
});

# Shows a simple map
Route::get('/map', function () {
    return \Limbo\Http\Controllers\DataRetrieval\MapController::get_all_items();
});

# Route to see information on presidents
Route::get('/map/{id}', function ($id) {
    return \Limbo\Http\Controllers\DataRetrieval\MapController::get_item_data($id);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin', ['middleware' => 'admin', function () {
    return \Limbo\Http\Controllers\DataRetrieval\AdminController::admin_home();
}]);
