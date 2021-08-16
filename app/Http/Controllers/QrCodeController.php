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
    return view('/pages/redirects/qrcode', $compactData);
  }

  public function createNewQrCode(Request $request) {
    $input = $request->except('_token');
    $input['uuid'] = Str::random(7);
    $redirect = Redirect::where('id', $input['id'])->first();
    unset($input['id']);
    $qrCode = new QrCode(env('APP_URL').'/r/'.$input['uuid']);
    $qrCode->setSize(400);
    $qrCode->setMargin(10);
    $qrCode->setWriterByName('png');
    $qrCode->setEncoding('UTF-8');
    $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
    $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
    $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
    $qrCode->setValidateResult(false);
    $qrCode->setRoundBlockSize(true);
    $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
    header('Content-Type: '.$qrCode->getContentType());
    $file_name= 'qrcode/'.time().'.png';
    $qrCode->writeFile(public_path('/'.$file_name));
    $data = [
      'img_path' => $file_name,
    ];
    if (isset($redirect)) {
      $old_qr = ModelsQrCode::where('id', $redirect->item_id)->first();
      unlink(public_path('/'.$old_qr->img_path));
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
}
