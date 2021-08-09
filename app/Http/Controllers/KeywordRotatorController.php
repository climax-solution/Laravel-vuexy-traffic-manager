<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Monarobase\CountryList\CountryListFacade;

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
  }
  public function index() {
    return view('/pages/redirects/keyword-rotator', $this->compactData);
  }
}
