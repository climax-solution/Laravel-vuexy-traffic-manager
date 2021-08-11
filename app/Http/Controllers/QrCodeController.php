<?php

namespace App\Http\Controllers;

use App\Models\QrCode as ModelsQrCode;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Monarobase\CountryList\CountryListFacade;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Str;

class QrCodeController extends Controller
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
  }

  public function index() {
    return view('/pages/redirects/qrcode', $this->compactData);
  }

  public function createNewQrCode(Request $request) {
    $input = $request->except('_token');
    $input['uuid'] = Str::random(7);
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
    $new = ModelsQrCode::create($data);
    $input['table_name'] = 'qr_code';
    $input['item_id'] = $new->id;
    $redirectData['user_id'] = auth()->user()->id;
    Redirect::create($input);
    return response()->json(['file' => asset($file_name) ]);
  }
}
