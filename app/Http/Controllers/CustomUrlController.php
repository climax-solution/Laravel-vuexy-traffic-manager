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

class CustomUrlController extends Controller
{
  public function __construct()
  {
    $track = ['Convomat Default', 'convomat List'];
    $pixel = ['Select Pixel', 'Pixel item'];
    $campaign = ['Campaign 1', 'Campaign 2'];
    $country_group = ['group 1', 'group 2'];
    $this->compactData = [
      'track' => $track,
      'pixel' => $pixel,
      'campaign' => $campaign,
      'countries'  =>  CountryListFacade::getList('en'),
      'country_group' => $country_group
    ];
  }

  public function index() {
    return view('/pages/redirects/step-url/custom-url', $this->compactData);
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
