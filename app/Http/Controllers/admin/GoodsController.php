<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    public function goods_add(Request $request)
    {
    	return view('admin.goods_add');
    }
    public function goods_add_do(Request $request)
    {
    	$path = $request->file('goods_pic')->store('goods');
    	//dd($path);
    	 echo asset('storage').$path;
    }
}
