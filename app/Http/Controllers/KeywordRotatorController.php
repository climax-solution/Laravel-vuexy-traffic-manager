<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\DeviceType;
use App\Models\EmptyReferrer;
use App\Models\GeoIp;
use App\Models\KeywordRotator;
use App\Models\KeywordRotatorList;
use App\Models\Proxy;
use App\Models\Redirect;
use App\Models\Referrer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Monarobase\CountryList\CountryListFacade;
use Illuminate\Support\Str;
use stdClass;

class KeywordRotatorController extends Controller
{
  public function __construct()
  {
    $this->compactData = [
      'track' => ['Convomat Default', 'convomat List'],
      'pixel' => ['Select Pixel', 'Pixel item'],
      'campaign' => ['Campaign 1', 'Campaign 2'],
      'countries'  =>  CountryListFacade::getList('en'),
      'country_group' => ['group 1', 'group 2']
    ];
    $this->rule_list = [
      GeoIp::class,
      Proxy::class,
      Referrer::class,
      EmptyReferrer::class,
      DeviceType::class
    ];
  }

  public function index(Request $request) {
    $compactData = $this->compactData; $id = $request->query('id');
    $url_data = Redirect::where('id', $id)->where('table_name', 'keyword_rotator')->first();
    $rule_data = [];  $url_list = [];   $advance_options = [];
    $rotation = 0;
    if ($url_data) {
      $keyword_rotator = KeywordRotator::where('id',$url_data->item_id)->first();
      $Rule = $this->rule_list;
      foreach($Rule as $key => $item) {
        $rule_data[$key] = $item::where('item_id',$keyword_rotator->id)->where('table_name','keyword_rotator')->first();
      }
      $url_list = KeywordRotatorList::where('parent_id',$keyword_rotator->id)->get();
      $advance_options = json_decode($keyword_rotator->advance_options, true);
      $rotation = $keyword_rotator->rotation_option;
    }
    $compactData['rule_data'] = $rule_data;
    $compactData['url_data'] = !$url_data ? [] : $url_data;
    $compactData['advance_options'] = $advance_options;
    $compactData['rotation'] = $rotation;
    $compactData['url_list'] = $url_list;
    $compactData['id'] = !$url_data ? -1 : $id;
    return view('/pages/redirects/keyword-rotator', $compactData);
  }

  public function createNewKeywordRotator(Request $request) {
    $input = $request->except('_token');
    unset($input['addFile']);
    $redirect = Redirect::where('id', $input['id'])->first();
    if(isset($redirect->item_id)) Helper::removeRules($redirect->item_id);
    unset($input['id']);
    unset($input['url_list']);
    $block_item = ['link_name','tracking_url','fallback_url', 'max_hit_day', 'campaign','pixel'];
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
    if (!isset($data['id'])) $res = KeywordRotator::create($data);
    else {
      KeywordRotator::where('id', $data['id'])->update($data);
      $res = new stdClass();
      $res->id = $redirect->item_id;
    }
    $addFile = json_decode($request->input('addFile'),true);
    $active_rule = json_decode($data['active_rule']);
    $redirectData['item_id'] = $res->id;
    $redirectData['table_name'] = 'keyword_rotator';
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
      $addFile[$item]['table_name'] = 'keyword_rotator';
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
    $url_list = json_decode($request->url_list,true);
    KeywordRotatorList::where('parent_id', $res->id)->delete();
    foreach($url_list as $key => $url) {
      $url['parent_id'] = $res->id;
      $url['uuid'] = $key;
      KeywordRotatorList::create($url);
    }
    $url = env('APP_URL').'/r/'.$uuid;
    return response()->json(['url' => $url]);
  }
}
