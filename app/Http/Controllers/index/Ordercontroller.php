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

    //确认订单
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
    //订单列表
    public function order_lists(Request $request)
    {
    	$value=$request->session()->get('name');
         //dd($value);
        if(empty($value)){
            return redirect('index/login');
        }
        //dd($data);
    	$uid=DB::table('user')->where('name',['name'=>$value])->first('id');
    	//dd($uid);
    	$uid=$uid->id;
    	$order_info = Order::where(['uid'=>$uid])->orderBy('add_time','desc')->paginate(5);
    	// dd($order_info);
    	$order = $order_info->toArray()['data'];
    	
    	$state_list = [1=>'待支付',2=>'已支付',3=>'已过期',4=>'用户删除'];
    	//十分钟取消订单
    	foreach($order as $k=>$v){
    		$order[$k]['end_time'] = date('Y/m/d H:i:s',$v['add_time'] + 10 * 60);
    		$order[$k]['order_state'] = $state_list[$v['state']];
    	}
        
    	return view('index.order_lists',['order_info'=>$order_info,'order'=>$order]);
    }
}
