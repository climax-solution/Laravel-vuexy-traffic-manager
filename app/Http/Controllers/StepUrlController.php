<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\StepUrl;
use App\Models\DeviceType;
use App\Models\EmptyReferrer;
use App\Models\GeoIp;
use App\Models\Proxy;
use App\Models\Redirect;
use App\Models\Referrer;
use App\Models\StepUrlList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Monarobase\CountryList\CountryListFacade;
use Illuminate\Support\Str;
use stdClass;

class StepUrlController extends Controller
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
    $compactData = $this->compactData; $id = $request->query('id');
    $url_data = Redirect::where('id', $id)->where('table_name', 'step_url')->first();
    $rule_data = [];  $url_list = [];   $advance_options = [];
    $rotation = 0; $amazon_aff_id = '';
    if ($url_data) {
      $step_url = StepUrl::where('id',$url_data->item_id)->first();
      $Rule = $this->rule_list;
      foreach($Rule as $key => $item) {
        $rule_data[$key] = $item::where('item_id',$step_url->id)->where('table_name','step_url')->first();
      }
      $amazon_aff_id = $step_url->amazon_aff_id;
      $url_list = StepUrlList::where('parent_id',$step_url->id)->get();
      $advance_options = json_decode($step_url->advance_options, true);
      $advance_options['spoof_service'] = $step_url->spoof_service;
      $rotation = $step_url->rotation_option;
    }
    $compactData['rule_data'] = $rule_data;
    $compactData['url_data'] = !$url_data ? [] : $url_data;
    $compactData['advance_options'] = $advance_options;
    $compactData['rotation'] = $rotation;
    $compactData['amazon_aff_id'] = $amazon_aff_id;
    $compactData['url_list'] = $url_list;
    $compactData['id'] = !$url_data ? -1 : $id;
    return view('/pages/redirects/step-url',$compactData);
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

  public function createNewStepAsin(Request $request) {
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
    if (!isset($data['id'])) $res = StepUrl::create($data);
    else {
      StepUrl::where('id', $data['id'])->update($data);
      $res = new stdClass();
      $res->id = $redirect->item_id;
    }
    $table_name = 'step_url';
    $addFile = json_decode($request->input('addFile'),true);
    $active_rule = json_decode($data['active_rule']);
    $redirectData['item_id'] = $res->id;
    $redirectData['table_name'] = $table_name;
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
      $addFile[$item]['table_name'] = $table_name;
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
    StepUrlList::where('parent_id', $res->id)->delete();
    foreach($url_list as $key => $url) {
      $url['parent_id'] = $res->id;
      $url['uuid'] = $key;
      StepUrlList::create($url);
    }
    $url = env('APP_URL').'/r/'.$uuid;
    return response()->json(['url' => $url]);
  }
}
