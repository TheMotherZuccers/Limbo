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

use App\Repositories\ItemRepository;

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

// Generates the necessary routes for authentication
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

Route::post('claim_item', ['as' => 'form_url', 'uses' => 'ItemClaimController@store']);

Route::get('search', function (ItemRepository $repository) {
    $items = $repository->search((string) request('q'));

    return view('search', [
        'items' => $items,
    ]);
});

Route::get('searchastype', 'ItemSearchController@search_as_type');

Route::get('responsive_pagination', 'ItemController@responsive_pagination');

Route::post('approve_claim', ['as' => 'form_url', 'uses' => 'ItemClaimController@approve_claim']);
