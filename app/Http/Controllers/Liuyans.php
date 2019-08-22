<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Tools\Wechat;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
class Liuyans extends Controller
{
	
    public $wechat;
    public function __construct(Wechat $wechat)
    {
        
        $this->wechat = $wechat;
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
    public function logins()
    {
    	return view('liuyans/logins');
    }
     public function login()
    
    {
        $redirect_uri="http://www.myshop.com/liuyans/code";
        //dd($redirect_uri);
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect ";
        //dd($url);
        header('Location:'.$url);   
    }
    public function code(Request $request)
    {
    	$res=$request->all();
    	//dd($re);
    	$code=$res['code'];
    	//dd($code);
    	//获取access_token
        $url=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."&code=".$code."&grant_type=authorization_code");
        //dd($url);
        
        $result=json_decode($url,1);
        //dd($result);
        $access_token=$result['access_token'];
        $openid=$result['openid'];
        //dd($openid);
        $access_token=$this->get_access_token();
        //dd($access_token);
        //获取用户基本信息
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
       $user_infos = json_decode($wechat_user,1);

        //去user_wechat表查是否有数据openid
        $user_openid=DB::connection('mysql')->table("user_wechat")->where(['openid'=>$openid])->first();
        //dd($user_openid);
        if(!empty($user_openid)){
        	//有数据在网站用户users表登录
        	$user_info=DB::table('users')->where(['id'=>$user_openid->uid])->first();
        	//dd($user_info);
        	$request->session()->put('username',$user_info->name);
        	
        	$request->session()->put('uid',$user_openid->uid);
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->get_access_token()."";
           $data=[
                'touser'=>$user_openid->openid,
                'template_id'=>'TluI_CS3tqeTt8CBLpxgAbezQ_iahlh62pYdOsz3Vrs',
                'url'=>'http://www.baidu.com',
                'data'=>[
                    'first'=>[
                        'value'=>'欢迎',
                        'color'=>''
                    ],
                    'keyword1'=>[
                        'value'=>'您',
                        'color'=>''
                    ],
                    'keyword2'=>[
                        'value'=>'登录',
                        'color'=>''
                    ],
                    
                ]

           ];
          $re=$this->post($url,json_encode($data));
           //dd($re);
        
        	header('location:liuyans_list');
        }else{
        	//没有数据 注册信息  insert user  user_openid   生成新用户
           // DB::beginTransaction();//开启事务
            DB::connection('mysql')->beginTransaction();
            
            $user_result = DB::table('users')->insertGetid([
                'password'=>'',
                'name'=>$user_infos['nickname'],
                'reg_time'=>time(),
                ]);
       
            
            //dd($user_result);
            $openid_result = DB::connection('mysql')->table('user_wechat')->insert([
                'uid'=>$user_result,
                'openid' => $openid,
                'name'=>$user_infos['nickname'],
                'reg_time'=>time(),
            ]);
            //dd($openid_result);
            DB::connection('mysql')->commit();
            //登陆操作
            $user_info = DB::connection('mysql')->table("users")->where(['id'=>$user_openid['uid']])->first();
            $request->session()->put('username',$user_info['name']);
            header('Location:liuyans_list');
        }

        }
    public function add(Request $request)
    {
    	$req=$request->all();
    	//dd($uid);
    	return view('liuyans/add',['uid'=>$req['uid']]);
    }
    public function add_do(Request $request)
    {
    	// $value=$request->session()->put('username');
    	//  dd($value);
    	
    	$req=$request->all();
    	//dd($req);
    	//发送模板留言信息
    	$openid=DB::table('user_wechat')->where(['uid'=>$req['uid']])->value('openid');
        
    	//dd($openid);
    	 $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->get_access_token()."";
           $data=[
                'touser'=>$openid,
                'template_id'=>'ygGOIjNPP2kyVT4B24Yp7Mv8WfBf12ImJxAFoBW7NAc',
                'url'=>'http://www.baidu.com',
                'data'=>[
                    'first'=>[
                        'value'=>'留言消息',
                        'color'=>''
                    ],
                    'keyword1'=>[
                        'value'=>$this->wechat->wechat_user_info()['nickname'],
                        'color'=>''
                    ],
                    'keyword2'=>[
                        'value'=>$req['neirong'],
                        'color'=>''
                    ],
                    
                ]

           ];
          $re=$this->post($url,json_encode($data));
          //dd($re);
          //我的留言入库
    	$result=DB::table('liuyanss')->insert(['name'=>session('username'),'uid'=>session('uid'),'neirong'=>$req['neirong'],'add_time'=>time()]);
    	//dd($result);
    	
    	if($result){
    		return redirect('liuyans/lists');
    	}
    	
    }
    public function liuyans_list(Request $request)
    {
        
    	
    	$re=DB::table('user_wechat')->get();
    	//dd($re);
    	return view('liuyans/liuyans_list',['list'=>$re]);
    }
   public function  lists(Request $request)
   {
   		$re=$re=DB::table('liuyanss')->get();
   		//dd($re);
   		return view('liuyans/lists',['list'=>$re]);
   }
}
