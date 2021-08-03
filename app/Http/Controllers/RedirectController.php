<?php

namespace App\Http\Controllers;

use App\Models\CustomUrl;
use App\Models\DeviceType;
use App\Models\EmptyReferrer;
use App\Models\GeoIp;
use App\Models\Proxy;
use Illuminate\Http\Request;
use App\Models\Redirect;
use App\Models\Referrer;
use Monarobase\CountryList\CountryListFacade;
use Illuminate\Support\Str;

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

    public function createNewCustomUrl(Request $request) {
      $input = $request->except('_token');
      unset($input['addFile']);
      $data = $input;
      $data['uuid'] = Str::random(7);
      $res = CustomUrl::create($data);
      $addFile = json_decode($request->input('addFile'),true);
      $active_rule = json_decode($data['active_rule']);
      foreach($active_rule as $item) {
        $addFile[$item]['item_id'] = $res->id;
        $addFile[$item]['table_name'] = 'custom_urls';
        $flag = 0;
        switch($item) {
          case '0':
            $count = GeoIp::where('item_id',$res->id)->count();
            if ($count) GeoIp::where('item_id',$res->id)->update($addFile[$item]);
            else GeoIp::create($addFile[$item]);
            $flag = 1;
            break;
          case '1':
            $count = Proxy::where('item_id',$res->id)->count();
            if ($count) Proxy::where('item_id',$res->id)->update($addFile[$item]);
            else Proxy::create($addFile[$item]);
            $flag = 1;

            break;
          case '2':
            $count = Referrer::where('item_id',$res->id)->count();
            if ($count) Referrer::where('item_id',$res->id)->update($addFile[$item]);
            else Referrer::create($addFile[$item]);
            $flag = 1;

            break;
          case '3':
            $count = EmptyReferrer::where('item_id',$res->id)->count();
            if ($count) EmptyReferrer::where('item_id',$res->id)->update($addFile[$item]);
            else EmptyReferrer::create($addFile[$item]);
            $flag = 1;
            break;
          case '4':
            $count = DeviceType::where('item_id',$res->id)->count();
            if ($count) DeviceType::where('item_id',$res->id)->update($addFile[$item]);
            else DeviceType::create($addFile[$item]);
            $flag = 1;
            break;
        };
      }
      $url = env('APP_URL').'/r/'.$data['uuid'];
      return response()->json(['url' => $url]);
    }


}
