<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceController extends Controller
{
   public function price()
   {
   	$redis = new \Redis();
            $redis->connect('127.0.0.1','6379');
            $app = app('wechat.official_account');
   		 //业务逻辑
            $price_info = file_get_contents('http://shopdemo.18022480300.com/price/api');
            $price_arr = json_decode($price_info,1);
            //dd($price_arr)['result'];
            foreach($price_arr['result'] as $v){
            	// dd($price_arr['result']);
                if($redis->exists($v['city'].'信息')){
                    $redis_info = json_decode($redis->get($v['city'].'信息'),1);
                     // dd($redis_info[$k]);
                    //DD($v);
                    foreach ($v as $k=>$vv){
                    	  //dd($redis_info[$k]);dd($vv);dd($v);
                        if($vv != $redis_info[$k]){
                        	//dd($vv);
                        	//dd($redis_info[$k]);
                            //推送模板消息
                            $openid_info = $app->user->list($nextOpenId = null);
                            $openid_list = $openid_info['data'];
                            foreach ($openid_list['openid'] as $vo){
                                $app->template_message->send([
                                    'touser' => $vo,
                                    'template_id' =>'mF9Mmc19NAUtT7FtC2Sfm7fVHDCwagLbll6G-c0aUuc',
                                    'url' => 'http://yijianxin.cn',
                                    'data' => [
                                        'first' => '',
                                        'keyword1' => '',
                                        'keyword2' => '',
                                    ],
                                ]);
                            }
                        }
                    }
                }
            }die;
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
