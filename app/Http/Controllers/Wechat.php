<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class Wechat extends Controller
{
	// public $request;
 //    public $wechat;
 //    public function __construct(Request $request,Wechat $wechat)
 //    {
 //        $this->request = $request;
 //        $this->wechat = $wechat;
 //    }

	//微信登录
	public function login()
	{
		$redirect_uri="http://www.myshop.com/wechat/code";
		//dd($redirect_uri);
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect ";
		//dd($url);
		header('Location:'.$url);	
	}

	public function code(Request $request)
	{
		$res=$request->all();
		// dd($res);
		$code=$res['code'];
		//dd($code);
		//获取access_token
		$url=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."&code=".$code."&grant_type=authorization_code");
		//dd($url);
		// $re=file_get_contents($url);
		$result=json_decode($url,1);
		//dd($result);
		$access_token=$result['access_token'];
		$openid=$result['openid'];
		// dd($access_token);
		// dd($openid);
		$access_token = $this->get_access_token();
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $user_infos = json_decode($wechat_user,1);
        //dd($user_infos);
		 //去user_openid 表查 是否有数据 openid = $openid
        $user_openid = DB::connection('mysql')->table("user_wechat")->where(['openid'=>$openid])->first();
        //dd($user_openid);
        if(!empty($user_openid)){
        	//echo 11;
            //有数据 在网站有用户 user表有数据[ 登陆 ]
            $user_info = DB::connection('mysql')->table("users")->where(['id'=>$user_openid->uid])->first();
           //dd($user_info);
            $request->session()->put('username',$user_info->name);
            header('Location:user_list');
        }else{
        	// echo 22;die;
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
            ]);
            //dd($openid_result);
            DB::connection('mysql')->commit();
            //登陆操作
            $user_info = DB::connection('mysql')->table("users")->where(['id'=>$user_openid['uid']])->first();
            $request->session()->put('username',$user_info['name']);
            header('Location:user_list');
        }

		
	}

