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
use IP2ProxyLaravel;
use Jenssegers\Agent\Agent;

class RedirectController extends Controller
{
    public function __construct()
    {
      $this->countries = CountryListFacade::getList('en');
      $track = ['Convomat Default', 'convomat List'];
      $pixel = ['Select Pixel', 'Pixel item'];
      $campaign = ['Campaign 1', 'Campaign 2'];
      $country_group = ['group 1', 'group 2'];
      $this->compactData = [
        'track' => $track,
        'pixel' => $pixel,
        'campaign' => $campaign,
        'countries'  =>  $this->countries,
        'country_group' => $country_group
      ];
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

    public function stepUrlAsin() {
      return view('/pages/redirects/step-url/asin', $this->compactData);
    }

    public function stepUrlStoreFront() {
      return view('/pages/redirects/step-url/store-front', $this->compactData);
    }

    public function stepUrlHiddenKeyword() {
      return view('/pages/redirects/step-url/hidden-keyword', $this->compactData);
    }

    public function stepUrlProductResult() {
      return view('/pages/redirects/step-url/product-result', $this->compactData);
    }

    public function stepUrlBrand() {
      return view('/pages/redirects/step-url/brand', $this->compactData);
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

    public function redirectTracking(Request $request) {
      $id = $request->id;
      $data = Redirect::where('uuid', $id)->first();
      $src = CustomUrl::where('id',$data->item_id)->first();
      if (!$src) {
        $message = "No Exist Data.";
        return;
      }
      if ($data->max_hit_day == $data->take_count) {
        echo "<script> window.location.href = '".$data->fallback_url."';</script>";
      }
      $ip = $request->ip();
      $ip = "188.43.136.32";
      $data = \Location::get($ip);
      $status = [];
      $active_rule = json_decode($src->active_rule, true);
      $countryCode = $data->countryCode;
      if (!is_bool($data)) {
        foreach($active_rule as $item) {
          $status[$item] = 0;
          switch($item) {
            case '0':
              $row = GeoIp::where(['item_id' => $src->id, 'table_name' => 'custom_urls'])->first();
              $country_list = explode(',',$row->country_list);
              $country_status = array_search($countryCode, $country_list);
              $geoip = 0;
              switch($row->action) {
                case '0':
                  if ( $country_status != false ) $geoip = 1;
                  break;
                case '1':
                  if ( !$country_status ) $geoip = 1;
              }
              $status[$item] = $geoip;
              break;
            case '1':
              $row = Proxy::where(['item_id' => $src->id, 'table_name' => 'custom_urls'])->first();
              $db = new \IP2Proxy\WebService('CMLFVVOTC0',  'PX10', false);
              $res = $db->lookup($ip);
              $proxy = 0;
              switch($row->action) {
                case '0':
                  if ($res['isProxy'] == 'YES') $proxy = 1;
                  break;
                case '1':
                  if ($res['isProxy'] == 'NO') $proxy = 1;
                  break;
              }
              $status[$item] = $proxy;
              break;
            case '2':
              $row = Referrer::where(['item_id' => $src->id, 'table_name' => 'custom_urls'])->first();
              $domain = $row->domain_name;
              $refer = 0;
              switch($row->action) {
                case '0':
                  switch($row->domain_type) {
                    case '0':
                      switch($row->domain_reg) {
                        case '0':
                          if ($src->dest_url != $domain) $refer = 1;
                          break;
                        case '1':
                          if ($src->dest_url == $domain) $refer = 1;
                          break;
                      }
                      break;
                    case '1':
                      $parse_domain = parse_url($src->dest_url);
                      switch($row->domain_reg) {
                        case '0':
                          if ($parse_domain == $domain) $refer = 1;
                          break;
                        case '1':
                          if ($parse_domain != $domain) $refer = 1;
                          break;
                      }
                      break;
                  }
                  break;
                case '1':
                    switch($row->domain_type) {
                      case '0':
                        switch($row->domain_reg) {
                          case '0':
                            if ($src->dest_url == $domain) $refer = 1;
                            break;
                          case '1':
                            if ($src->dest_url != $domain) $refer = 1;
                            break;
                        }
                        break;
                      case '1':
                        $parse_domain = parse_url($src->dest_url);
                        switch($row->domain_reg) {
                          case '0':
                            if ($parse_domain == parse_url($domain)) $refer = 1;
                            break;
                          case '1':
                            if ($parse_domain != parse_url($domain)) $refer = 1;
                            break;
                        }
                        break;
                    }
                    break;
              }
              $status[$item] = $refer;
              break;
            case '3':
              $row = EmptyReferrer::where(['item_id' => $src->id, 'table_name' => 'custom_urls'])->first();
              $empty = 0;
              switch($row->action) {
                case '0':
                  if (!isset($_SERVER['HTTP_REFERER'])) $empty = 1;
                  break;
                case '1':
                  if (isset($_SERVER['HTTP_REFERER'])) $empty = 1;
                  break;
              }
              $status[$item] = $empty;
              break;
            case '4':
              $row = DeviceType::where(['item_id' => $src->id, 'table_name' => 'custom_urls'])->first();
              $device = 0;
              $agent = new Agent;
              switch($row->action) {
                case '0':
                  switch($row->device) {
                    case '0':
                      if ($agent->isMobile()) $device = 1;
                      break;
                    case '1':
                      if ($agent->isDesktop()) $device = 1;
                  }
                  break;
                case '1':
                  switch($row->device) {
                    case '0':
                      if ($agent->isMobile()) $device = 1;
                      break;
                    case '1':
                      if ($agent->isDesktop()) $device = 1;
                  }
                  break;
              }
              $status[$device] = $device;
              break;
          }
        }
        // dd($status);
        $src->take_count ++;
        $flag = 0;
        foreach ($status as $item) {
          if ($item) $flag = 1;
        }
        if ($flag) {
          Redirect::where('id',$data->id)->update(['take_count' => $src->take_count]);
          echo "<script> window.location.href = '".$data->fallback_url."';</script>";
        }
        else {
          Redirect::where('id',$data->id)->update(['take_count' => $src->take_count]);
          echo "<script> window.location.href = '".$data->dest_url."';</script>";
        }
      }
      else {
        return view('error');
      }
    }
}
