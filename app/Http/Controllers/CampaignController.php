<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        return view('/pages/campaigns/index', [
            'pageConfigs' => $this->pageConfigs,
        ]);
    }
    public function create()
    {
        return view('/pages/campaigns/create', [
            'pageConfigs' => $this->pageConfigs,
        ]);
    }
}
