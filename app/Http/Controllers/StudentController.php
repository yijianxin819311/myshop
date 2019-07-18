<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class StudentController extends Controller
{
    public  function index(Request $request)
    {

    	// DB::connection('mysql_shop')->enableQueryLog();
    	// $info=DB::connection('mysql_shop')->table('shop_goods')->where('goods_name','like','%22%')->get()->toArray();
    	// $sql=DB::connection('mysql_shop')->getQueryLog();
    	// var_dump($sql);
    	// $info=DB::connection('mysql_shop')->table('shop_goods')->get()->toArray();
    	// dd();
    	$redis=new \Redis;
    	$redis->connect('127.0.0.1','6379');
    	$redis->incr('num');
    	$num=$redis->get('num');
    	echo "访问次数".$num;
    	$res=$request->all();
    	//var_dump($res);$search='';
    	
    	if(!empty($res['search'])){
    		$search=$res['search'];
    		$info=DB::table('student')
    		->where('name','like','%'.$res['search'].'%')
    		->paginate(2);
    	}else{
    		$info=DB::table('student')->paginate(2);
    	}
    	
    	
    	return view('studentList',['student'=>$info,'search'=>$search]);
	}
	//登录视图
	public  function login(Request $request)
	{
		return view('login');
	}
	//登录
	public  function do_login(Request $request)
	{
		$res=$request->all();
		$request->session()->put('username','name123');
		return redirect('student/index');
	}
	public  function register(Request $request)
	{
		return view('register');
	}
	 public  function add()
	 {
	 	return view('studentAdd',[]);
	 }
	 public  function do_add(Request $request)
	 {
	 	
		 $validatedData = $request->validate
		 ([
	        'name' => 'required',
	        'age' => 'required',
	        'sex' => 'required',
	        'class_id' =>'required',
	       
	        
	    ],
		    ['name.required'=>'字段必填',
		    'age.required'=>'年龄必填',
		    'sex.required'=>'性别必填',
		    'class_id.required'=>'班级必填',
		   
	    ]);
		$res=$request->all();
	 	//dd($res);
	 	$result=DB::table('student')->insert([
	 			'name'=>$res['name'],
	 			'age'=>$res['age'],
	 			'sex'=>$res['sex'],
	 			'class_id'=>$res['class_id'],
	 			'addtime'=>time(),
	 		]);
	 	//dd($result);
	 	if($result){
	 		return redirect('student/index');
	 	}else{
	 		echo "fail";
	 	}
	 }
	  public  function delete(Request $request)
	  {
	  	$res=$request->all();
	  	$result=DB::table('student')->where(['id'=>$res['id']])->delete();
	  	//dd($result);
	  	
	  	if($result){
	   	return redirect('student/index');
	   }else{
	   	echo "fail";
	   }
	  
	  }

	  public  function update(Request $request)
	  {
	  	$res=$request->all();
	  	$info=DB::table('student')->where(['id'=>$res['id']])->first();
	  	return view('studentUpdate',['student_info'=>$info]);
	  }
	  public  function do_update(Request $request)
	  {
	   	$res=$request->all();
	   	//dd($res);
	   	$result=DB::table('student')->where(['id'=>$res['id']])->update([
	   			'name'=>$res['name'],
	   			'age'=>$res['age'],
	   			'sex'=>$res['sex'],
	   			'class_id'=>$res['class_id'],
	   			'addtime'=>$res['addtime'],
	   		]);
	   //dd($result);
		   if($result){
		   	return redirect('student/index');
		   }else{
		   	echo "fail";
		   }
	  }


}
