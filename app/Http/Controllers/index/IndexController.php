<?php

namespace App\Http\Controllers\index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\index\Model\Cart;
use App\Http\admin\Model\Goods;
use App\Http\admin\Model\User;

use App\Http\index\Model\Order;
class IndexController extends Controller
{
    public function index(Request $request)
    {
    	$res=$request->all();
        //dd($res);
        $result=DB::table('goods')->get();
        $value=$request->session()->get('name');
        //dd($result);
    	return view('index.index',['goods'=>$result,'value'=>$value]);
    }
     public function login(Request $request)
    {
    	return view('index.login');
    }
     public function login_do(Request $request)
     {
     	$data=$request->all();
//          dump($data);die;
            $where=[
                ['name','=',$data['names']],
                ['password','=',$data['password']],
            ];
            $res=User::where($where)->insert($res);
            // dd($res);
            if($res){
                $request->session()->put('name',$data['names']);
                $request->session()->put('id',$res['id']);

                return redirect('index');
            }else{
                return redirect('index/login');
            }

     }
     public function register(Request $request)
     {
     	return view('index.register');

     }
      public function register_do(Request $request)
     {
        // $validatedData = $request->validate
        //  ([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'password' => 'required',
        // ],
        //     ['name.required'=>'名字必填',
        //     'email.required'=>'邮箱必填',
        //     'password.required'=>'密码必填',
            
           
        // ]);
     	$res=$request->all();
     	// dd($res);
     	$result=DB::table('user')->insert(['name'=>$res['names'],'email'=>$res['email'],'password'=>$res['password'],'reg_time'=>time()]);
     	// dd($result);
     	if($result){
     		return redirect('index/login');
     	}

     }

      public function goodsdetail(Request $request)
    {
        
        $id=$request->all();
        //dd($res);
        $result=DB::table('goods')->where(['id'=>$id['id']])->first();
        //dd($result);
        $car=Cart::where(['goods_id'=>$id,'uid'=>session('id')])->count();
        //dd($car);
        return view ('index.goodsdetail',['goods'=>$result,'car'=>$car]);
    }
    //添加购物车
    public function cart(Request $request)
    {
        //echo 11;die;
         $value=$request->session()->get('name');
         //dd($value);
        if(empty($value)){
            return redirect('index/login');
        }
        $id=$request->all();
        
        //dd($id);
        $goods=Goods::where('id',$id)->first();
        
         $uid=session('id');
       
        $res=Cart::insert([
            'uid'=>$uid,
            'goods_id'=>$goods['id'],
            'goods_name'=>$goods['goods_name'],
            'goods_pic'=>$goods['goods_pic'],
            'add_time'=>time(),
            'goods_price'=>$goods['goods_price'],

            ]);
        //dd($res);
        if($res){
            return redirect('index/cartlist');
        }else{
            return redirect('index/goodsdetail');
        }
    }
    //购物车列表
    public function cartlist(Request $request)
    {
        
        $res = Cart::orderby('add_time','desc')->get();
        //dd($res);
        $total = 0;
        foreach($res->toArray() as $v){
            $total += $v['goods_price'];
        }
        //dd($total);
        return view('index/cartlist',['cart'=>$res,'total'=>$total]);
    }
     //购物车删除
    public function cartdelete(Request $request){
        $id=$request->all();
    // dd($id);
        $res=Cart::where('id',$id)->delete();
        if($res){
            return redirect('index/cartlist');
        }else{
            return redirect('index/cartlist');
        }
    }
    //    订单视图
    public function order_list()
    {
        $order=Order::where('uid',session('id'))->orderBy('add_time','desc')->limit(6)->get();
//        dd($order);
//        $order['state']=['1'=>'未支付','2'=>'已支付','3'=>'过期','4'=>'已取消'];
        $order=$order->toArray();
        $state_list = [1=>'待支付',2=>'已支付',3=>'已过期',4=>'用户删除'];
        //十分钟取消订单
        foreach($order as $k=>$v){
            $order[$k]['end_time'] =date('Y-m-d H:i:s', $v['add_time'] + 10 * 60);
            $order[$k]['order_state'] = $state_list[$v['state']];
        }
        return view('index/order_list', ['order'=>$order]);
    }

}
