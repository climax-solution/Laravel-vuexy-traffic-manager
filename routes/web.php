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
//Redirects
  Route::get('/redirects', 'RedirectController@index')->name('redirects.index');
  Route::get('/redirects/create', 'RedirectController@create')->name('redirects.create');
  Route::get('/redirects/custom-url', 'RedirectController@customUrl')->name('redirects.custom-url');
  Route::get('/redirects/url-router', 'RedirectController@urlRouter')->name('redirects.url-router');
  Route::get('/redirects/step-url', 'RedirectController@stepUrl')->name('redirects.step-url');
  Route::get('/redirects/dynamic-qr-code', 'RedirectController@dynamicQrCode')->name('redirects.dynamic-qr-code');
  Route::get('/redirects/keyword-rotator', 'RedirectController@keywordRotator')->name('redirects.keyword-rotator');
  Route::get('/redirects/cart-url', 'RedirectController@cartUrl')->name('redirects.cart-url');
  Route::get('/redirects/product-url', 'RedirectController@productUrl')->name('redirects.product-url');
//
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
