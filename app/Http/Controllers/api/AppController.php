<?php

namespace App\Http\Controllers\api;

use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Regster;
use App\Http\Model\Order;
class AppController extends Controller
{
    public function regster(Request $request)
    {
        //echo 11;die;
        $phone=$request->input('phone');
        $verify_code=123456;
        $password=$request->input('password');
        $source=$request->input('source');
        if(!$phone){
            return json_encode(['status'=>0,'result'=>['msg'=>'手机不能为空']],JSON_UNESCAPED_UNICODE);
        }
        if(!$verify_code){
            return json_encode(['status'=>0,'result'=>['msg'=>'参数1不能为空']],JSON_UNESCAPED_UNICODE);
        }
        if(!$password){
            return json_encode(['status'=>0,'result'=>['msg'=>'参数2不能为空']],JSON_UNESCAPED_UNICODE);
        }
        if(!$source){
            return json_encode(['status'=>0,'result'=>['msg'=>'参数3不能为空']],JSON_UNESCAPED_UNICODE);

        }
        if($verify_code!=123456){
            return json_encode(['status'=>0,'result'=>['msg'=>'验证码错误']],JSON_UNESCAPED_UNICODE);
        }

        $data=Regster::where(['phone'=>$phone])->first();
        if($data){
            return json_encode(['status'=>0,'result'=>['msg'=>'手机号已被占用']],JSON_UNESCAPED_UNICODE);

        }
        $regsterData=Regster::create([
            'phone'=>$phone,
            'verify_code'=>123456,
            'password'=>$password,
            'source'=>$source,
        ]);
        if($regsterData){
            return json_encode(['status'=>0,'result'=>['msg'=>'注册成功']],JSON_UNESCAPED_UNICODE);
        }
    }
    //登录接口
    public function login_do(Request $request)
    {
//        dd($request->all());
        $phone=$request->input('phone');
        $password=$request->input('password');
        $userdata=Regster::where(['phone'=>$phone,'password'=>$password])->first();
        //dd($userdata);
        if(empty($userdata)){
            return json_encode(['msg'=>'用户名密码错误','code'=>201],JSON_UNESCAPED_UNICODE);
        }
        $token=md5($userdata['id'].time());
        $user=Regster::where(['id'=>$userdata['id']])->update([
            'token'=>$token,
            'createtime'=>time()+7200
        ]);
        if($user){
            return json_encode(['msg'=>'登录成功','code'=>200,'token'=>$token],JSON_UNESCAPED_UNICODE);
        }

    }
    //测试接口
    public function app(Request $request)
    {

        return view('api/app');
    }
    public function apps(Request $request)
    {
        $name=$request->input('name');
        $zhi=$request->input('zhi');
        $appdata=Regster::get()->toarray();
        var_dump($appdata);
    }
    //展示订单接口
    public function orderlist(Request $request)
    {
        $token=$request->input('token');
        if(!$token){
            return json_encode(['status'=>0,'result'=>['msg'=>'token为空']],JSON_UNESCAPED_UNICODE);

        }
        $orderdata=Order::get()->toarray();
        if($orderdata){
            return json_encode(['status'=>1,'result'=>['data'=>$orderdata,'msg'=>'订单显示成功']],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['status'=>0,'result'=>['msg'=>'订单为空']],JSON_UNESCAPED_UNICODE);
        }
    }


}
