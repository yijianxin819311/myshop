<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class GoodsController extends Controller
{
    public function goods_add(Request $request)
    {
    	return view('admin.goods_add');
    }
    public function goods_add_do(Request $request)
    {
        // echo 11;die;
        $res= $request->all();

        $res['add_time']=time();
        unset($res['_token']);
        // dd($res);
        //文件上传
        $files=$request->file('goods_pic');
        // $path="";
        if(empty($files)){
            //未上传图片
            echo "fail";die;
        }else{
            //已上传图片
            $path=$files->store('goods');
            // dd($path);
            $res['goods_pic']=asset('storage').'/'.$path;
        }
        //dd($res);
        $result=DB::table('goods')->insert($res);
        if($result){
            echo "<script>alert('添加成功,跳转到展示页面'),location.href='/admin/goods_list'</script>";
        }else{
            echo "<script>alert('添加失败,跳转到添加页面'),location.href='/adin/goods_add'</script>";
        }
    	

    }
    public function goods_list(Request $request)
    {
        $redis=new \Redis;
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num');
        $num=$redis->get('num');
        echo '访问次数'.$num;

        $res=$request->all();
        // dd($res);
        $search="";
        // dd($search);
        if(!empty($res['search'])){
            $search=$res['search'];
            // dd($search);
             $result=DB::table('goods')
             ->where('goods_name','like','%'.$res['search'].'%')
             ->paginate(2);
        }else{
            $result=DB::table('goods')->paginate(2);
        }
        
        return view('admin.goods_list',['goods'=>$result,'search'=>$search]);
    }
    public function goods_delete(Request $request)
    {

        $res=$request->all();
        // dd($res);
        $result=DB::table('goods')->where(['id'=>$res['id']])->delete();
        //dd($result);
        if($result){
             echo "<script>alert('添加成功,跳转到展示页面'),location.href='/admin/goods_list'</script>";
         }else{
            echo "<script>alert('添加失败,跳转到添加页面'),location.href='/adin/goods_add'</script>";
         }
    }
    public function goods_update(Request $request)
    {

        $res=$request->all();
        
        $result=DB::table('goods')->where(['id'=>$res['id']])->first();
        //dd($result);
        return view('admin.goods_update',['goods_info'=>$result]);

    }
     public function goods_update_do(Request $request)
     {
        $res=$request->all();
         unset($res['_token']);
         // dd($res);
        $result=DB::table('goods')->where(['id'=>$res['id']])->update(['goods_name'=>$res['goods_name'],'goods_price'=>$res['goods_price'],
            ]);
       //dd($result);
       if($result){
        return redirect('admin/goods_list');
       }else{
        echo "fail";
       }
     }

     
}
