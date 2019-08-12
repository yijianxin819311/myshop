<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Map extends Controller
{
    public function index(){
    	
    	return view('map/index');
    }
    public function add(Request $request){
    	$data= $request->all();
    	$address=$data['address'];
    	$path = "http://api.map.baidu.com/geocoder/v2/?address=$address&output=json&ak=CxF13N48UHZ12G8sIVpa2YTG";
		$re=file_get_contents($path,1);
		$res=json_decode($re,1);
		// dd($res);
		$info=$res['result']['location'];
		//dd($info);
		// echo "<pre>";//原样输出
		// print_r($res);
		return view('map/list',['address'=>$address,'info'=>$info]);
    }
    public function list(Request $request){

    }
}
