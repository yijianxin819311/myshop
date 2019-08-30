<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
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
        $redirect_uri="http://www.yijianxin.cn/kecheng/code";
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
    //微信消息推送
    public function event()
    {//$this->checkSignature();
        //dd(11);
        $data = file_get_contents("php://input");
        //dd($data);
        //解析XML
        $xml = simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);//将xml字符串 转换成对象
        $xml = (array)$xml; //转化成数组
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents(storage_path('logs/wx_event.log'),$log_str,FILE_APPEND);
        // dd($xml);
        if($xml['MsgType'] == 'event'){
            if($xml['Event'] == 'subscribe'){ //关注
               //isset检测变量是否设置
                if(isset($xml['EventKey'])){
                    //dd(11);
                    //拉新操作
                    $agent_code = explode('_',$xml['EventKey'])[1];
                    // dd($agent_code);
                    $agent_info = DB::table('user_agent')->where(['uid'=>$agent_code,'openid'=>$xml['FromUserName']])->first();
                    //dd($agent_info);
                    if(empty($agent_info)){
                        DB::table('user_agent')->insert([
                            'uid'=>$agent_code,
                            'openid'=>$xml['FromUserName'],
                            'add_time'=>time()
                        ]);
                    }
                } 
                //$message = '嗨!';//新关注用户回复
                $message = '欢迎进入选课系统!';
                //dd($message);
                $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
            }
        }elseif($xml['MsgType'] == 'text'){
            //dd($xml['Content']);
            $preg_str='/^.*?油价$/';
            //dd($preg_str);
            $preg_result=preg_match($preg_str,$xml['Content']);
            //dd($preg_result);
             if($preg_result){
                //查询油价
                $city=substr($xml['Content'],0,-6);
                //dd($city);
                $url="http://shopdemo.18022480300.com/price/api";
                $price_info=file_get_contents($url);
                $price_arr=json_decode($price_info,1);
                //dd($price_arr);
                $support_arr=[];
                foreach ($price_arr['result'] as $k => $v) {
                     $support_arr[]=$v['city'];
                }
                if(!in_array($city,$support_arr)){
                     $message = '查询城市不存在';
                      $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;die(); 
                 }
                 //dd($price_arr['result']);
                 foreach ($price_arr['result']  as $v) {
                    //dd($v);
                    if($city==$v['city']){
                        $this->redis->incr($city);
                        $find_num=$this->redis->get($city);
                        // dd($find_num);
                        //缓存操作
                        if($find_num>10){
                            //dd(11);
                            if($this->redis->exists($city.'信息')){
                                //存在
                                
                                $v_info=$this->redis->get($city.'信息');
                                $v=json_decode($v_info,1);
                                //dd($v);
                            }else{
                                $this->redis->set($city.'信息',json_encode($v));
                            }

                        }
                      
                        $message = $city.'目前油价：'."\n".'92h：'.$v['92h']."\n".'95h：'.$v['95h']."\n".'98h：'.$v['98h']."\n".'0h：'.$v['0h'];
                        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                        echo $xml_str;
                        die();
                    }
                 }
            }
            
  
            // $message = '你好,欢迎来到我的世界';
            // $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            // echo $xml_str;
        }
        //echo $_GET['echostr'];  //第一次访问
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
      	
      		$list=DB::table('kecheng')->insert([
            'openid'=>$arr['openid'],
            'first_kecheng'=>$arr['first_kecheng'],
            'two_kecheng'=>$arr['two_kecheng'],
            'three_kecheng'=>$arr['three_kecheng'],
            'four_kecheng'=>$arr['four_kecheng'],
            'add_time'=>time(),
        	]);
      	
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
    public  function update_do(Request $request)
   {
   		$arr=$request->all();
   		// dd($req);
   		//$re=DB::table('kecheng')->select('add_time')->first();
   		//dd($re);
   		$first = Carbon::create(2019, 9, 1);
   		// dd($first);
   		$times=$first->toDateTimeString();
   		//dd($times);
   		$time=Carbon::now()->toDateTimeString();
   		 //dd($time);
   		if($time>$times){
   			echo "当前日期超过2019年9月1日,不能进行修改";die();
   		}else{
   			$re=DB::table('kecheng')->where(['openid'=>$arr['openid']])->update([
   					
		            'first_kecheng'=>$arr['first_kecheng'],
		            'two_kecheng'=>$arr['two_kecheng'],
		            'three_kecheng'=>$arr['three_kecheng'],
		            'four_kecheng'=>$arr['four_kecheng'],
		            'add_time'=>time(),
   				]);
   			dd($re);
   		}
   		
   }
   public function moban()
   {
   		$openid_info = DB::connection('mysql')->table("wechat_openid")->select('openid')->limit(10)->get()->toArray();
        //dd($openid_info);
        foreach($openid_info as $v){
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->wechat->get_access_token()."";
           $data=[
                'touser'=>$v->openid,
                'template_id'=>'ImOrfmPtxkRTaDWtYfPuUIGauBRdWp52UjJHcRm9Ixc',
                'url'=>'http://www.baidu.com',
                'data'=>[
                    'first'=>[
                        'value'=>'用户',
                        'color'=>''
                    ],
                    'keyword1'=>[
                        'value'=>'第一节课',
                        'color'=>''
                    ],
                    'keyword2'=>[
                        'value'=>'第二节课',
                        'color'=>''
                    ],
                    'keyword3'=>[
                        'value'=>'第三节课',
                        'color'=>''
                    ],
                    'keyword4'=>[
                        'value'=>'第四节课',
                        'color'=>''
                    ],
                    
                ]

           ];
          $re=$this->post($url,json_encode($data));
           dd($re);
        }
   }

   public function class_caidan(){
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->get_access_token().'';
//        dd($url);
        $data=[
            "button"=>[
                 [
                     "type"=>"click",
                      "name"=>"查看课程",
                      "key"=>"kecheng",
                  ],
                [
                    "type"=>"view",
                    "name"=>"课程管理",
                    "url"=>'http://www.yijianxin.cn/kecheng/add',
                ],
        ],
    ];
//        dd($data);
        $data=$this->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        dd($data);

    }

}
