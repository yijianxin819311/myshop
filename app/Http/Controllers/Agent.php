<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
class Agent extends Controller
{
	public function userlist()
	{
		//用户列表
	    $user=DB::table('users')->get();
	    // dd($user);
	    return view('agent.userList',['user'=>$user]);
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
	 /**
     * 生成专属二维码
     */
    
    public function creat_qrcode(Request $request)
    {
    	$uid=$request->all()['uid'];//用户uid就是用户专属推广二维码
    	// dd($uid);
    	$access_token=$this->get_access_token();
       $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
       //dd($url);
       $data=[
       	'expire_seconds'=>24*3600*60,
       	'action_name'=>'QR_LIMIT_STR_SCENE',
       	'action_info'=>[
       		'scene'=>[
       			'scene_str'=>$uid,
       		]
       	]
       ];
       //dd($data);
       $user_info=$this->post($url,json_encode($data));
       // dd($user_info);
       $ticket=json_decode($user_info,1)['ticket'];
       //dd($ticket);
        
        //把二维码存入laravel
       $re="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket)."";
       //dd($re);
       $rea=file_get_contents($re);
       $client=new Client();
       $res=$client->get($re);
       //dd($res);
       //获取文件名
      $h=$res->getHeaders();
      //dd($h);
      $ext=explode('/',$h['Content-Type'][0])[1];//取后缀名
      //dd($ext);
      $file_name=time().rand(1000,9999).'.'.$ext;//生成新的
      //dd($file_name);
      //保存图片
      $path='qrcode/'.$file_name;
      // dd($path);
      $rea=Storage::disk('local')->put($path,$res->getBody());
      //dd($rea);
     $qrcode_url=env('APP_URL').'/storage/'.$path;
     //dd($qrcode_url);
     //存入数据库
      $result=DB::table('userss')->where(['id'=>$uid])->update([
            'qrcode_url' => $qrcode_url,
            'agent_code' => $uid
        ]);
      //dd($result);
      if($result){
      	return redirect('agent/list');
      }
    }
    public function list()
    {
    	$user_info = DB::table('userss')->get();
    	//dd($user_info);
        return view('agent.list',['user_info'=>$user_info]);
    }
}
    
