<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Goodss;
use App\Http\Model\Aes;
use App\Http\Model\Rsa;
use DB;
class GoodsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $name=$request->input('name');
        //$page=$request->input('page');
        //$a=$name.$page;
        //var_dump($name);die;
        $where=[];
        $where1=[];
        //$b='';
       // $name=$b;

        //isset 防止搜索值为0报错，比empty
        if(isset($name)){
            $where[]=['goods_name','like',"%$name%"];
        }
        if(isset($name)){
            $where1[]=['goods_price','like',"%$name%"];
        }

                $data=Goodss::where($where)->orwhere($where1)->paginate(2)->toArray();
                //$name=json_encode($data,JSON_UNESCAPED_UNICODE);
                $redis->set('$a','$b');
                if(isset($name)){
                    foreach ($data['data'] as $k=>$v){
                        $data['data'][$k]['goods_name']=str_replace($name,"<b style='color:blue'>".$name."</b>",$v['goods_name']);
                        $data['data'][$k]['goods_price']=str_replace($name,"<b style='color:blue'>".$name."</b>",$v['goods_price']);
                    }
                }



        //var_dump($data);die;

        return json_encode(['code'=>200,'data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo "create";die;
    }

    /**
     * Store a newly created resource in storage.
     *商品添加
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //var_dump($_FILES);die;
        //echo 11;die;
      $goods_name=$request->input('goods_name');
      $goods_price=$request->input('goods_price');
      $goods_file=$request->hasFile('goods_file');
      $path='';
      if(!empty($goods_file)){
        $path=$request->goods_file->store('images/'.date('Y-n-j'));
        //var_dump($path);die;
      }
      $res=Goodss::create([
          'goods_name'=>$goods_name,
          'goods_price'=>$goods_price,
          'goods_file'=>$path,
          'add_time'=>time(),
      ]);
      if($res){
          return json_encode(['code'=>200,'msg'=>'添加成功']);
      }
    }

    /**
     * Display the specified resource.
     *修改默认值接收
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($g_id)
    {
        if(empty($g_id)){
            return json_encode(['code'=>203,'msg'=>'id不能为空']);
        }
        $data=Goodss::where(['g_id'=>$g_id])->first();
        //dd($data);
        return json_encode(['code'=>200,'data'=>$data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo "edit";die;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $g_id)
    {
        //var_dump($g_id);
        $goods_name=$request->input('goods_name');
        $goods_price=$request->input('goods_price');
        $goods_file=$request->hasFile('goods_file');
        $path='';
        if(!empty($goods_file)){
            $path=$request->goods_file->store('images/'.date('Y-n-j'));
            //var_dump($path);die;
        }

        $res=Goodss::where(['g_id'=>$g_id])->update([
            'goods_name'=>$goods_name,
            'goods_price'=>$goods_price,
            'goods_file'=>$path,
            'add_time'=>time(),
        ]);
        //dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'修改成功']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$g_id)
    {
        $data=Goodss::where(['g_id'=>$g_id])->select('goods_file')->first()->toArray();
        //dd($data);
        $file=$data['goods_file'];
        $a=strrpos($file,'/');
        $b=substr($file,$a+1);
        $c=substr($file,0,$a);
         unlink($c.'/'.$b);
        $res=Goodss::where(['g_id'=>$g_id])->delete();
        //dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'删除失败']);
        }
    }
    //aes加密解密
    public function aes()
    {
        $obj = new Aes('1234567890123456');

        $url = "hLlDHcFgKgfDJMNR8yWFVq3ZFRmKh1TXFbV0G/qFE+rtT98uUvbIYMxK+Du4/OJ8A1Eal28skNg6ie10w80Xc8IbBJRLtmavduUC5IkceDf/haoh56jPxgIBTbgBa0a9YRDb5FrDWyQdA8VWMqEJpSZ0Ck99CR5Uxu/zKUU5U+amdhjLrn8pVJ5nSYx4KF3s";
        echo $eStr = $obj->encrypt($url);//加密
        //echo "<hr>";
      echo $obj->decrypt($url);//解密
    }
   // rsa加密解密
    public function rsa()
    {
        //举个粒子
        $Rsa = new Rsa();
//        $keys = $Rsa->new_rsa_key(); //生成完key之后应该记录下key值，这里省略
//        $this->p($keys);die;
        $privkey = file_get_contents('private.txt');//$keys['privkey'];
        $pubkey  = file_get_contents('public.txt');//$keys['pubkey'];
        //echo $privkey;die;
        //初始化rsaobject
        $Rsa->init($privkey, $pubkey,TRUE);
        //原文
        $data = '马云赶紧还花呗了';

    //私钥加密示例
        $encode = $Rsa->priv_encode($data);
        $this->p($encode);
        $ret = $Rsa->pub_decode($encode);
        $this->p($ret);

    //公钥加密示例
//        $encode = $Rsa->pub_encode($data);
//        $this->p($encode);
//        $ret = $Rsa->priv_decode($encode);
//        $this->p($ret);
    }
    public function p($str)
    {
        echo '<br>';
        print_r($str);
        echo '<br>';
    }
    //加密调用
    public function zuoye()
    {
        $obj = new Aes('1314520612345258');
        $url='http://sun.vizhiguo.com/api/adduser?user=';
        $data='name=jj&age=12&mobile=222';
        $info=$obj->encrypt($data);//加密
        //dd($info);
        $res=file_get_contents($url.$info);
        dd($res);
    }
    //写入库的加密接口
    public function  jiekouadd(Request $request)
    {
//        $data=$request->all();
//        dd($data);
        $obj = new Aes('fdjfdsfjakfjadii');
        $str='name=李四&age=18&mobile=189';
        $arr=explode('&',$str);
        //dd($arr);
        $arrs=[];
        foreach ($arr as $k=>$v){
            $re=explode('=',$v);
            $arrs[$re[0]]=$re[1];
        }
        //dd($arrs);
    }
    //加密调用
    public function jiekou_do()
    {
        $obj = new Aes('1314520612345258');
        $url='http://www.myshop.com/api/goods/jiekou_add?user=';
        $data='name=李四&age=18&mobile=189';
        $info=$obj->encrypt($data);
        //dd($info);
        $res=file_get_contents($url.$info);
        dd($res);
    }
    public function jiekou_add(Request $request){
        $data=$request->all();
        //dd($data);
        if(empty($data['user'])){
            echo json_encode(['code'=>205,'msg'=>'缺少参数'],JSON_UNESCAPED_UNICODE);die;
        }
        $aes=new aes('1314520612345258');
        //解密
        $info=$aes->decrypt($data['user']);
        $data=explode('&',$info);
        $name=substr($data[0],5);
        $age=substr($data[1],4);
        $mobile=substr($data[2],7);
        $data['name']=$name;
        $data['age']=$age;
        $data['mobile']=$mobile;
        unset($data[0]);
        unset($data[1]);
        unset($data[2]);
        if(empty($data['name'])||empty($data['age'])||empty($data['mobile'])){
            echo json_encode(['code'=>205,'msg'=>'参数错误'],JSON_UNESCAPED_UNICODE);die;
        }
        $res=DB::connection('mysql_shops')->table('users')->insert($data);
        if($res){
            echo json_encode(['code'=>200,'msg'=>'请求成功'],JSON_UNESCAPED_UNICODE);die;
        }else{
            echo json_encode(['code'=>203,'msg'=>'请求失败'],JSON_UNESCAPED_UNICODE);die;
        }

    }
}
