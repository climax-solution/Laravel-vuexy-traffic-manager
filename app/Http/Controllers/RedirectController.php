<?php

namespace App\Http\Controllers;

use App\Models\StepUrl;
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
      // $license_key = 'f7dYbv0eL31zjHTc';
      // $ipaddress   = '188.43.136.32';
      // $query = "https://minfraud.maxmind.com/app/ipauth_http?l=" . $license_key
      //     . "&i=" . $ipaddress;
      // $score = file_get_contents($query);
      // echo $score; die();
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
      $create['take_count'] = 0;
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
      if (!isset($redirect_src->active) || isset($redirect_src->active) && !$redirect_src->active) {
        return abort(404);
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
        case 'step_url':
          $Model = StepUrl::class;
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
        return abort(404);
      }

      if ($redirect_src->table_name != 'qr_code' && $redirect_src->max_hit_day == $redirect_src->take_count) {
        return redirect()->to($redirect_src->fallback_url);
      }
      $ipaddress = '';
      if (isset($_SERVER['HTTP_CLIENT_IP']))
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_X_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
      else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_FORWARDED'];
      else if(isset($_SERVER['REMOTE_ADDR']))
          $ipaddress = $_SERVER['REMOTE_ADDR'];
      else
          $ipaddress = 'UNKNOWN';
      $data = \Location::get($ipaddress);
      // dd($data);
      if ($redirect_src->table_name != 'qr_code') {
        $active_rule = json_decode($src->active_rule, true);
        $redirect_src->take_count ++;
        Redirect::where('id',$redirect_src->id)->update(['take_count' => $redirect_src->take_count]);
      }
      if (is_object($data)) {
        $status = [];
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
                    if ( !is_bool($country_status) ) $geoip = 1;
                    break;
                  case '1':
                    if ( $country_status == false && is_bool($country_status)) $geoip = 1;
                }
                // dd($country_status, $row->action, $geoip, !is_bool($country_status));
                $status[$item] = $geoip;
                break;
              case '1':
                $row = Proxy::where(['item_id' => $src->id, 'table_name' => $redirect_src->table_name])->first();
                $check = new \IP2Proxy\Database(base_path('vendor/ip2location/ip2proxy-php/data/PX10.SAMPLE.BIN'), \IP2PROXY\Database::FILE_IO);
                $res = $check->lookup($ipaddress, \IP2PROXY\Database::ALL);
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
                        if ($agent->isDesktop()) $device = 1;
                        break;
                      case '1':
                        if ($agent->isMobile()) $device = 1;
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
                $status[$item] = $device;
                break;
            }
          }
        }

        $flag = 0;
        foreach ($status as $item) {
          if ($item) $flag = 1;
        }
        switch($redirect_src->table_name) {
          case 'custom_urls':
            $dest_url = $redirect_src->dest_url;
            $parse_url = parse_url($dest_url);
            $advanced_option = json_decode($src->advance_options, true);
            if ($advanced_option['deep']) {
              if ($parse_url['host'] == 'amazon.com') {
                $scheme = ['http://', 'https://'];
                foreach($scheme as $item) {
                  if (strpos($dest_url, $item) === 0) {
                    $dest_url = str_replace($item, '', $dest_url);
                  }
                }
                $redirect_src->dest_url = 'com.amazon.mobile.shopping.web://'.$dest_url;
              }
            }
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
            $dest_url = $redirect_src->dest_url;
            $parse_url = parse_url($dest_url);
            $advanced_option = json_decode($src->advance_options, true);
            if ($advanced_option['deep']) {
              if ($parse_url['host'] == 'amazon.com') {
                $scheme = ['http://', 'https://'];
                foreach($scheme as $item) {
                  if (strpos($dest_url, $item) === 0) {
                    $dest_url = str_replace($item, '', $dest_url);
                  }
                }
                $redirect_src->dest_url = 'com.amazon.mobile.shopping.web://'.$dest_url;
              }
            }
            $ReList::where(['parent_id' => $src->id, 'uuid' => $index])->update(['take_count' => $url_lists[$index]->take_count ]);
            break;
        }
        if ($flag) {
          return redirect()->to($redirect_src->fallback_url);
        }
        else {
          if ($redirect_src->table_name != 'qr_code' && $redirect_src->table_name != 'custom_urls' ) {
            $Model::where('id',$src->id)->update(['active_position' => $src->active_position]);
          }
          return redirect()->to($redirect_src->dest_url);
        }
      }
      else {
        return redirect()->to($redirect_src->fallback_url);
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

    public function editURL(Request $request) {
      $uuid = $request->uuid;
      $row = Redirect::where('uuid', $uuid)->first();
      switch($row->table_name) {
        case 'custom_urls':
          return redirect('/redirects/custom-url?id='.$row->id);
          break;
        case 'url_rotator':
          return redirect('/redirects/url-rotator?id='.$row->id);
          break;
        case 'step_url':
          return redirect('/redirects/step-url?id='.$row->id);
          break;
        case 'qr_code':
          return redirect('/redirects/qr-code?id='.$row->id);
          break;
        case 'keyword_rotator':
          return redirect('/redirects/keyword-rotator?id='.$row->id);
          break;
      }
    }
}
