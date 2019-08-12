<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Liuyan extends Controller
{
	public function info(Request $request){
		//$res=$request->all();
		if(empty($request->all()['access_token']) || $request->all()['access_token']){
			return json_encode(['errno'=>'40014']);
		}
		$result=DB::table('liuyand')->get()->toArray();

		$result=json_decode(json_encode($result,1));
		echo json_encode($result);
	}



    public function liuyan_login(Request $request){
    	return view('liuyan/liuyan_login');
    }
    public function do_login(Request $request){
    	$res=$request->all();
    	//dd(md5($res['pwd']));
    	
    	$result=DB::table('liuyans')->where(['name'=>$res['name'],'pwd'=>md5($res['pwd'])])->first();

    	//dd($result);
    	if($result){
    		 $request->session()->put('name',$res['name']);
    		 
    		return redirect('liuyan/liuyan');
    	}
    }
    public function liuyan(Request $request){
    	 $res=$request->all();
    	$value=$request->session()->get('name');
    	//dd($value);
    	if(empty($value)){
    		 echo "<script>alert('请先登录'),location.href='liuyan_login'</script>";
    	}
    	return view('liuyan/liuyan');
    }
    public function do_liuyan(Request $request){
    	$res=$request->all();

    	$result=DB::table('liuyand')->insert(['name'=>session('name'),'neirong'=>$res['neirong'],'add_time'=>time()]);
    	if($result){
    		return redirect('liuyan/list');
    	}
    }
     public function list(Request $request)
     {
     	$redis=new \Redis;
    	$redis->connect('127.0.0.1','6379');
    	$redis->incr('num');
    	$key = 'liuyyan:list';
    	// $num=$redis->get('num');
    	// echo "浏览次数".$num;
     	$res=$request->all();
     	$search="";
     		// echo 111;die;
     		if(!empty($res['search'])){
     			// echo 111;die;
	     		$search=$res['search'];
	    		$result=DB::table('liuyand')
	    		->where('name','like','%'.$res['search'].'%')
	    		->get();
	    		// dd($result);
	    	
	    		// if($redis->exists($key)){
     		// 	$redis_info=$redis->get($key);
     		// 	$result=json_decode($redis_info,1);
     			
     		// 	dd($result);
     	//}
	     		
	     	}else{
	     		// echo 222;
	     		$result=DB::table('liuyand')->get();
	     		// $result = json_decode(json_encode($result),1);
	     		// $redis->set($key,json_encode($result),30);
	     		// dd($result);
	     	}
	     

	     	return view('liuyan/list',['liuyan'=>$result,'search'=>$search]);
      }

      public function delete(Request $request){
      	$res=$request->all();
      	$value=$request->session()->get('name');
      	$req=DB::table('liuyand')->where(['id'=>$res['id']])->select('name','add_time')->first();//查一条数据
      	//dd($req);
      	//dd($value);
      	if($value!=$req->name){
      		echo "无权限";die;
      	}
      $times=time()-$req->add_time;//时间间隔,这里出来的是秒数

      if($times<30*60){
      	$result=DB::table('liuyand')->where(['id'=>$res['id']])->delete();
      }else{
      	echo "超过半小时不能删除";die;
      }
      
      	if($result){
      		return redirect('liuyan/list');
      	}
      }
}
