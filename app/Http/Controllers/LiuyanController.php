<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class LiuyanController extends Controller
{
    public function login(Request $request)
    {
    	
    	return view('liuyand/login');
    }
}
