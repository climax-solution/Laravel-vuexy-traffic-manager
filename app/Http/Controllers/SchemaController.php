<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchemaController extends Controller
{
    public function index()
    {
        return view('/pages/schemas/index', [
            'pageConfigs' => $this->pageConfigs,
        ]);
    }
    public function create()
    {
        return view('/pages/schemas/create', [
            'pageConfigs' => $this->pageConfigs,
        ]);
    }
}
