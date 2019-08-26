<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceController extends Controller
{
   public function price()
   {
   		$city="北京油价";
   		$url="http://shopdemo.18022480300.com/price/api";
   		$re=file_get_contents($url);
   		$res=json_decode($re,1)['result'];
   		//dd($res);
   		$preg_str='/^.*?油价$/';
   		$preg_result=preg_match($preg_str,$city,$preg);
   		//dd($preg_result);
   		$arr=substr($city,0,-6);
   		// dd($arr);
   		$redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
   		$key="city:price";
   		$num=$redis->get($key);
   		//dd($num);
   		$support_arr=[];

   		foreach($res as $v)
   		{
   			$support_arr[]=$v['city'];
   		}
   		dd($support_arr);
   }
}
