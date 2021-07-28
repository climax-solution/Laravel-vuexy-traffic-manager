<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrafficController extends Controller
{
    public function index(Request $request)
    {
        return view('/pages/traffic-manager', [
            'pageConfigs' => $this->pageConfigs,
        ]);
    }
}
