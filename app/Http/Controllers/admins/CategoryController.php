<?php

namespace App\Http\Controllers\admins;

use App\Http\Model\Types;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Category;
use App\Http\Model\Attribute;
use DB;
class CategoryController extends Controller
{
    //分类添加视图
    public function cate()
    {
        $data=Category::get()->toArray();
        $data=$this->info($data);
        return view('admins/category',['data'=>$data]);
    }
    public function add_do(Request $request)
    {
        $data=$request->all();
       // dd($data);
        $cate_name=$request->input('cate_name');
        //dd($cate_name);
        //var_dump($cate_name);
        if(empty($cate_name)){
            echo json_encode(['code'=>203,'msg'=>'分类必填'],JSON_UNESCAPED_UNICODE);die;
        }
        $res=Category::create($data);
        //dd($res);
        if($res){
            echo json_encode(['code'=>200,'msg'=>'添加成功'],JSON_UNESCAPED_UNICODE);
        }
    }
    //验证分类唯一性
    public function add_dodo(Request $request)
    {
        $cate_name=$request->input('cate_name');
        if(empty($cate_name)){
            echo json_encode(['code'=>203,'msg'=>'分类必填'],JSON_UNESCAPED_UNICODE);die;
        }
        //dd($cate_name);
        //var_dump($cate_name);
        $re=Category::where(['cate_name'=>$cate_name])->first();
        if($re){
            echo json_encode(['code'=>202,'msg'=>'该分类已存在'],JSON_UNESCAPED_UNICODE);die;
        }

    }
    //分类展示
    public function lists()
    {
       $data=Category::get()->toArray();
    //        dd($data);
        $data=$this->info($data);

        //dd($data);exit;
        return view('admins/lists',['data'=>$data]);
    }
    //类型添加
    public function goods_type()
    {
        return view('admins/goods_type');
    }
    public function type_do(Request $request)
    {
        $type=$request->input('type');
        $res=DB::table('type')->insert(['type'=>$type]);
        if($res){
            echo json_encode(['code'=>200,'msg'=>'添加成功'],JSON_UNESCAPED_UNICODE);
        }
    }
    //类型展示
    public function type_list(Request $request)
    {
        $data=Types::get()->toArray();
        //dd($data);
       foreach ($data as $k=>$v){
           //dd($v);
           $count=Attribute::where(['t_id'=>$v['t_id']])->count();
           $data[$k]['count']=$count;
       }
       //dd($data);
        return view('admins/type_list',['data'=>$data]);
    }
    //属性添加
    public function goods_attr(Request $request)
    {
        $data=DB::table('type')->get();
        return view('admins/goods_attr',['data'=>$data]);
    }
    //属性添加执行
    public function attr_do(Request $request)
    {
        $attr_name=$request->input('attr_name');
        //dd($name);
        $t_id=$request->input('t_id');
        $attr_type=$request->input('attr_type');
        $res=Attribute::create([
            'attr_name'=>$attr_name,
            't_id'=>$t_id,
            'attr_type'=>$attr_type]
        );
        if($res){
            echo json_encode(['code'=>200,'msg'=>'添加成功'],JSON_UNESCAPED_UNICODE);
        }
    }
    //属性展示
    public function attr_list(Request $request)
    {
        $t_id=$request->input('t_id');
        //var_dump($t_id);die;
        $re=Types::get();
//        var_dump($re);die;
        if(!empty($t_id)){
            $data=Attribute::
            join('type', 'type.t_id','=','attribute.t_id')
                ->select( 'type','attribute.a_id','attr_name','attr_type')
                -> where('Type.t_id',$t_id)
                ->get();
        }else{
            $data=Attribute::
            join('type', 'type.t_id','=','attribute.t_id')
                ->select( 'type','attribute.a_id','attr_name','attr_type')
                ->get();
        }


        //dd($data);
        return view('admins/attr_list',['data'=>$data,'re'=>$re]);
    }
    //内容改变事件
    public function attr_lists(Request $request)
    {
        $t_id=$request->input('t_id');
        //var_dump($t_id);

        $data=Attribute::
        join('type', 'type.t_id','=','attribute.t_id')
            ->select( 'type','attribute.a_id','attr_name','attr_type')
            -> where('Type.t_id',$t_id)
            ->get();
        return json_encode($data);
    }
    //批删方法
    public function del(Request $request)
    {
        $a_id = $request->input('a_id');
        //dd($a_id);
        $res= Attribute::where('a_id',$a_id)->delete();
        //dd($res);
        if($res){
            echo json_encode(['msg'=>"成功删除",'code'=>1]);
        }else{
            echo json_encode(['msg'=>"删除失败",'code'=>2]);
        }
    }
    public function goods(Request $request)
    {
//        $value=$request->session()->get('id');
//        dd($value);
        //查询分类数据
        $cate=Category::get();
        //查询类型数据
        $type=Types::get();
        return view('admins/goods',['cate'=>$cate,'type'=>$type]);
    }
    //无限极分类
    public function info($data,$pid=0,$level=1){
//        dd($data);
        static $arr=[];
        foreach ($data as $v){
//            var_dump($v);
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->info($data,$v['cate_id'],$level+1);
            }
        }
        return $arr;
    }
}
