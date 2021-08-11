<?php

namespace App\Http\Controllers;

use App\Models\Asin;
use App\Models\CustomUrl;
use App\Models\DeviceType;
use App\Models\EmptyReferrer;
use App\Models\GeoIp;
use App\Models\KeywordRotator;
use App\Models\KeywordRotatorList;
use App\Models\Proxy;
use App\Models\QrCode;
use Illuminate\Http\Request;
use App\Models\Redirect;
use App\Models\Referrer;
use App\Models\StepUrlList;
use App\Models\UrlRotator;
use App\Models\UrlRotatorList;
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
        $id = auth()->user()->id;
        $redirects = Redirect::where('user_id', $id)->get();
        $activeLink = Redirect::where('active', 1)->where('user_id', $id)->count();
        $analysis = [
            'activeLinks' => $activeLink,
            'totalRedirects' => count($redirects),
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

    public function updateActive(Request $request) {
      $res = Redirect::where('id',$request->id)->update(['active' => $request->active]);
      return $res;
    }

    public function cloneURL(Request $request) {
      $data = json_decode(Redirect::where('id',$request->id)->first(), true);
      $create = [];
      unset($data['id']);
      unset($data['created_at']);
      unset($data['updated_at']);
      $data['uuid'] = Str::random(7);
      $data['active'] = 0;
      foreach($data as $key => $value) {
        $create[$key] = $value;
      }
      $res = Redirect::create($create);
      return $res;
    }

    public function deleteURL(Request $request) {
      $data = Redirect::where('id',$request->id)->delete();
      return $data;
    }

    public function redirectTracking(Request $request) {
      // dd($_SERVER);
      $Model = '';
      $ReList = StepUrlList::class;
      $id = $request->id;
      $redirect_src = Redirect::where('uuid', $id)->first();
      if (!$redirect_src->active) {
        echo "<script> window.location.href = '".$redirect_src->fallback_url."';</script>";
      }
      $src = '';
      switch($redirect_src->table_name) {
        case 'custom_urls':
          $Model = CustomUrl::class;
          break;
        case 'url_rotator':
          $Model = UrlRotator::class;
          $ReList = UrlRotatorList::class;
          break;
        case 'asin':
          $Model = Asin::class;
          break;
        case 'qr_code':
          $Model = QrCode::class;
          break;
        case 'keyword_rotator':
          $Model = KeywordRotator::class;
          $ReList = KeywordRotatorList::class;
          break;
      };
      $src = $Model::where('id',$redirect_src->item_id)->first();
      if ($redirect_src->table_name != 'qr_code' && !$src) {
        $message = "No Exist redirect_src.";
        return;
      }

      if ($redirect_src->table_name != 'qr_code' && $redirect_src->max_hit_day == $redirect_src->take_count) {
        echo "<script> window.location.href = '".$redirect_src->fallback_url."';</script>";
      }
      $ip = request()->ip();
      // $ip = "188.43.136.32";
      dd(\Request::getClientIp(true));
      $data = \Location::get($ip);
      $status = [];
      if ($redirect_src->table_name != 'qr_code') $active_rule = json_decode($src->active_rule, true);
      if (!is_bool($data)) {
        $countryCode = $data->countryCode;
        if ($redirect_src->table_name != 'qr_code') {
          foreach($active_rule as $item) {
            $status[$item] = 0;
            switch($item) {
              case '0':
                $row = GeoIp::where(['item_id' => $src->id, 'table_name' => $redirect_src->table_name])->first();
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
                $row = Proxy::where(['item_id' => $src->id, 'table_name' => $redirect_src->table_name])->first();
                $check = new \IP2Proxy\Database(base_path('vendor/ip2location/ip2proxy-php/data/PX10.SAMPLE.BIN'), \IP2PROXY\Database::FILE_IO);
                $res = $check->lookup($ip, \IP2PROXY\Database::ALL);
                $proxy = 0;
                switch($row->action) {
                  case '0':
                    if ($res['isProxy']) $proxy = 1;
                    break;
                  case '1':
                    if (!$res['isProxy']) $proxy = 1;
                    break;
                }
                $status[$item] = $proxy;
                break;
              case '2':
                $row = Referrer::where(['item_id' => $src->id, 'table_name' => $redirect_src->table_name])->first();
                $domain = $row->domain_name;
                $refer = 0;
                $REFERRER = ($_SERVER);
                switch($row->action) {
                  case '0':
                    switch($row->domain_type) {
                      case '0':
                        switch($row->domain_reg) {
                          case '0':
                            if ($redirect_src->dest_url != $domain) $refer = 1;
                            break;
                          case '1':
                            if ($redirect_src->dest_url == $domain) $refer = 1;
                            break;
                        }
                        break;
                      case '1':
                        $parse_domain = parse_url($redirect_src->dest_url);
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
                              if ($redirect_src->dest_url == $domain) $refer = 1;
                              break;
                            case '1':
                              if ($redirect_src->dest_url != $domain) $refer = 1;
                              break;
                          }
                          break;
                        case '1':
                          $parse_domain = parse_url($redirect_src->dest_url);
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
                $row = EmptyReferrer::where(['item_id' => $src->id, 'table_name' => $redirect_src->table_name])->first();
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
                $row = DeviceType::where(['item_id' => $src->id, 'table_name' => $redirect_src->table_name])->first();
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
        }

        $redirect_src->take_count ++;
        $flag = 0;
        foreach ($status as $item) {
          if ($item) $flag = 1;
        }
        switch($redirect_src->table_name) {
          case 'custom_urls':
            break;
          case 'qr_code':
            break;
          default:
            $url_lists = $ReList::where('parent_id',$src->id)->get();
            $weight_sum = $ReList::where('parent_id',$src->id)->sum('weight');
            $list_len = count($url_lists);
            $index = 0;
            switch($src->rotation_option) {
              case '0':
                $index = rand(0, $list_len - 1);
                break;
              case '1':
                $weight_index = [];
                foreach ($url_lists as $key => $list) {
                  $weight_item = array_fill(0, $list->weight, $key);
                  $weight_index = array_merge($weight_index, $weight_item);
                }
                $rand = mt_rand(0, $weight_sum - 1);
                $index = $weight_index[$rand];
                break;
              case '2':
                if ($src->active_position == $list_len - 1) $src->active_position = 0;
                else $src->active_position ++;
                $index = $src->active_position;
                break;
              case '3':
                $rand = rand(0, $list_len - 1);
                $index = $this->pare_count($url_lists,$rand);
                if ($index == false) {
                  $flag = 1;
                }
                else $url_lists[$index]->take_count ++;
                break;

            }

            $redirect_src->dest_url = $url_lists[$index]->dest_url;
            $ReList::where(['parent_id' => $src->id, 'uuid' => $index])->update(['take_count' => $url_lists[$index]->take_count ]);
            break;
        }
        if ($redirect_src->table_name != 'qr_code') Redirect::where('id',$redirect_src->id)->update(['take_count' => $redirect_src->take_count]);
        if ($flag) {
          echo "<script> window.location.href = '".$redirect_src->fallback_url."';</script>";
        }
        else {
          if ($redirect_src->table_name != 'qr_code' && $redirect_src->table_name != 'custom_urls' ) {
            $Model::where('id',$src->id)->update(['active_position' => $src->active_position]);
          }
          echo "<script> window.location.href = '".$redirect_src->dest_url."';</script>";
        }
      }
      else {
        echo "<script> window.location.href = '".$redirect_src->fallback_url."';</script>";
      }
    }
    public function pare_count($data,$index) {
      if ($data[$index]->take_count < $data[$index]->max_hit_day) {
        return $index;
      }
      else {
        if (count($data) == 1) return false;
        $rand = rand(0, count($data) - 1);
        $this->pare_count($data, $rand);
      }
    }
}
