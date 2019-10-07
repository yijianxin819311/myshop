<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\User;
use App\Http\Model\Wechat;
class WeatherController extends Controller
{
    //登录接口
    public function login(Request $request)
    {
//        dd($request->all());
        $appid=$request->input('appid');
        $appsert=$request->input('appsert');
        $userdata=Wechat::where(['appid'=>$appid,'appsert'=>$appsert])->first()->toArray();
        //dd($userdata);
        if(empty($userdata)){
            return json_encode(['msg'=>'用户名密码错误','code'=>201],JSON_UNESCAPED_UNICODE);
        }
        $token=md5($userdata['id'].time());
        $user=Wechat::where(['id'=>$userdata['id']])->update([
            'access_token'=>$token,
            'reg_time'=>time()+7200,
        ]);
        if($user){
            return json_encode(['msg'=>'登录成功','code'=>200,'access_token'=>$token],JSON_UNESCAPED_UNICODE);
        }

    }
    //退出登录接口开发
    public function login_out(Request $request)
    {
        //销毁token
        $user_id = $request->input('user_id');
        $user = User::where(['user_id' => $user_id])->first()->toArray();
        //d($user);
        unset($user['token']);
        if ($user) {
            return json_encode(['msg' => '退出成功', 'code' => 200], JSON_UNESCAPED_UNICODE);
        }
    }
    //天气接口开发
    public function weather_do(Request $request)
    {
        $citynm=$request->input('citynm');
        //dd($citynm);
        $url="http://api.k780.com:88/?app=weather.future&weaid=$citynm&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
        $data=file_get_contents($url);
        $data=json_encode(json_decode($data));
        $callback=$_REQUEST['jsoncallback'];
        if($callback){
            echo $callback."(".$data.")";
        }else{
            echo "(".$data.")";
        }

    }
    public function weather(){
        return view('api/weather');
    }
    public function register()
    {
        $appid = uniqid(rand(), true);
        //dd($appid);
        $appsert = md5(time() . mt_rand(0, 1000));
        //dd($appsert);
        $data = Wechat::create([
            'appid' => $appid,
            'appsert' => $appsert
        ]);
        //dd($data);
        if ($data) {
            return json_encode(['code' => 200, 'msg' => '注册成功'], JSON_UNESCAPED_UNICODE);
        }
    }
        //程序访问域名
        public function web()
        {
            return view('api/web');
        }


}
