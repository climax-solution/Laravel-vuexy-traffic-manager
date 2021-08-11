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
  Route::get('/redirects/custom-url', 'CustomUrlController@index')->name('redirects.custom-url');
  Route::get('/redirects/url-rotator', 'UrlRotatorController@index')->name('redirects.url-rotator');
  //Step Url
  Route::get('/redirects/step-url', 'StepUrlController@index')->name('redirects.step-url');
  Route::get('/redirects/step-url/asin', 'RedirectController@stepUrlAsin')->name('redirects.step-asin');
  Route::get('/redirects/step-url/store-front', 'RedirectController@stepUrlStoreFront')->name('redirects.step-store-front');
  Route::get('/redirects/step-url/hidden-keyword', 'RedirectController@stepUrlHiddenKeyword')->name('redirects.step-hidden-keyword');
  Route::get('/redirects/step-url/product-result', 'RedirectController@stepUrlProductResult')->name('redirects.step-product-result');
  Route::get('/redirects/step-url/brand', 'RedirectController@stepUrlBrand')->name('redirects.step-brand');
  //
  Route::get('/redirects/dynamic-qr-code', 'QrCodeController@index')->name('redirects.step-dynamic-qr-code');
  Route::post('/redirects/create-new-qr-code', 'QrCodeController@createNewQrCode')->name('redirects.create-new-qr-code');

  Route::get('/redirects/keyword-rotator', 'KeywordRotatorController@index')->name('redirects.step-keyword-rotator');

  //
  Route::get('/campaigns', 'CampaignController@index')->name('campaigns.index');
  Route::get('/campaigns/create', 'CampaignController@create')->name('campaigns.create');
  Route::get('/schemas', 'SchemaController@index')->name('schemas.index');
  Route::get('/schemas/create', 'SchemaController@create')->name('schemas.create');
  Route::get('/pixels', 'PixelController@index')->name('pixels.index');
  Route::get('/pixels/create', 'PixelController@create')->name('pixels.create');

  //CRUD
  Route::post('/create-new-custom-url','CustomUrlController@createNewCustomUrl')->name('redirects.create-new-custom-url');
  Route::post('/create-new-url-rotator','UrlRotatorController@createNewUrlRotator')->name('redirects.create-new-url-rotator');
  Route::post('/create-new-keyword-rotator','KeywordRotatorController@createNewKeywordRotator')->name('redirects.create-new-keyword-rotator');

  Route::post('/create-new-step-asin','StepUrlController@createNewStepAsin')->name('redirects.create-new-step-asin');

  Route::post('/update-url-active','RedirectController@updateActive')->name('redirects.update-url-active');
  Route::post('/clone-url','RedirectController@cloneURL')->name('redirects.clone-url');
  Route::post('/delete-url','RedirectController@deleteURL')->name('redirects.delete-url');

  Route::post('/get-csv-data','UrlRotatorController@getCsvData');
  Route::post('/get-csv-data-step-asin','StepUrlController@getCsvData');
});
Route::get('/r/{id}','RedirectController@redirectTracking')->name('redirects.redirect-to');

// Locked page
Route::get('locked', 'HomeController@lockedPage');

// locale Route
Route::get('lang/{locale}',[LanguageController::class,'swap']);
