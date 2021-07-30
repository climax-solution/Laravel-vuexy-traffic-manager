<?php
  use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => ['auth','locked']], function () {
  // User dashboard
  Route::get('/', 'TrafficController@index')->name('traffic-manager');
  Route::get('/traffic-manager', 'TrafficController@index')->name('traffic-manager');
  Route::get('/redirects', 'RedirectController@index')->name('redirects.index');
  Route::get('/redirects/create', 'RedirectController@create')->name('redirects.create');
  Route::get('/redirects/edit', 'RedirectController@edit')->name('redirects.edit');
  Route::get('/campaigns', 'CampaignController@index')->name('campaigns.index');
  Route::get('/campaigns/create', 'CampaignController@create')->name('campaigns.create');
  Route::get('/schemas', 'SchemaController@index')->name('schemas.index');
  Route::get('/schemas/create', 'SchemaController@create')->name('schemas.create');
  Route::get('/pixels', 'PixelController@index')->name('pixels.index');
  Route::get('/pixels/create', 'PixelController@create')->name('pixels.create');
});

// Locked page
Route::get('locked', 'HomeController@lockedPage');

// locale Route
Route::get('lang/{locale}',[LanguageController::class,'swap']);
