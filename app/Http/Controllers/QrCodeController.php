<?php

namespace App\Http\Controllers;

use App\Models\QrCode as ModelsQrCode;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Monarobase\CountryList\CountryListFacade;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
  public function __construct()
  {
    $this->compactData = config('constants');
    $this->compactData['countries'] = CountryListFacade::getList('en');
  }

  public function index(Request $request) {
    $id = $request->query('id');
    $url_data = Redirect::where('id', $id)->where('table_name', 'qr_code')->first();
    $compactData = $this->compactData;
    $compactData['url_data'] = !$url_data ? [] : $url_data;
    $compactData['id'] = !$url_data ? -1 : $id;
    if (isset($url_data->item_id)) {
      $item = ModelsQrCode::where('id', $url_data->item_id)->first();
      $compactData['fore_color'] = $item->fore_color;
      $compactData['back_color'] = $item->back_color;
    }
    else {
      $compactData['fore_color'] = '#000000';
      $compactData['back_color'] = '#ffffff';
    }
    return view('/pages/redirects/qrcode', $compactData);
  }

  public function createNewQrCode(Request $request) {
    $input = $request->except('_token');
    $input['uuid'] = Str::random(7);
    $redirect = Redirect::where('id', $input['id'])->first();
    unset($input['id']);
    $fore = $this->convertRGB($input['fore_color']);
    $back = $this->convertRGB($input['back_color']);
    $qrCode = new QrCode(env('APP_URL').'/r/'.$input['uuid']);
    $qrCode->setSize(400);
    $qrCode->setMargin(10);
    $qrCode->setWriterByName('png');
    $qrCode->setEncoding('UTF-8');
    $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
    $qrCode->setForegroundColor(['r' => $fore[0], 'g' => $fore[1], 'b' => $fore[2], 'a' => 1]);
    $qrCode->setBackgroundColor(['r' => $back[0], 'g' => $back[1], 'b' => $back[2], 'a' => 0.5]);
    $qrCode->setValidateResult(false);
    $qrCode->setRoundBlockSize(true);
    $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
    header('Content-Type: '.$qrCode->getContentType());
    $file_name= 'qrcode/'.time().'.png';
    $qrCode->writeFile(public_path('/'.$file_name));
    $data = [
      'img_path' => $file_name,
      'fore_color' => $input['fore_color'],
      'back_color' => $input['back_color']
    ];
    unset($input['fore_color']); unset($input['back_color']);
    if (isset($redirect)) {
      $old_qr = ModelsQrCode::where('id', $redirect->item_id)->first();
      if (file_exists(public_path('/'.$old_qr->img_path))) unlink(public_path('/'.$old_qr->img_path));
      ModelsQrCode::where('id', $redirect->item_id)->delete();
    }
    $new = ModelsQrCode::create($data);
    $input['table_name'] = 'qr_code';
    $input['item_id'] = $new->id;
    $input['user_id'] = auth()->user()->id;
    if (!isset($redirect)) Redirect::create($input);
    else {
      Redirect::where('id', $redirect->id)->update($input);
    }
    return response()->json(['file' => asset($file_name) ]);
  }

  public function convertRGB($hexValue) {
    $arrayRGB = [];
    $hexValue = str_replace("#", "", $hexValue);
    $split_hex_color = str_split($hexValue, 2);
    $rgb1 = hexdec($split_hex_color[0]);
    $rgb2 = hexdec($split_hex_color[1]);
    $rgb3 = hexdec($split_hex_color[2]);

    array_push($arrayRGB, $rgb1, $rgb2, $rgb3);

    return $arrayRGB;
  }
}
