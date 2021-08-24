<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Monarobase\CountryList\CountryListFacade;
use stdClass;

class UrlRotatorController extends Controller {

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
    $compactData = $this->compactData;   $id = $request->query('id');
    $url_data = Redirect::where('id', $id)->where('table_name', 'url_rotator')->first();
    $rule_data = [];  $url_list = [];   $advance_options = [];
    $rotation = 0;
    if ($url_data) {
      $url_rotator = UrlRotator::where('id',$url_data->item_id)->first();
      $Rule = $this->rule_list;
      foreach($Rule as $key => $item) {
        $rule_data[$key] = $item::where('item_id',$url_rotator->id)->where('table_name','url_rotator')->first();
      }
      $url_list = UrlRotatorList::where('parent_id',$url_rotator->id)->get();
      $advance_options = json_decode($url_rotator->advance_options, true);
      $rotation = $url_rotator->rotation_option;
    }
    $compactData['rule_data'] = $rule_data;
    $compactData['url_data'] = !$url_data ? [] : $url_data;
    $compactData['advance_options'] = $advance_options;
    $compactData['rotation'] = $rotation;
    $compactData['url_list'] = $url_list;
    $compactData['id'] = !$url_data ? -1 : $id;
    return view('/pages/redirects/url-rotator',$compactData);
  }

  public function createNewUrlRotator(Request $request) {
    $input = $request->except('_token');
    unset($input['addFile']);
    $redirect = Redirect::where('id', $input['id'])->first();
    if(isset($redirect->item_id)) Helper::removeRules($redirect->item_id);
    unset($input['id']);
    unset($input['url_list']);
    $block_item = ['link_name','tracking_url', 'dest_url','fallback_url', 'max_hit_day', 'campaign','pixel'];
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
    if (!isset($data['id'])) $res = UrlRotator::create($data);
    else {
      UrlRotator::where('id', $data['id'])->update($data);
      $res = new stdClass();
      $res->id = $redirect->item_id;
    }
    $addFile = json_decode($request->input('addFile'),true);
    $active_rule = json_decode($data['active_rule']);
    $redirectData['item_id'] = $res->id;
    $redirectData['table_name'] = 'url_rotator';
    $redirectData['user_id'] = auth()->user()->id;
    if(!isset($redirect->item_id)) {
      $redirectData['uuid'] = $uuid;
      Redirect::create($redirectData);
    }
    else {
      $redirectData['uuid'] = $redirect->uuid;
      $redirectData['uuid'] = $redirect->uuid;
      Redirect::where('id',$redirect->id)->update($redirectData);
    }
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
    $url_list = json_decode($request->url_list,true);
    UrlRotatorList::where('parent_id', $res->id)->delete();
    foreach($url_list as $key => $url) {
      $url['parent_id'] = $res->id;
      $url['uuid'] = $key;
      $dest_url = $url['dest_url'];
      if ($url['spoof_referrer'] == 1 && $url['spoof_type'] == '0') {
        $url['request_id'] = Helper::createGoogleSpoof($dest_url);
      }
      UrlRotatorList::create($url);
    }
    $url = env('APP_URL').'/r/'.$redirectData['uuid'];
    return response()->json(['url' => $url]);
  }

  public function getCsvData(Request $request) {
    $ext = $request->file('file')->getClientOriginalExtension();
    $name = time().'.'.$ext;
    Storage::disk('csv_file')->putFileAs('/',$request->file('file'),$name);
    $header = null;
    $data = array();
    if (($handle = fopen(public_path('/csv_file/'.$name), 'r')) !== false)
    {
        while (($row = fgetcsv($handle, 1000, ',')) !== false)
        {
            if (!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    unlink(public_path('/csv_file/'.$name));
    return response()->json($data);
  }

  public function getCustomUrl(Request $request) {
    $id = auth()->user()->id;
    $res = Redirect::distinct()->select('dest_url')->where('user_id', $id)->where('table_name', 'custom_urls')->get();
    return response()->json($res);
  }
}
