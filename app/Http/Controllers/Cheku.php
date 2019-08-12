<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Car;
use DB;
class Cheku extends Controller
{
    public  function login(){
        return view('cheku/login');
    }
     public  function login_do(Request $request){
        $res=$request->all();
        //dd($res);
        unset($res['_token']);
        $result=DB::table('login')->first();
        if($result){
            $request->session()->put('name',$res['name']);
            
             return redirect('cheku/add');
        }
    }
    public  function add(Request $request){

        // 缓冲
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $cart_use_key = 'cart:cart_use_key';//cart键名 后边是值 已使用车位
        $cart_use_num = $redis->get($cart_use_key);
        //dd($cart_use_num);
        if(!$cart_use_num){
            $cart_use_num = 0;
        }
        //dd($cart_use_num);
        // 剩余车辆
        $cart_left_num = 400- $cart_use_num;
        //dd($cart_left_num);
       $value=$request->session()->get('name');
         //dd($value);
        if(empty($value)){
            echo "<script>alert('请先登录'),location.href='login'</script>";
        }
        
        return view('cheku/add',['cart_left_num'=>$cart_left_num]);
    }
    public  function adds(Request $request){
            
        return view('cheku/adds');
    }
     // 缓冲
    public function get_redis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        return $redis;
    }
     public  function adds_do(Request $request){
        // 缓冲
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $cart_use_key = 'cart:cart_use_key';
        $data = $request->all();
        unset($data['_token']);
        $in_car = Car::where(['cart_num'=>$data['cart_num'],'state'=>1])->value('cart_num');
        if(!empty($in_car)){
            echo "车已入库";die;
        }
        $data['add_time'] = time();
        $data['state'] = 1;
        // dd($data);
        $res = Car::insert($data);
        if($res){
            $redis->incr($cart_use_key);
            echo ("<script>alert('入库成功');location='add'</script>");
        }else{
            echo ("<script>alert('添加失败,系统错误');location='add'</script>");
        }
    }
    

    public  function out(Request $request){
        
        return view('cheku/out');
    }
    public  function out_do(Request $request){
        
       $req=$request->all();
       $re=Car::where(['cart_num'=>$req['cart_num'],'state'=>1])->select(['id','add_time'])->first();
       if(empty($re)){
        echo "车辆不存在";die;
       }
       $stop_time=time()-$re->add_time;//停车时间间隔,这里出来的是秒数
       //dd($stop_time);
       $pay_amount=0;//计费
       if($stop_time <15 *60){
        $pay_amount=0;
       }else if($stop_time >= 15 * 60 && $stop_time <= 6 * 3600){
            $pay_amount = ceil($stop_time/1800) * 2;
        }else{
            $pay_amount = 12 * 2;
            $pay_amount += ceil(($stop_time-6*3600)/3600) * 1;
        }
        if($stop_time >= 3600){
            $time_info = floor($stop_time / 3600).'时'.floor(($stop_time % 3600)/60).'分';
        }else{
            $time_info ='0时'.floor($stop_time/60).'分';
        }
        return view('cheku.chekuPrice',['pay_amount'=>(int)$pay_amount,'cart_num'=>$req['cart_num'],'time_info'=>$time_info,'cart_id'=>$re->id]);
    }
    public function del_price(Request $request){
      // 缓冲
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $cart_use_key = 'cart:cart_use_key';
        $cart_use_num = $redis->get($cart_use_key);
        if(!$cart_use_num){
            $cart_use_num = 0;
        }
        if($cart_use_num == 0){
            echo ("<script>alert('操作失败');location='cheku/add'</script>");
        }


        $data = $request->all();
        // dd($data);
        $res = Car::where(['id'=>$data['id']])->update([
            'state'=>2,
            'del_time'=>time(),
            'price'=>$data['price']
        ]);
        // dd($res);
        if(!$res){
            echo "操作失败!";die();
        }else{
            $redis->set($cart_use_key,$cart_use_num - 1);
            return redirect("cheku/add");
        }
        
    }

}