public function get_user_info()
    {
    	$access_token=$this->get_access_token();
    	$user_info=$this->get_user_list();
    	$user=DB::table('wechat_openid')->first();
    	if($user==null){
    		foreach ($user_info['data']['openid'] as $v) {
    			$users=DB::table('wechat_openid')->insert([
    					'openid'=>$v['openid'],
    					'subscribe'=>$v['subscribe'],
    					'nickname'=>$v['nickname'],
    					'city'=>$v['city'],
    					'sex'=>$v['sex'],
    					'headimgurl'=>$v['headimgurl'],
    					'language'=>$v['language'],
    					'add_time'=>$v['subscribe_time'],
    					'province'=>$v['province'],
    					'country'=>$v['country'],
    				]);
    			
    		}
    		
    	}
    	//echo 11;
    	echo "<script>history.go(-1)</script>";
    }





	//粉丝信息
    // public function get_user_info()
    // {
    // 	$access_token=$this->get_access_token();
    // 	$user_info=$this->get_user_list();
    // 	 //dd($user_info);
    	
    // 	foreach ($user_info['data']['openid'] as $v ) {
    // 		$infos=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$v}&lang=zh_CN");
    // 		$user_infos=json_decode($infos,1);
    // 		$user[]=$user_infos;
    // 	}
    // 	// dd($user);
    // 	 $userss=DB::table('wechat_openid')->first();
    // 	 //dd($userss);
    // 	if($userss==null){
    // 		foreach ($user as $v) {
    // 			$users=DB::table('wechat_openid')->insert([
    // 					'openid'=>$v['openid'],
    // 					'subscribe'=>$v['subscribe'],
    // 					'nickname'=>$v['nickname'],
    // 					'city'=>$v['city'],
    // 					'sex'=>$v['sex'],
    // 					'headimgurl'=>$v['headimgurl'],
    // 					'language'=>$v['language'],
    // 					'add_time'=>$v['subscribe_time'],
    // 					'province'=>$v['province'],
    // 					'country'=>$v['country'],
    // 				]);
    // 		}
    		
    		
    // 	}

    // 			echo "<script>history.go(-1)</script>";

    // }
    
   
    //粉丝列表
     public function get_user_list()
    {
    	$access_token=$this->get_access_token();
    	//dd($access_token);
    	$info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=");
    	$user_info=json_decode($info,1);
    	
    	return $user_info;

    }
    public function user_list(Request $request)
    {
    	$user=$request->all();
    	$user_info=DB::table('wechat_openid')->select('id','nickname')->get();
    	 //dd($user_info);
    	return view('wechat/user_list',['res'=>$user_info]);
    }
    public function lists(Request $request)
    {
    	$id=$request->id;
    	//dd($user);
    	$user_info=DB::table('wechat_openid')->where('id',$id)->first();
    	// $user_info=DB::table('wechat_openid')->get();
    	// dd($user_info);
    	return view('wechat/lists',['res'=>$user_info]);
    }

    //获取access_token
    public function get_access_token()
    {
    	//获取access_token
    	$access_token="";
    	$redis=new \Redis();
    	$redis->connect('127.0.0.1','6379');
    	$access_key="wechat_access_token";
    	if($redis->exists($access_key)){
    		//从缓存取
    		$access_token=$redis->get($access_key);
    	}else{
    		//从微信接口获取
    		$access_re=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."");
	    	//dd($access_re);
	    	//拉取关注用户列表
	    	$access_result=json_decode($access_re,1);
	    	$access_token=$access_result['access_token'];
	    	$expire_time=$access_result['expires_in'];
	    	//dd($expire_time);
	    	//加人缓存
	    	$redis->set($access_key,$access_token,$expire_time);
    	}
    	
    	return $access_token;
    }

        /**
     * 模板列表
     */
    public function template_list()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token='.$this->get_access_token();
        $re = file_get_contents($url);
        $res=json_decode($re,1);
        $res=$res['template_list'];
        //dd($res);
        return view('wechat/template_list',['res'=>$res]);
    }
    public function del_template(Request $request)
    {
    	//$id=$request->all();
    	//dd($id);
        $url = 'https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token='.$this->get_access_token();
        $data = [
            'template_id' => 'CILXXtAl-6QZC2G8k4JCHSM8YXGdtABBMf7eSJ_aTM0',
        ];
        //dd($data);
        $re = $this->post($url,json_encode($data));
        dd($re);
    }
    /**
     * 推送模板消息
     */
    public function push_template()
    {
        $openid_info = DB::connection('mysql')->table("wechat_openid")->select('openid')->limit(10)->get()->toArray();
      	//dd($openid_info);
        foreach($openid_info as $v){
        	$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->get_access_token()."";
           $data=[
           		'touser'=>$v->openid,
           		'template_id'=>'2i_oOXMLcOzImHbYGhWIc4pXgzF_6PKhIwjZWdCHL7A',
           		'url'=>'http://www.baidu.com',
           		'data'=>[
           			'first'=>[
           				'value'=>'小柒流年',
           				'color'=>''
           			],
           			'keyword1'=>[
           				'value'=>'爱',
           				'color'=>''
           			],
           			'keyword2'=>[
           				'value'=>'一生一世',
           				'color'=>''
           			],
           			'remark'=>[
           				'value'=>'备注',
           				'color'=>''
           			],
           		]

           ];
          $re=$this->post($url,json_encode($data));
           dd($re);
        }
         

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

    //素材管理
    public function sucai()
     {
    // 	 $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->get_access_token();
    //     $data = ['type'=>'image','offset'=>0,'count'=>20];
    //     $re = $this->post($url,json_encode($data));
    //     echo '<pre>';
      	 //print_r(json_decode($re,1));
      	 // echo 11;
    	return view('wechat/sucai');
    }
    public function do_upload(Request $request)
    {
    	
    	$upload_type=$request['up_type'];
    	//dd($upload_type);
    	$res='';
    	if($request->hasFile('image')){
            //图片类型
            $res= $this->upload_source($upload_type,'image');
        }elseif($request->hasFile('voice')){
            //音频类型
            //保存文件
            $res= $this->upload_source($upload_type,'voice');
        }elseif($request->hasFile('video')){
            //视频
            //保存文件
            $res= $this->upload_source($upload_type,'video','视频标题','视频描述');
        }elseif($request->hasFile('thumb')){
            //缩略图
            $path = $request->file('thumb')->store('wechat/thumb');
        }
        //echo $re;
        dd($res);
    }
    
}
