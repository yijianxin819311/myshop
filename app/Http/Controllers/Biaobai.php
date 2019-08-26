<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use EasyWeChat\Factory;
use App\Http\Tools\Wechat;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use EasyWeChat\Kernel\Messages\Text;
class Biaobai extends Controller
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
    public function add()
    {
    	return view('biaobai/add');
    }
    public function list(Request $request)
    {
        $res=DB::table('wechat_openid')->get();
        //dd($res);
        // $openid = 'oia6dsw4xO1VWGXpqAhGhzHKY05k';
        // //$openid=DB::table('wechat_openid')->select('openid')->get();
        // // dd($openid);
        //  $user = $this->wechat->app->user->get($openid);
        //  dd($user);
        // $app = app('wechat.official_account');
        // $aa=$app->user->list($nextOpenId = null);
        // dd($aa);
    // $url="https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->get_access_token()."&next_openid=";
    // //dd($url);
    //         $data=file_get_contents($url);
    //         $datas=json_decode($data,1);
    //         //dd($datas);
    //         $openid=$datas['data']['openid'];
    //         //dd($openid);
    //         foreach($openid as $k=>$v){
    //             //dd($v);
    //             $list=DB::table('wechat_openid')->first();
    //            if(empty($list)){
    //                $http="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->get_access_token()}&openid={$v}&lang=zh_CN";
    //                 $user=json_decode(file_get_contents($http),1);
    //                 //dd($user);
    //                $res[]=DB::table('wechat_openid')->insert([
    //                    'openid'=>$user['openid'],
    //                    'add_time'=>time(),
    //                ]);
    //            }else{
    //             $http="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->get_access_token()}&openid={$v}&lang=zh_CN";
    //                 $res[]=json_decode(file_get_contents($http),1);
                   
    //            }

               
    //         }
            //dd($list);    1`
            //dd($res);
        
    	return view('biaobai/list',['list'=>$res]);
    }
    public function sends(Request $request)
    {
        $openid=$request->all();
        //dd($openid);
        return view('biaobai/sends',['openid'=>$openid['openid']]);
    }
    public function send(Request $request)
    {
       $arr=$request->all();
       $openid=DB::table('user_wechat')->value('openid');
       $user = $this->wechat->app->user->get($openid);
       //dd($openid);
       //dd($re);
       //表白信息入库
       // $list=DB::table('biaobai_list')->insert([
       //      'openid'=>$arr['openid'],
       //      'connect'=>$arr['connect'],
       //      'type'=>$arr['type'],
       //      'add_time'=>time(),
       //  ]);
       //dd($list);
       $list=DB::table('biaobais')->insert([
            'to_user'=>$arr['openid'],
            'connect'=>$arr['connect'],
            'from_user'=>$openid,
            'type'=>$arr['type'],
            'add_time'=>time(),
        ]);
       //dd($list);
       //表白推送内容
       $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$this->get_access_token()}";
            $data=[
                   "touser"=>$arr['openid'],
                   "template_id"=>"mF9Mmc19NAUtT7FtC2Sfm7fVHDCwagLbll6G-c0aUuc",
                   "url"=>"http://yijianxin.cn/biaobai/lists",
                   "data"=>[
                            "first"=> [
                                "value"=>$arr['type']==2?'有人匿名向你表白':$user['nickname'].' 向你表白',
                               "color"=>"#173177"
                           ],
                           "keyword1"=>[
                                "value"=>$arr['connect'],
                               "color"=>"#173177"
                           ],
                       "keyword2"=>[
                           "value"=>date('Y-m-d H:i:s',time()),
                           "color"=>"#173177"
                       ],
                           "remark"=>[ 
                                "value"=>"希望有情人终成眷属",
                               "color"=>"#173177"
                           ]
                   ]
            ];
        $aa=$this->post($url,json_encode($data));
        //dd($aa);

        header('location:lists');
    }

     //微信登录
    public function login()
    {
        $redirect_uri="http://www.myshop.com/biaobai/code";
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

    public function biaobai_add(Request $request)
    {
    	$re=DB::table('biaobai')->groupBy('menu_name')->select(['menu_name'])->orderBy('menu_name')->get()->toArray();
    	//dd($re);
    	// dump($re);
    	$info=[];
    	foreach($re as $v){
    		// dd($v);
    		$res=DB::table('biaobai')->where(['menu_name'=>$v->menu_name])->orderBy('menu_name')->get()->toArray();
    		//dd($res);
    		//dump($res);
    		if(!empty($res[0]->menu_names)){
    			//二级
    			$info[]=[
    				'menu_str'=>'|',
    				'menu_name'=>$v->menu_name,
    				'menu_type'=>2,
    				'menu_names'=>'',
    				'menu_num'=>0,
    				'event_type'=>'',
    				'menu_tag'=>'',
    				
    			];
    			//dd($info);
    			// dump($info);
    			foreach($res as $vo){
    				 $vo->menu_str = '|-';
                    $info[] = (array)$vo;
    			}
    			//dd($info);
    			// dump($info);
    		}else{
    			//一级
    			 $res[0]->menu_str = '|';
                $info[] = (array)$res[0];
                //dd($info);
               //dump($info);
    		}
    		// dd($info);
    		  //dump($info);
    		$url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->get_access_token();
        	$result= file_get_contents($url);
    	}
    	return view('biaobai/biaobai_add',['list'=>$info]);
    	
    }
     public function biaobai_add_do(Request $request)
    {
    	$req=$request->all();
    	//dd($req);
    	
    	$data=[];
    	$first_caidan_count=DB::table('biaobai')->where(['menu_type'=>1])->count();
    	$two_caidan_count=DB::table('biaobai')->where(['menu_type'=>2])->count();
    	//dd($two_caidan_count);
    	if($req['menu_type']==1){
    		if($first_caidan_count<3){
    		 // dd(11);
		    		$result=DB::table('biaobai')->insert([
		    		'menu_name'=>$req['menu_name'],
		    		'menu_names'=>empty($req['menu_names'])?'':$req['menu_names'],
		    		'menu_type'=>$req['menu_type'],
		    		'event_type'=>$req['event_type'],
		    		'menu_tag'=>$req['menu_tag'],
	    		]);
		    	$this->biaobai();
    		}else{
    			return redirect('biaobai/biaobai_add');
    		}
    	}if($req['menu_type']==2){
    			if($two_caidan_count<5){
    			$result=DB::table('biaobai')->insert([
	    		'menu_name'=>$req['menu_name'],
	    		'menu_names'=>empty($req['menu_names'])?'':$req['menu_names'],
	    		'menu_type'=>$req['menu_type'],
	    		'event_type'=>$req['event_type'],
	    		'menu_tag'=>$req['menu_tag'],
    		]);
	    		$this->biaobai();
    		}else{
    			return redirect('biaobai/biaobai_add');
    		}	
    	}	
    	
  }
  //刷新菜单
  	public function biaobai()
  	{
  		 $menu_info = DB::table('biaobai')->groupBy('menu_name')->select(['menu_name'])->orderBy('menu_name')->get()->toArray();
        foreach($menu_info as $v){
            $menu_list =DB::table('biaobai')->where(['menu_name'=>$v->menu_name])->get()->toArray();
            $sub_button = [];
            foreach($menu_list as $k=>$vo){
                if($vo->menu_type == 1){ //一级菜单
                    if($vo->event_type == 'view'){
                        $data['button'][] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->menu_name,
                            'url'=>$vo->menu_tag
                             //$vo->menu_tag=='click'?"key":"url" => $vo->menu_tag,
                        ];
                    }else{
                        $data['button'][] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->menu_name,
                            'key'=>$vo->menu_tag
                             //$vo->menu_tag=='click'?"key":"url" => $vo->menu_tag,
                        ];
                    }
                }
                if($vo->menu_type == 2){ //二级菜单
                    //echo "<pre>";print_r($vo);
                    if($vo->event_type == 'view'){
                        $sub_button[] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->menu_names,
                            'url'=>$vo->menu_tag
                        ];
                    }elseif($vo->event_type == 'media_id'){

                    }else{
                        $sub_button[] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->menu_names,
                            'key'=>$vo->menu_tag
                        ];
                    }
                }
            }
             //echo "<pre>";print_r($sub_button);
            if(!empty($sub_button)){
                $data['button'][] = ['name'=>$v->menu_name,'sub_button'=>$sub_button];
            }
        }
       //echo "<pre>";print_r($data);
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->get_access_token();
       
        $re = $this->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        dd($re);
  	}
  	public function lists()
  	{
        $re=DB::table('biaobai_list')->get();
        //dd($re);
        return view('biaobai/lists',['list'=>$re]);
  	}
  }