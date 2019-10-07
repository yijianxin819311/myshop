<?php

namespace App\Http\Controllers\api;
use App\Http\Model\Category;
use App\Http\Model\Goods_attr;
use App\Http\Model\Order_address;
use DemeterChain\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Http\Model\Good;
use App\Http\Model\Cart;
use App\Http\Model\User;
use App\Http\Model\Attribute;
use DB;
class IndexController extends Controller
{

    /**
     *分类展示接口
     *
     */
    public function cate()
    {
        $pid=0;
        $cache_name="cate";
        $catedata=Cache::get($cache_name);
        if(empty($catedata)){
            $catedata=Category::where(['pid'=>$pid])->orderBy('cate_id','DESC')->get();
            Cache::put($cache_name,$catedata,600);
        }
        $catedata=json_encode($catedata);
        $callback=$_REQUEST['jsoncallback'];
        if($callback){
            echo $callback."(".$catedata.")";die;
        }else{
            echo "(".$catedata.")";
        }

    }
    /**
     *最新商品展示接口
     *
     */
    public function newgoods()
    {

        //缓存
        $cache_name="goodsnews";
        $goodsdata=Cache::get($cache_name);
        if(empty($goodsdata)){
            $goodsdata=Good::orderBy('goods_id','DESC')->limit(4)->get();
            foreach ($goodsdata as $k=>$v){
                $goodsdata[$k]['file']="http://www.myshop.com/".$v['file'];
            }
            Cache::put($cache_name,$goodsdata,600);
        }
        $goodsdata=json_encode($goodsdata);
        $callback=$_REQUEST['jsoncallback'];
        if($callback){
            echo $callback."(".$goodsdata.")";die;
        }else{
            echo "(".$goodsdata.")";
        }

    }
    /**
     *分类商品展示接口
     *
     */
    public function allgoods(Request $request)
    {
          $cate_id=$request->input('cate_id');
          $allcate=Category::where(['pid'=>$cate_id])->get()->toArray();
         //dd($allcate);
            $newid=[];
            foreach($allcate as $k=>$v){
                $newid[]=$v['cate_id'];
            }
            //dd($newid);
            if(!$newid){
                //echo 11;die;
                $goodsdata=Good::where(['cate_id'=>$cate_id])->orderBy('goods_id','DESC')->get();
            }else{
                //echo 22;die;
                foreach ($newid as $k=>$v){
                    $goodsdata=Good::where(['cate_id'=>$v])->orderBy('goods_id','DESC')->get();
                }
            }
            foreach ($goodsdata as $k=>$v){
                $goodsdata[$k]['file']="http://www.myshop.com/".$v['file'];
            }
        $goodsdata=json_encode($goodsdata);
        $callback=$_REQUEST['jsoncallback'];
        if($callback){
            echo $callback."(".$goodsdata.")";
        }else{
            echo "(".$goodsdata.")";
        }
    }
    /**
     *分类商品1展示接口
     *
     */
    public function cates(Request $request)
    {
        $catedata=Category::get()->toArray();
        $catedata=json_encode($catedata);
        return $catedata;
    }
    /**
     *商品详情接口
     *
     */
    public function goodsdetail(Request $request)
    {
        $goods_id=$request->input('goods_id');
        //dd($goods_id);
        $goodsdata=Good::where(['goods_id'=>$goods_id])->first();
        $attrdata=Goods_attr::
          join('attribute','attribute.a_id','=','goods_attr.a_id')
          ->where(['goods_id'=>$goods_id])
          ->get()->toArray();
        $guige=[];
        $canshu=[];
        foreach($attrdata as $k=>$v){
            if($v['attr_type']==1){
                $guige[$v['a_id']][]=$v;
            }else{
                $canshu[]=$v;
            }
        }
        $data=json_encode([
            'goodsdata'=>$goodsdata,
            'guige'=>$guige,
            'canshu'=>$canshu,
        ]);
        //dd($data);
        $callback=$_REQUEST['jsoncallback'];
        if($callback){
            echo $callback."(".$data.")";
        }else{
            echo "(".$data.")";
        }

    }
    /**
     *登录token
     *
     */
    public function login(Request $request)
    {
        $user_name=$request->input('user_name');
        $pwd=$request->input('pwd');
        $where=['user_name'=>$user_name,'pwd'=>$pwd];
        $userdata=User::where($where)->first();
        //dd($userdata);
        if(empty($userdata)){
            return json_encode(['msg'=>'用户名密码错误','code'=>201],JSON_UNESCAPED_UNICODE);
        }
        $token=md5($userdata['user_id'].time());
        //dd($token);
        $user=User::where(['user_id'=>$userdata['user_id']])->update([
            'token'=>$token,
            'reg_time'=>time()+7200
        ]);

        if($user){
            return json_encode(['msg'=>'登录成功','code'=>200,'token'=>$token],JSON_UNESCAPED_UNICODE);
        }
    }
        /**
         *利用token获取用户信息
         *
         */
    public function info(Request $request)
    {

        //查询返回用户信息
    }
    //添加购物车
    public function addcart(Request $request)
    {
        $user_id = $request->get('user_id');//中间件产生的参数
        $goods_id=$request->input('goods_id');
        $attr_list=$request->input('attr_list');
        $goods_attr=$request->input('goods_attr');
        $attr_list=implode(',',$attr_list);
        $goods_attr=implode(' ',$goods_attr);
        $goods_num=$request->input('goods_num');
        //dd($goods_attr);
        $where=['goods_id'=>$goods_id];
        $where1=['goods_attr'=>$goods_attr];
        $data=Cart::where($where)->where($where1)->first();
        //dd($data);
        if($data){
            $cartcdata=Cart::where($where)->where($where1)->update([
                'goods_num'=>$goods_num+$data['goods_num'],
            ]);
        }else{
            $cartcdata=Cart::create([
                'goods_id'=>$goods_id,
                'attr_list'=>$attr_list,
                'goods_num'=>$goods_num,
                'user_id'=>$user_id,
                'goods_attr'=>$goods_attr,
                'add_time'=>time(),
            ]);
        }
        if($cartcdata){
            return json_encode(['msg'=>'添加购物车成功','code'=>200],JSON_UNESCAPED_UNICODE);
        }
    }
    //购物车展示
    public function  cartlist(Request $request)
    {
        //$user_id = $request->get('user_id');//中间件产生的参数
        $cartdata = Cart::join('good', 'cart.goods_id', '=', 'good.goods_id')
            ->where(['user_id' => 1])
            ->get()->toArray();
        $attr_id = [];
        $attr = [];
//       dd($cartdata);
        foreach ($cartdata as $key => $value) {
            $attr_id = explode(',', $value['attr_list']);
            //dd($attr_id);
            $attr = Goods_attr::whereIn('ga_id', $attr_id)->get()->toArray();
//            dd($attr);
            foreach ($attr as $k => $v) {
                //var_dump($cartdata[$k]['goods_price']);
                //dd($v['attr_price_list']);
                $cartdata[$key]['goods_price'] += $v['attr_price_list'];
            }
        }
        //dd($cartdata);
        return json_encode(['code' => 200, 'res' => $cartdata]);

    }

    //添加收货人地址
    public function addressadd(Request $request)
    {
        $user_id = $request->get('user_id');//中间件产生的参数
        $name=$request->input('name');
        $tel=$request->input('tel');
        $city=$request->input('city');
        $address=$request->input('address');
        $addressdata=Order_address::create([
            'name'=>$name,
            'tel'=>$tel,
            'city'=>$city,
            'address'=>$address,
            'user_id'=>$user_id,
        ]);
      if($addressdata){
          return json_encode(['msg'=>'添加地址成功','code'=>200],JSON_UNESCAPED_UNICODE);
      }else{
          return json_encode(['msg'=>'添加地址失败','code'=>201],JSON_UNESCAPED_UNICODE);
      }
    }
    //收货地址展示
    public function addresslist(Request $request)
    {
        $addressdata=Order_address::get()->toArray();
        return json_encode($addressdata);
    }
    //直接购买接口
    public function goods_buy(Request $request)
    {
        $user_id = $request->get('user_id');//中间件产生的参数
        //根据user_id查询一条地址
        $addressdata=Order_address::where(['user_id'=>$user_id])->first()->toArray();
        //dd($addressdata);
        return json_encode($addressdata);
    }
    public function  register()
    {
        return  view('admins/register');
    }
}
