<?php

namespace App\Http\Controllers;

use App\Models\CustomUrl;
use App\Models\UrlRotator;
use App\Models\DeviceType;
use App\Models\EmptyReferrer;
use App\Models\GeoIp;
use App\Models\Proxy;
use App\Models\Redirect;
use App\Models\Referrer;
use App\Models\UrlRotatorList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Monarobase\CountryList\CountryListFacade;

class UrlRotatorController extends Controller {

  public function __construct()
  {
    $this->compactData = [
      'track' => ['Convomat Default', 'convomat List'],
      'pixel' => ['Select Pixel', 'Pixel item'],
      'campaign' => ['Campaign 1', 'Campaign 2'],
      'countries'  =>  CountryListFacade::getList('en'),
      'country_group' => ['group 1', 'group 2']
    ];
  }

  public function index() {
    $links = Redirect::select('dest_url')->get();
    $compactData = $this->compactData;
    $compactData['links'] = $links;
    return view('/pages/redirects/url-rotator',$compactData);
  }

  public function createNewUrlRotator(Request $request) {
    $input = $request->except('_token');
    unset($input['addFile']);
    $block_item = ['link_name','tracking_url', 'dest_url','fallback_url', 'max_hit_day', 'campaign'];
    $redirectData = [];
    foreach($block_item as $item) {
      if (isset($input[$item])) {
        $redirectData[$item] = $input[$item];
        unset($input[$item]);
      }
    }
    $data = $input;
    $uuid = Str::random(7);
    $res = UrlRotator::create($data);
    $addFile = json_decode($request->input('addFile'),true);
    $active_rule = json_decode($data['active_rule']);
    $redirectData['uuid'] = $uuid;
    $redirectData['item_id'] = $res->id;
    $redirectData['table_name'] = 'url_rotator';
    Redirect::create($redirectData);
    foreach($active_rule as $item) {
      $addFile[$item]['item_id'] = $res->id;
      $addFile[$item]['table_name'] = 'url_rotator';
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
    $url = env('APP_URL').'/r/'.$uuid;
    return response()->json(['url' => $url]);
  }
}
