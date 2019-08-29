<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use EasyWeChat\Factory;
use App\Http\Tools\Wechat;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use EasyWeChat\Kernel\Messages\Text;
class Kecheng extends Controller
{
	public $request;
    public $wechat;
    public $redis;
    public $app;
    public function __construct(Request $request,Wechat $wechat)
    {
        $this->request = $request;
        $this->wechat = $wechat;
        $this->app = $app = app('wechat.official_account');
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1','6379');
    }

    //获取access_token
    public function  get_access_token()
    {
    	//获取access_token
    	$access_token="";
    	$redis=new \Redis();
    	$redis->connect('127.0.0.1','6379');
    	$access_key="weixin_access_token";
    	if($redis->exists($access_key)){
    		//从缓存取
    		$access_token=$redis->get($access_key);
    	}else{
    		//从微信接口获取
    		$access_re=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."");
    		//拉取关注用户列表
    		$result=json_decode($access_re,1);

    		$access_token=$result['access_token'];
    		$expire_time=$result['expires_in'];
    		//加人缓存
    		$redis->set($access_key,$access_token,$expire_time);
    	}
    	return $access_token;
    }
    /**
     * post请求
     * @param $url
     * @param $data
     * @return bool|string
     */
    public function post($url, $data = []){
        //初使化init方法
        $ch=curl_init();
        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送什么数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //发送请求
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }
     //微信登录
    public function login()
    {
        $redirect_uri="http://www.myshop.com/kecheng/code";
        //dd($redirect_uri);
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect ";
        //dd($url);
        header('Location:'.$url);   
    }
    public function code(Request $request)
    {
    	$res=$request->all();
    	$code=$res['code'];
    	$url=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."&code=".$code."&grant_type=authorization_code");
    	$result=json_decode($url,1);
    	$openid=$result['openid'];
    	$access_token=$result['access_token'];
    	//dd($openid);
    	$access_token=$this->get_access_token();
    	$user=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
    	//dd($user);
    	$user_info=json_decode($user,1);
    	$user_openid=DB::connection('mysql')->table("user_wechat")->where(['openid'=>$openid])->first();
    	// dd($user_openid);
    	if(!empty($user_openid)){
            //echo 11;
            //有数据 在网站有用户 user表有数据[ 登陆 ]
            $user_info = DB::connection('mysql')->table("users")->where(['id'=>$user_openid->uid])->first();
           //dd($user_info);
            $request->session()->put('username',$user_info->name);
            header('Location:add');
        }

    }
  
   public  function add()
   {
   		return view('kecheng/add');
   }
   public  function adds(Request $request)
   {
   		$res=DB::table('wechat_openid')->get();
   		return view('kecheng/adds',['list'=>$res]);
   }
    public  function addss(Request $request)
   {
   		$openid=$request->all();
        //dd($openid);
        return view('kecheng/addss',['openid'=>$openid['openid']]);
   }
   public function add_do(Request $request)
   {

   		$arr=$request->all();
   		//dd($arr);
      	$openid=DB::table('kecheng')->first();
      	if(empty($openid)){
      		$list=DB::table('kecheng')->insert([
            'openid'=>$arr['openid'],
            'first_kecheng'=>$arr['first_kecheng'],
            'two_kecheng'=>$arr['two_kecheng'],
            'three_kecheng'=>$arr['three_kecheng'],
            'four_kecheng'=>$arr['four_kecheng'],
            'add_time'=>time(),
        	]);
      	}else{
      		return redirect('kecheng/adds');
      	}
       //dd($user);
       
      if($list){
      	return redirect('kecheng/list');
      }
   }
   public function list()
  	{

        $re=DB::table('kecheng')->get();
        //dd($re);
        return view('kecheng/list',['list'=>$re]);
  	}
  	 public  function update(Request $request)
   {
   		$openid=$request->all();
       //dd($openid);
        return view('kecheng/update',['openid'=>$openid['openid']]);
   }
}
