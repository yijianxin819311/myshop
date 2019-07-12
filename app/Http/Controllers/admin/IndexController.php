<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index(Request $request)
    {
    	echo 22;
    }
    public function login(Request $request)
    {
    	return view('login');
    }
     public function login_do(Request $request)
     {
     	$res=$request->all();
     }
     public function register(Request $request)
     {
     	return view('register');
     }
}
