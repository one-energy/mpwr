<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        return view('castle.permission.index');
    }
}
