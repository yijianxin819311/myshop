<?php

namespace App\Http\Controllers\index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class IndexController extends Controller
{
    public function index()
    {
    	echo "前台首页";
    	// return view()
    }
     public function login(Request $request)
    {
    	return view('index.login');
    }
     public function login_do(Request $request)
     {
     	$res=$request->all();
     	$result=DB::table('user')->where(['name'=>$res['names'],'password'=>$res['password']])->first();
        dd($result);
        if(empty($result)){
            echo '账号密码错误';
        }else{
            $request->session->put('result',$result);
            return redirect('index.index');
        }
     }
     public function register(Request $request)
     {
     	return view('index.register');

     }
      public function register_do(Request $request)
     {
        $validatedData = $request->validate
         ([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ],
            ['name.required'=>'名字必填',
            'email.required'=>'邮箱必填',
            'password.required'=>'密码必填',
            
           
        ]);
     	$res=$request->all();
     	// dd($res);
     	$result=DB::table('user')->insert(['name'=>$res['names'],'email'=>$res['email'],'password'=>$res['password'],'reg_time'=>time()]);
     	// dd($result);
     	if($result){
     		return redirect('index/login');
     	}

     }



}
