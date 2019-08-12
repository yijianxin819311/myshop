<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\admin\model\Train as Trains;
use DB;
class Train extends Controller
{
    public function add(Request $request)
    {
    	// echo 11;die;
    	$res=$request->all();
    	return view('admin.add');
    }
    public function add_do(Request $request)
    {
    	$res=$request->all();
    	//dd($res);
    	 unset($res['_token']);
    	 //dd($res);
    	 $result=DB::table('train')->insert($res);
    	 //dd($result);
    	 if($result){
    	 	 return redirect('admin/list');
    	 }
    }
    public function list(Request $request)
    {

        $res=$request->all();

        // dd($res);
        // 链接redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        //判断redis里面有没有key
         if(!$redis->get('train_info')){
                // 判断搜索条件是否存在
            if(!empty($res['strcfd']) || !empty($res['endddd'])){ 
                 // dd($res['strcfd']);
                 // 记录搜索次数
                 $redis->incr('num');
                 $result=DB::table('train')
                 ->where('cfd','like',"%{$res['strcfd']}%")
                 ->where('ddd','like',"%{$res['endddd']}%")
                 ->get();
                 
             
            }else{
                $result=DB::table('train')->get();
            }
            //redis获取访问次数
            $num=$redis->get('num');
            if($num>5){
                $redis_info=json_decode(json_encode($result));
                $redis->set('train_info',$redis_info,3*60);
            
            }
         }
        else{
            $list=json_decode($redis->get('train_info'),true);
          }  
         echo "访问次数:".$redis->get('num');
        
        //var_dump($result);
        return view('admin.list',['list'=>$result,'strcfd'=>$res['strcfd'],'endddd'=>$res['endddd']]);
    }
}
