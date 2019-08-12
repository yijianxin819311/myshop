<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
class Cai extends Controller
{
    public function  index(){
        $info = DB::table('qiu')->get();

        
        return view('cai/index',['info'=>$info]);
    }
    public function add(){
        return view('cai/add');
    }
    public function add_do(Request $request){
        $data = $request->all();
     
        $data = DB::table('qiu')->insert([
               'q_name'=>$data['q_name'],
               'q_name1'=>$data['q_name1'],
               'odd_time'=>strtotime($data['odd_time']),
            ]);
        // dd($data);
        if($data){
            return redirect('cai/index');
        }
    }

    public function list(Request $request){
        // $data="";
        $q_id =$request->id;
        // dd($q_id);
        $info =DB::table('qiu')->where('q_id',$q_id)->first();
        
        $data = DB::table('cai')
            ->Join('qiu', 'cai.q_id', '=', 'qiu.q_id')->where('cai.q_id','=',$q_id)
            ->first();

        if(!$data){
            $data['w_cai']=0;
            $data=json_encode($data);
            $data=json_decode($data); 
        }
  
        return view('cai/list',['info'=>$info,'data'=>$data]);
    }

    public function list_do(Request $request){
        $q_id =$request->id;
        $info =DB::table('qiu')->where('q_id',$q_id)->first();

        // dd($info);
        return view('cai/list_do',['info'=>$info]);
    }

    public function do_cai(Request $request){
        // dd(123);
        $info = $request->all();

        $data = DB::table('cai')->insert([
               'q_id'=>$info['q_id'],
               'w_cai'=>$info['w_cai'],
            ]);
        if($data){
            return redirect('cai/index'); 
        }
    }
    public function jieguo(Request $request){
        $q_id =$request->id;
        // dd($q_id);
        $info =DB::table('qiu')->where('q_id',$q_id)->first();

        // dd($info);
        return view('cai/jieguo',['info'=>$info]);
    }
   public function jieguo_do(Request $request){
        // dd(123);
        $info = $request->all();
        unset($info['_token'],$info['w_cai']);
        //dd($info);
        $data = DB::table('qiu')->where(['q_id'=>$info['q_id']])->update(['q_cai'=>2]);
        if($data){
            return redirect('cai/index'); 
        }
    }
}
