<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redirect;
use Monarobase\CountryList\CountryListFacade;

class RedirectController extends Controller
{
    public function __construct()
    {
      $this->countries = CountryListFacade::getList('en');
    }
    public function index()
    {
        $pageConfigs = $this->pageConfigs;
        $redirects = Redirect::all();
        $analysis = [
            'activeLinks' => 3,
            'totalRedirects' => 8,
            'totalPixelsFired' => 8,
            'totalBlockedTraffic' => 8
        ];
        return view('/pages/redirects/index',
            compact('pageConfigs', 'analysis', 'redirects')
        );
    }
    public function create()
    {
      $json = file_get_contents(storage_path('create-new-redirect-list.json'));
      $objs = json_decode($json,true);
        return view('/pages/redirects/create', [
            'pageConfigs' => $this->pageConfigs,
            'products' => $objs['products']
        ]);
    }

    public function customUrl() {
      $track = ['Convomat Default', 'convomat List'];
      $pixel = ['Select Pixel', 'Pixel item'];
      $campaign = ['Campaign 1', 'Campaign 2'];
      $country_group = ['group 1', 'group 2'];
      $data = [
        'track' => $track,
        'pixel' => $pixel,
        'campaign' => $campaign,
        'countries'  =>  $this->countries,
        'country_group' => $country_group
      ];
      return view('/pages/redirects/step-url/custom-url', $data);
    }

    public function urlRotator() {
      return view('/pages/redirects/step-url/url-rotator', ['countries' => $this->countries]);
    }

    public function stepUrlAsin() {
      return view('/pages/redirects/step-url/asin', ['countries' => $this->countries]);
    }

    public function stepUrlStoreFront() {
      return view('/pages/redirects/step-url/store-front', ['countries' => $this->countries]);
    }

    public function stepUrlHiddenKeyword() {
      return view('/pages/redirects/step-url/hidden-keyword', ['countries' => $this->countries]);
    }

    public function stepUrlProductResult() {
      return view('/pages/redirects/step-url/product-result', ['countries' => $this->countries]);
    }

    public function stepUrlBrand() {
      return view('/pages/redirects/step-url/brand', ['countries' => $this->countries]);
    }

    public function dynamicQrCode() {

    }

    public function keywordRotator() {
      return view('/pages/redirects/step-url/keyword-rotator', ['countries' => $this->countries]);
    }

    public function cartUrl() {
      return view('/pages/redirects/step-url/cart-url', ['countries' => $this->countries]);
    }

    public function productUrl() {
      return view('/pages/redirects/step-url/product-url', ['countries' => $this->countries]);
    }

}
