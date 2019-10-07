<?php

namespace App\Http\Controllers\admins;

use App\Http\Model\Login;
use App\Http\Model\Types;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Category;
use App\Http\Model\Attribute;
use App\Http\Model\Good;
use App\Http\Model\Goods_attr;
use App\Http\Model\Sku;
use DB;
class GoodsController extends Controller
{
    /*
     * 商品属性的渲染
     *
    */
    public function  attr_add(Request $request)
    {
        $t_id=$request->input('t_id');
//        dd($t_id);
        $data=Attribute::where(['t_id'=>$t_id])->get()->toArray();
//        var_dump($res);
        return json_encode($data);
    }
    public function goods_add_do(Request $request)
    {
        $postdata=$request->input();
        $file=$request->hasFile('file');
        if(empty($postdata['goods_name']) ||empty($postdata['cate_id'])){
            echo "商品名称和商品分类必填";die;
        }
        $path='';
        if(!empty($file)){
            $path=$request->file->store('images/'.date('Y-n-j'));
            //var_dump($path);die;
        }
//        var_dump($postdata);die;
        $goodsdata=Good::create([
            'goods_name'=>$postdata['goods_name'],
            'goods_price'=>$postdata['goods_price'],
            'cate_id'=>$postdata['cate_id'],
            'is_up'=>$postdata['is_up'],
            'add_time'=>time(),
            'file'=>$path,
            'content'=>$postdata['content'],
        ]);
//       var_dump($goodsdata);die;
        //获取自增id
        $goods_id=$goodsdata->id;
        //var_dump($goods_id);die;
        //var_dump($postdata['attr_id_list']);die;
        foreach ($postdata['attr_id_list'] as  $key=>$value){
           $res=Goods_attr::create([
                'goods_id'=>$goods_id,
                'a_id'=>$value,
                'attr_value_list'=>$postdata['attr_value_list'][$key],
                'attr_price_list'=>$postdata['attr_price_list'][$key],
            ]);
        }
        if($res){
            return redirect('admins/goods_sku/'.$goods_id);
        }
    }
    //货品添加页面
    public function goods_sku($goods_id)
    {
        $goodsdata=Good::where(['goods_id'=>$goods_id])->first();
        //dd($goodsdata);
        $goods_attr=Goods_attr::
            join('attribute','goods_attr.a_id','=','attribute.a_id')
            ->where(['goods_id'=>$goods_id,'attribute.attr_type'=>1])
            ->get()->toArray();
       // var_dump($goods_attr);die;
        $shuxing=[];
        foreach($goods_attr as $k=>$v){
//            var_dump($k);
//            var_dump($v);
            $shuxing[$v['a_id']][]=$v;
        }
       // var_dump($shuxing);die;
        return view('admins/goods_sku',
            ['goodsdata'=>$goodsdata,'shuxing'=>$shuxing,'goods_id'=>$goods_id]
        );
    }
    public function  goods_sku_do(Request $request)
    {
        $postdata=$request->input();
        //var_dump($postdata);
        //通过每个商品对应单一的字段把商品属性分成几个
        $count=count($postdata['goods_attr'])/count($postdata['goods_num']);
        //var_dump($count);
       // 通过分成的数量把数组分割
        $re=array_chunk($postdata['goods_attr'],$count);
//        var_dump($re); die;
        //每次循环 添加一条数据入库
        foreach ($re as $k=>$v){
            //var_dump($v);die;
            //implode数组分割成字符串
            $insertdata[]=[
                'goods_id'=>$postdata['goods_id'],
                'goods_attr'=>implode(',',$v),
                'goods_num'=>$postdata['goods_num'][$k],
            ];
        }
        //var_dump($insertdata);die;
        //insert能处理字符串
        $res=Sku::insert($insertdata);
        //var_dump($res);
        if($res){
            return redirect('admins/goods_list');
        }
    }
    /*
     * 商品展示
     */
    public function  goods_list(Request $request)
    {
        //查询所有分类数据
        $catedata=Category::get()->toArray();
        $search=$request->input('search');
        //dd($search);
        $cate_id=$request->input('cate_id');
        $where=[];
        if(isset($search)){
            $where[]=[
                'goods_name','like',"%$search%",
            ];
        }
        if(isset($cate_id)){
            $where[]=[
                'good.cate_id','=',$cate_id,
            ];
        }

        //查询所有商品数据
        $data=Good::join('category','category.cate_id','=','good.cate_id')
            ->where($where)->paginate(2);
//   dd($data);
        return view('admins/goods_list',['data'=>$data,'catedata'=>$catedata,'search'=>$search,'cate_id'=>$cate_id]);
    }
    /*
     *即点即改
     */
    public function  change(Request $request)
    {
        $value = $request->input('value');
        $field = $request->input('field');
        $goods_id = $request->input('goods_id');
        $where = [
            ['goods_id', '=', $goods_id],
        ];
        $data = [$field => $value];
        $res = Good::where($where)->update($data);
        if ($res) {
            echo json_encode(['msg' => "修改成功", 'code' => 200]);
        }
    }
    public function  login()
    {
        return  view('admins/login');
    }
    public function  login_do(Request $request)
    {
        $data=$request->input();
        $where=['name'=>$data['name'],'pwd'=>$data['pwd']];
        $res=Login::where($where)->first();
        if(empty($res)){
           echo "账号密码不匹配";die;
       }

        //dd($res);
        if($res){
            $request->session()->put('name',$data['name']);
            $request->session()->put('id',$res['id']);
            return redirect('admins/index');
        }
    }


}