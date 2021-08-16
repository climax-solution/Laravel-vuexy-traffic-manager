<?php

namespace App\Http\Controllers;

use App\Models\CustomUrl;
use App\Models\DeviceType;
use App\Models\EmptyReferrer;
use App\Models\GeoIp;
use App\Models\Proxy;
use App\Models\Referrer;
use Illuminate\Http\Request;
use App\Models\Redirect;
use Monarobase\CountryList\CountryListFacade;
use Illuminate\Support\Str;
use Helper;
use stdClass;

class CustomUrlController extends Controller
{
  public function __construct()
  {
    $this->compactData = config('constants');
    $this->compactData['countries'] = CountryListFacade::getList('en');
    $this->rule_list = [
      GeoIp::class,
      Proxy::class,
      Referrer::class,
      EmptyReferrer::class,
      DeviceType::class
    ];
  }

  public function index(Request $request) {
    $id = $request->query('id');
    $url_data = Redirect::where('id', $id)->where('table_name', 'custom_urls')->first();
    $compactData = $this->compactData;
    $rule_data = [];
    $advance_options = [];
    if ($url_data) {
      $custom_url = CustomUrl::where('id',$url_data->item_id)->first();
      $Rule = $this->rule_list;
      foreach($Rule as $key => $item) {
        $rule_data[$key] = $item::where('item_id',$custom_url->id)->where('table_name','custom_urls')->first();
      }
      $advance_options = json_decode($custom_url->advance_options, true);
      $advance_options['spoof_service'] = $custom_url->spoof_service;
    }
    $compactData['rule_data'] = $rule_data;
    $compactData['url_data'] = !$url_data ? [] : $url_data;
    $compactData['advance_options'] = $advance_options;
    $compactData['id'] = !$url_data ? -1 : $id;
    return view('/pages/redirects/custom-url', $compactData);
  }

  public function createNewCustomUrl(Request $request) {
    $input = $request->except('_token');
    unset($input['addFile']);
    $redirect = Redirect::where('id', $input['id'])->first();
    if(isset($redirect->item_id)) Helper::removeRules($redirect->item_id);
    unset($input['id']);
    $block_item = ['link_name','tracking_url', 'dest_url','fallback_url', 'max_hit_day', 'campaign', 'pixel'];
    $redirectData = [];
    foreach($block_item as $item) {
      if (isset($input[$item])) {
        $redirectData[$item] = $input[$item];
        unset($input[$item]);
      }
    }
    $data = $input;
    if(isset($redirect->item_id)) $data['id'] = $redirect->item_id;
    $uuid = Str::random(7);
    if (!isset($data['id'])) $res = CustomUrl::create($data);
    else {
      CustomUrl::where('id', $data['id'])->update($data);
      $res = new stdClass();
      $res->id = $redirect->item_id;
    }
    $addFile = json_decode($request->input('addFile'),true);
    $active_rule = json_decode($data['active_rule']);
    $redirectData['item_id'] = $res->id;
    $redirectData['table_name'] = 'custom_urls';
    $redirectData['user_id'] = auth()->user()->id;
    if(!isset($redirect->item_id)) {
      $redirectData['uuid'] = $uuid;
      Redirect::create($redirectData);
    }
    else {
      $redirectData['uuid'] = $redirect->uuid;
      Redirect::where('id',$redirect->id)->update($redirectData);
    }
    foreach($active_rule as $item) {
      $addFile[$item]['item_id'] = $res->id;
      $addFile[$item]['table_name'] = 'custom_urls';
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
