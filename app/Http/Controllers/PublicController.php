<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PublicController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function main_menu() {
        $user = Auth::user();
        return view('public.menu', ['user' => $user]);
    }
}
