<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PixelController extends Controller
{
    public function index()
    {
        return view('/pages/pixels/index', [
            'pageConfigs' => $this->pageConfigs,
        ]);
    }
    public function create()
    {
        return view('/pages/pixels/create', [
            'pageConfigs' => $this->pageConfigs,
        ]);
    }
}
