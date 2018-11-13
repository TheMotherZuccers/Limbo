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

// Page to update items (for admins and posters) and to view information on an item (everybody)
Route::get('/item/{id}', function ($id) {
    return \App\Http\Controllers\ItemController::get_item_data($id);
});

Route::put('update_item', ['as' => 'form_url', 'uses' => 'ItemController@update']);

// Generates the nessesary routes for authentication
Auth::routes();

// Homepage for users when they initially login
Route::get('/home', 'HomeController@index')->name('home');

// Admin dashboard
Route::get('admin', ['middleware' => 'admin', function () {
    return \App\Http\Controllers\DataRetrieval\AdminController::admin_home();
}]);

// Report item page
Route::get('report_item/{senario}','ItemController@add_item')->where('senario', '(lost)|(found)');
// Report item post url
Route::post('report_item', ['as' => 'form_url', 'uses' => 'ItemController@store']);
