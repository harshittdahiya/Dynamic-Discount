<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home()
    {
        $banners = \App\Models\Banner::active()->get();
        return view('user.home', compact('banners'));
    }
}
