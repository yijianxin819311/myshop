<?php

namespace App\Http\Controllers\index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\index\Model\Cart;
use App\Http\admin\Model\Goods;
use App\Http\index\Model\Order;

class Ordercontroller extends Controller
{
	//添加订单
    public function add(Request $request)
    { 
    	 $value=$request->session()->get('name');
         //dd($value);
        if(empty($value)){
            return redirect('index/login');
        }
        // $data=$request->all();
        //dd($data);
    	$uid=DB::table('user')->where('name',['name'=>$value])->first('id');
    	//dd($uid);
    	$uid=$uid->id;
    	 //dd($uid);
    	//订单id随机
    	$oid=time().rand(1000,9999);
    	//dd($oid);
    	$res=Order::insert([
    			'oid'=>$oid,
    			'uid'=>$uid,
    			'pay_time'=>time(),
    			'add_time'=>time(),
    		]);
    	//dd($res);
    	if($res){
    		return redirect('index/order_list');
    	}else{
    		return redirect('order/add');
    	}
    }
    public function order_list(Request $request)
    {
    	$res=$request->all();
    	//dd($res);
    	$data = Cart::get();
        //dd($res);
        $total = 0;
        foreach($data->toArray() as $v){
            $total += $v['goods_price'];
        }
    	$oid=Order::get('oid')->toArray();
    	//dd($oid);
    	$order=Order::where(['oid'=>$oid])->get();
    	//dd($order);
    	return view('index.order_list',['order'=>$order],['total'=>$total]);
    }
}
