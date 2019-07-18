<?php

namespace App\Http\Controllers\index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\index\Model\Cart;
use App\Http\admin\Model\Goods;
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
     	$res=$request->all();
            $where=[
                ['name','=',$res['names']],
                ['password','=',$res['password']],
            ];
     	$result=DB::table('user')->where($where)->first();
       // dd($result);
        if(empty($result)){
            echo "<script>alert('账号密码错误'),location.href='/index/login'</script>";
        }else{
            //存入session
            $request->session()->put('name',$res['names']);

            return redirect('index');
        }
     }
     public function register(Request $request)
     {
     	return view('index.register');

     }
      public function register_do(Request $request)
     {
        $validatedData = $request->validate
         ([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ],
            ['name.required'=>'名字必填',
            'email.required'=>'邮箱必填',
            'password.required'=>'密码必填',
            
           
        ]);
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
        
        $res=$request->all();
        //dd($res);
        $result=DB::table('goods')->where(['id'=>$res['id']])->first();
        //dd($result);
        return view ('index.goodsdetail',['goods'=>$result]);
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
        //dd($goods);
        $uid=DB::table('user')->where('name',['name'=>$value])->first('id');
         // dd($uid);
          $uid=$uid->id;
  
        $res=Cart::insert([
            'uid'=>$uid,
            'goods_id'=>$goods['id'],
            'goods_name'=>$goods['goods_name'],
            'goods_pic'=>$goods['goods_pic'],
            'add_time'=>time(),
            'goods_price'=>$goods['goods_price'],

            ]);
        if($res){
            return redirect('index/cartlist');
        }else{
            return redirect('index/goodsdetail');
        }
    }
    //购物车列表
    public function cartlist(Request $request)
    {
        
        $res = Cart::get();
        //dd($res);
        $total = 0;
        foreach($res->toArray() as $v){
            $total += $v['goods_price'];
        }
        //dd($total);
        return view('index/cartlist',['cart'=>$res,'total'=>$total]);
    }
     public function confirm_pay(Request $request)
     {
       $res = Cart::get();
        //dd($res);
        $total = 0;
        foreach($res->toArray() as $v){
            $total += $v['goods_price'];
        }
        //dd($total);
        return view('index/confirm_pay',['cart'=>$res,'total'=>$total]);
     }
}
