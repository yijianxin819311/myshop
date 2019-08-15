<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
class Caidan extends Controller
{
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

    public function add(Request $request)
    {
    	$re=DB::table('caidan')->groupBy('menu_name')->select(['menu_name'])->orderBy('menu_name')->get()->toArray();
    	//dd($re);
    	$info=[];
    	foreach($re as $v){
    		$res=DB::table('caidan')->where(['menu_name'=>$v->menu_name])->orderBy('menu_name')->get()->toArray();
    		//dd($res);
    		if(!empty($res[0]->menu_names)){
    			$info[]=[
    				'menu_str'=>'|',
    				'menu_name'=>$v->menu_name,
    				'menu_type'=>2,
    				'menu_names'=>'',
    				'menu_num'=>0,
    				'event_type'=>'',
    				'menu_tag'=>'',
    				
    			];
    			foreach($res as $vo){
    				 $vo->menu_str = '|-';
                    $info[] = (array)$vo;
    			}
    		}else{
    			 $res[0]->menu_str = '|';
                $info[] = (array)$res[0];
    		}
    		// dd($info);
    		$url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->get_access_token();
        	$result= file_get_contents($url);
    	}
    	return view('caidan/add',['list'=>$info]);
    	
    }
     public function add_do(Request $request)
    {
    	$req=$request->all();
    	$sub_button=[];
    	$first_caidan_count=DB::table('caidan')->where(['menu_type'=>1])->count();
    	$two_caidan_count=DB::table('caidan')->where(['menu_type'=>2])->count();
    	//dd($two_caidan_count);
    	if($req['menu_type']==1){
    		if($first_caidan_count<=2){
    		 // dd(11);
		    		$result=DB::table('caidan')->insert([
		    		'menu_name'=>$req['menu_name'],
		    		'menu_names'=>empty($req['menu_names'])?'':$req['menu_names'],
		    		'menu_type'=>$req['menu_type'],
		    		'event_type'=>$req['event_type'],
		    		'menu_tag'=>$req['menu_tag'],
	    		]);
		    	$this->caidan();
    		}else{
    			return redirect('caidan/add');
    		}
    	}if($req['menu_type']==2){
    			if($two_caidan_count<=4){
    			$result=DB::table('caidan')->insert([
	    		'menu_name'=>$req['menu_name'],
	    		'menu_names'=>empty($req['menu_names'])?'':$req['menu_names'],
	    		'menu_type'=>$req['menu_type'],
	    		'event_type'=>$req['event_type'],
	    		'menu_tag'=>$req['menu_tag'],
    		]);
	    		$this->caidan();
    		}else{
    			return redirect('caidan/add');
    		}
    			
	    		
    	}
    	
    	
    	
  }
  	public function caidan()
  	{
  		$re=DB::table('caidan')->groupBy('menu_name')->select('menu_name')->orderBy('menu_name')->get()->toArray();
  		//dd($re);
  		foreach($re as $v){
  			$res=DB::table('caidan')->where(['menu_name'=>$v->menu_name])->get()->toArray();
  			//dd($res);
  			$button=[];
  			foreach($res as $vo){
  				if($vo->menu_type==1){//一级菜单
  					if($vo->event_type=='view'){
  						$data['button'][]=[
  							'type'=>$vo->event_type,
  							'name'=>$vo->menu_name,
  							'url'=>$vo->menu->tag
  						];
  					}else{
  						$data['button'][]=[
  							'type'=>$vo->event_type,
  							'name'=>$vo->menu_name,
  							'url'=>$vo->menu_tag
  						];
  					}
  				}
  				if($vo->menu_type==2){//二级菜单
  					if($vo->event_type=='view'){
  						$sub_button[]=[
  							'type'=>$vo->event_type,
                            'name'=>$vo->menu_names,
                            'url'=>$vo->menu_tag
  						];
  					}elseif($vo->event_type == 'media_id'){

  					}

  				}else{
  					$sub_button[] = [
                            'type'=>$vo->event_type,
                            'name'=>$vo->menu_names,
                            'key'=>$vo->menu_tag
                        ];
  				}
  			}
  		}
  		 if(!empty($sub_button)){
                $data['button'][] = ['name'=>$v->menu_name,'sub_button'=>$sub_button];
            }
         $url ='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->get_access_token();
           //dd($url);
          $datas = [
            'button' => [
                [
                    'type'=>'click',
                    'name'=>'今日歌曲',
                    'key'=>'V1001_TODAY_MUSIC'
                ],
                [
                    'name'=>'菜单',
                    'sub_button' =>[
                        [
                            'type'=>'view',
                            'name'=>'搜索',
                            'url'=>'http://www.soso.com/'
                        ],
                        [
                            "type"=>"click",
                            "name"=>"赞一下我们",
                            "key"=>"V1001_GOOD"
                        ]
                    ]
                ],
                [
                    'type'=>'click',
                    'name'=>'明日歌曲',
                    'key'=>'V1001_TODAY_MUSIC111'
                ]
            ],
        ];
        //dd($datas);
        $result=$this->post($url,json_encode($datas,JSON_UNESCAPED_UNICODE));

        dd($result);
  	}
  	public function caidan_list(Request $request)
  	{
  		$re=DB::table('caidan')->get();
  		dd($re);
  	}
}
