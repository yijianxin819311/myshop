<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class IndexController extends Controller
{
    public function index(Request $request)
    {
    	echo 22;
    }
    //登录页面
    public function login(){

        return view('admin/login');
    }
    public function login_do(Request $request){
        $data=$request->all();
//        dd($data);
        $where=[
            ['name','=',$data['name']],
            ['password','=',$data['password']],
        ];
        $res=DB::table('user')->where($where)->first();
//        dd($res);die;
        if($res){
            return redirect('admin/goods_list');
        }else{
            return redirect('admin/login');
        }

    }
}
