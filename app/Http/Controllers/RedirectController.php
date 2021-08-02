<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redirect;

class RedirectController extends Controller
{
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
      return view('/pages/redirects/custom-url');
    }

    public function urlRouter() {
      return view('/pages/redirects/url-router');
    }

    public function stepUrl() {
      return view('/pages/redirects/step-url');
    }

    public function dynamicQrCode() {

    }

    public function keywordRotator() {

    }

    public function cartUrl() {

    }

    public function productUrl() {

    }

}