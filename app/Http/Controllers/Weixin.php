<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Tools\Wechat;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
class Weixin extends Controller
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


    //微信登录
    public function login()
    {
        $redirect_uri="http://www.myshop.com/weixin/code";
        //dd($redirect_uri);
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect ";
        //dd($url);
        header('Location:'.$url);   
    }

    public function code(Request $request)
    {
        $res=$request->all();
        //dd($res);
        $code=$res['code'];
        //dd($code);
        //获取access_token
        $url=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."&code=".$code."&grant_type=authorization_code");
        //dd($url);
        //$re=file_get_contents($url);
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
                'name'=>$user_infos['nickname'],
                'reg_time'=>time(),
            ]);
            //dd($openid_result);
            DB::connection('mysql')->commit();
            //登陆操作
            $user_info = DB::connection('mysql')->table("users")->where(['id'=>$user_openid['uid']])->first();
            $request->session()->put('username',$user_info['name']);
            header('Location:user_list');
        }

        
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
    		//从缓存取获取用GET
    		$access_token=$redis->get($access_key);
    	}else{
    		//从微信接口获取
    		$access_re=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."");
    		//拉取关注用户列表
    		$result=json_decode($access_re,1);

    		$access_token=$result['access_token'];
    		$expire_time=$result['expires_in'];
    		//加人缓存设置缓存用set
    		$redis->set($access_key,$access_token,$expire_time);
    	}
    	return $access_token;
    }
    //粉丝列表
    public function get_user_list()
    {

        // $openid = $this->app->user->list($nextOpenId = null)['data']['openid'];  // $nextOpenId 可选
        // dd($openid);
    	$access_token=$this->get_access_token();
    	//dd($access_token);
    	$info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=");
    	$user_info=json_decode($info,1);
    	//dd($user_info);
    	return $user_info;

    }

    
    //粉丝基本信息
    public function get_user_info()
    	
    {
        $access_token=$this->get_access_token();
        $user_info=$this->get_user_list();
        //dd($user_info);
        $user=DB::table('wechat_openid')->first();
    	if($user==null){
    		foreach ($user_info['data']['openid'] as $v) {
                //dd($v);
    			$users=DB::table('wechat_openid')->insert([
    					'openid'=>$v,
    					'add_time'=>time(),
    					
    				]);
    			
    		}

    	 }
        
    	echo "<script>history.go(-1)</script>";
    }

    public function user_list(Request $request)
    {
        
    	$user_info=DB::table('wechat_openid')->get();
    	 //dd($user_info);
    	return view('weixin/user_list',['res'=>$user_info]);
    }


    public function lists(Request $request)
    {
    	
         $openid=DB::table('wechat_openid')->where(['id'=>$this->request->all()['id']])->value('openid');
        $user_info = $this->wechat->wechat_user_info($openid);
        //dd($user_info);
        //dd($openid);
        // $access_token = $this->get_access_token();
        // $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        // $user_info = json_decode($wechat_user,1);
        //  //dd($user_info);
       
        return view('weixin/lists',['res'=>$user_info]); 
    }

    public function wechat_user_info(){
        // $openid = $this->app->user->list($nextOpenId = null)['data']['openid'];  // $nextOpenId 可选
        // dd($openid);
        // $user = $this->app->user->get($openid);
        // dd($user);
        $aess_token = $this->get_access_token();
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $user_info = json_decode($wechat_user,1);
        dd($user_info);
        return $user_info;
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
        return view('weixin/template_list',['res'=>$res]);
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
    
        return view('weixin/sucai');
     }
    public function do_upload(Request $request)
    {
        
         $upload_type = $request['up_type'];
         //dd($upload_type);
         //dd($type);
        $re = '';
        if($request->hasFile('image')){
            //图片类型hasfile格式转换
            $re = $this->wechat->upload_source($upload_type,'image');
        }elseif($request->hasFile('voice')){
            //音频类型
            //保存文件
            $re = $this->wechat->upload_source($upload_type,'voice');
        }elseif($request->hasFile('video')){
            //视频
            //保存文件
            $re = $this->wechat->upload_source($upload_type,'video','视频标题','视频描述');
        }elseif($request->hasFile('thumb')){
            //缩略图
            $path = $request->file('thumb')->store('weixin/thumb');
        }
        //echo $re;
        dd($re);

    }
    
     public function get_voice_source()
    {
        $media_id = '-qlPfVds-GpMb-KbXVzFZQpPVLT9yCaRzOkQU8RW8KgqJj-_TGy5Phr4-2xP7Mma';
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        //echo $url;echo '</br>';
        //保存图片
        $client = new Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);                                        
        //保存图片
        $path = 'weixin/voice/'.$file_name;
        $re = Storage::put($path, $response->getBody());
        echo env('APP_URL').'/storage/'.$path;
        dd($re);
    }
    public function get_video_source(){
        $media_id = 'VUN4ijz8oLeG28CJsgj3fPJNCf95H5j3ViNwbFb6t4S7Ld68QtdPYgptEWmAwhFq'; //视频
        $url = 'http://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        $client = new Client();
        $response = $client->get($url);
        $video_url = json_decode($response->getBody(),1)['video_url'];
        $file_name = explode('/',parse_url($video_url)['path'])[2];
        //设置超时参数
        $opts=array(
            "http"=>array(
                "method"=>"GET",
                "timeout"=>3  //单位秒
            ),
        );
        //创建数据流上下文
        $context = stream_context_create($opts);
        //$url请求的地址，例如：
        $read = file_get_contents($video_url,false, $context);
        $re = file_put_contents('./storage/weixin/video/'.$file_name,$read);
        var_dump($re);
        die();
    }
    public function get_source()
    {
        $media_id = 'Nb_-R7OsUvpISOoIScijcMuKqrorVBSe1KzIkBClUpPqorWoWN3DtxpadKNYLmRY'; //图片
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        //echo $url;echo '</br>';
        //保存图片
        $client = new Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);
        //$wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $path = 'weixin/image/'.$file_name;
        $re = Storage::disk('local')->put($path, $response->getBody());
        echo env('APP_URL').'/storage/'.$path;
        dd($re);
        //return $file_name;
    }

    //素材列表
    public function sucai_list()
    {
        $url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->get_access_token()."";
        //dd($url);
        $data = ['type'=>'image','offset'=>0,'count'=>20];
        $res=$this->post($url,json_encode($data));
        // dd($res);
        $result=json_decode($res,1);
         //dd($result);
        return view('weixin/sucai_list',['list'=>$result['item']]);
    }
    //标签
    public function add_tag()
    {
        return view('weixin/add_tag');
    }
    //添加标签
    public function do_add_tag(Request $request)
    {
        $res=$request->all();
        $url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token=".$this->get_access_token()."";
        // dd($url);
        $data=[
        'tag'=>['name'=>$res['name']],
        ];
        //dd($data);
        $result = $this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
         //dd($result);
        $ress=json_decode($result,1);
        //dd($ress);
        if($ress){
            return redirect('weixin/taglist');
        }
    }
    //标签列表
    public function taglist(Request $request)
    {
        
        $url="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->get_access_token()."";
        //dd($url);
        $re=file_get_contents($url);
        // dd($re);
        $res=json_decode($re,1);
        //dd($res);
        return view('weixin/taglist',['list'=>$res['tags']]);
    }
    //标签下的粉丝列表
    public function tag_user(Request $request)
    {
         $id=$request->all();
        //dd($id);
        $url="https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=".$this->get_access_token()."";
       //dd($url);
       // $re=file_get_contents($url);
       // dd($re);
       $data=[
            'tagid'=>$id['tag_id'],
            'next_openid'=>'',
       ];
       //dd($data);
       $res=$this->post($url,json_encode($data));
       //dd($res);
       $result=json_decode($res,1)['data'];
      // dd($result);
      // dd($result['data']['openid']);
      return view('weixin/fensilist',['list'=>$result]);
    }
    //删除标签
     public function del_tag(Request $request)
     {
       $id=$request->all();
        //dd($id);
        $url="https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=".$this->get_access_token()."";
        //dd($url);
        $data=[
            'tag' => ['id' =>$id['id']]
        ];
        //dd($data);
        $res=$this->post($url,json_encode($data));
        //dd($res);
        $result=json_decode($res,1);
        if($result){
           return redirect('weixin/taglist');
        }
       
     }
     /**
     * 获取用户标签
     */
    public function get_user_tag(Request $request)
    {
       
        $id=$request->all();
        //dd($id);
        $url="https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=".$this->get_access_token()."";
        // dd($url);
        $data=[
            'openid'=>$id['id'],
        ];
        // dd($data);

        $res=$this->post($url,json_encode($data));
        //dd($res);
        $result=json_decode($res,1);
        //dd($result);被添加的标签
        $url1="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->get_access_token()."";
        //dd($url);
        $re1=file_get_contents($url1);
        // dd($re);
        $res1=json_decode($re1,1);
        //dd($res1);
        $arr=$res1['tags'];
        //dd($arr);
        foreach($arr as $v){
            //dd($v);
            foreach($result['tagid_list'] as $vo){
               // dd($vo);
                if($vo == $v['id']){
                    echo $v['name']."<a href='".env('APP_URL').'/weixin/del_user_tag'.'?tag_id='.$v['id'].'&openid='.$request->all()['id']."'>删除</a><br/>";
                }
            }
        }

    }
    //批量给用户取消标签
     public function del_user_tag(Request $request)
     {
        $re=$request->all();
        //dd($re);
        $url="https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=".$this->get_access_token()."";
        // dd($url);
        $data=[
            'openid_list'=>$re['openid'],
            'tagid'=>$re['tag_id'],
        ];
        // dd($data);
        $res=$this->post($url,json_encode($data));
         //dd($res);
        $result=json_decode($res,1);
        //dd($result);
        if($result){
            return redirect('weixin/taglist');
        }
        
     }
    //批量给用户打标签.用户列表展示页面
     public function add_user_tag(Request $request)
    {
        $tag_id= $request->all();
        //dd($tag_id);
        $user_info=$this->get_user_list()['data']['openid'];
        //dd($user_info);
        return view('weixin/tag_users',['tag_id'=>$tag_id['tag_id'],'openid'=>$user_info]);
    }
    public function tag_do(Request $request)
    {
        $re=$request->all();
        //dd($re);
         $url="https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=".$this->get_access_token()."";
         //dd($url);
         $data=[
            'tagid'=>$re['tag_id'],
            'openid_list'=>$re['openid'],
         ];
         //dd($data);
         $res=$this->post($url,json_encode($data));
         //dd($res);
         $result=json_decode($res,1);
         //dd($result);
         if($result){
            return redirect('weixin/taglist');
         }
    }
    //推送消息
    public function tuisong(Request $request)
    {
        $tagid=$request->all();
        //dd($tagid);
        $url ='https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$this->get_access_token();
        $data = [
            'tagid' => $tagid['tag_id'],
            'next_openid' => ''
        ];
        //dd($data);
        $re = $this->post($url,json_encode($data));
        //dd($re);
       // $res=json_decode($re,1)['data']['openid'];
       //dd(json_decode($re,1)['data']['openid']);
        return view('weixin/tuisong',['openid'=>json_encode(json_decode($re,1)['data']['openid']),'tag_id'=>$request->all()['tag_id']]);    
    }
    //推送信息执行页面
    public function tuisong_do(Request $request)
    {

        $url ='https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->get_access_token();
        $push_type = $request->all()['push_type'];
        // dd($push_type);
        if($push_type == 1){
            //文本消息
            $data = [
                'filter' => ['is_to_all'=>false,'tag_id'=>$request->all()['tag_id']],
                'text' => ['content' => $request->all()['message']],
                'msgtype' => 'text'
            ];
        }elseif($push_type == 2){
            //素材消息 图
            $data = [
                'filter' => ['is_to_all'=>false,'tag_id'=>$request->all()['tag_id']],
                'image' => ['media_id' => $request->all()['media_id']],
                'msgtype' => 'image'
            ];
        }
        $re = $this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        dd(json_decode($re,1));
    }



//微信推送消息
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
          }elseif($xml['Event'] == 'CLICK'){
                //echo 11;
                if($xml['EventKey'] == 'kecheng'){
                    //echo 22;die;
                    $data=DB::table('kecheng')->first();
                    //dd($data);
                    if(empty($data)){
                        $message = '还没选择课程，请先选择课程';
                        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                        echo $xml_str;
                    }else{
                        $user = DB::table('user_wechat')->where('openid',$xml['FromUserName'])->first();

                        $message='欢迎'.$user->name."\n".'第一节'.$data->first_kecheng."\n".'第2节'.$data->two_kecheng."\n".'第3节'.$data->three_kecheng."\n".'第4节'.$data->four_kecheng;
                        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                        echo $xml_str;
                    }

                }

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
    public function do_get()
    {
        $url="http://www.yijianxin.cn/jiekou/jiekou";
        $data=$this->wechat->get($url);
        // $data=file_get_contents($url);
        //echo 11;die;
        dd($data);
    }

}
