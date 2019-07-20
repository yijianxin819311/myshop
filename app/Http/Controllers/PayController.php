<?php
namespace App\Http\Controllers;
use App\Http\index\Model\OrderDetail;
use Illuminate\Http\Request;
use App\Http\index\Model\Cart;
use App\Http\index\Model\Order;
use App\Http\admin\Model\Goods;
use DB;
use Pay;
class PayController extends Controller
{
    public $app_id;
    public $gate_way;
    public $notify_url;
    public $return_url;
    public $rsaPrivateKeyFilePath = '';  //路径
    public $aliPubKey = '';  //路径
    public $privateKey = "MIIEogIBAAKCAQEAuEzLzcU82UjMOxeH3+F0H4P/fZhLbayk6XrAbQyVTeTEqsZ8E1qDcyrzxP4pjnCGuX5MghcTqgYKcsHzRT4MSkQm/u8nG0JqFevbN3HL68vJg0cYfO3n6cOvy/V/fBHgpbFcCsZqKgpHpP0Lue8/eROcmEwyrj+nvRemmGfEb5aFlYptxFURAL0lDO54PPFd4z/k651orMLQYlTTVxUWiYQdGGpsnxHv2QpVYQ+l16S2gCAKdGIw461vB7tKxSGbJWwEacoqQBf998Kv2INnSLbUTaRQxnA1HbKlxrp/W5Upc/NLP5lCQmjOD4XvsTMJxlrsAvJagWJdKXO3BozcWwIDAQABAoIBADmU5Og5g7VvpT+hLnaofhuKpjybZJWzpyK7k3t+vvdDTp78vK9fTEqjVN5rephiV/WEnGrYwvAQSxtntZYguL5LZNOp7NH7giGvOyKrj0bwuNknUWNXAWpsFXToExi0jTe3nkZPj8Pqyk+mMA+7zF0L35VF2V/Py5ys0wQzB5DFMqFwFMWP5BHgaeEoAOtWI2Ilsi7JUHi77JbkZQLtRQJXNBKflF+qpXupj5e1PudsVG/uT0aoikORvSKpA4cwl4mgd45y5z8EH1mHaE/dcX18WBUcM56bdZfPebKRRmY7cHR9JBiEA2SuVlhX/msxksa60rm6/tGJWaopBweuxeECgYEA5x9eVqbdlKitjJaUxfzb7pHW5ZTlBiekuPfvUzHRRVc86FwMdtpuIGvD+X//gPg/BYlxr+UDXID7W9OqG1ooVSzxE2VJScyDuIHt5DvkAoCiMy3otLbKup/Auk1H8X8OGqt59vf2yEjrCTH1AtsDS/VTSr77VP0ANrpDlK0Pxy0CgYEAzCM43Xu6FA0wtH+HVEpMKVnMXNnaJpUqbWWu6oZa+PWDfNO51yvsHCm5PZt9LsQmx91kTJhf3kk9l+ygVfkMywTVJPD4B2KVcSXuYJmZoCgTj0CV8XFTtcM+xBdXVjTr/m5JUAIept/qJwBLLU810WqjytESK4SU0eyHYuc+ZqcCgYBaZ3+8P0sfEhfIjUIma7EPkYZQiTxIewtIutqnXS4xhF0zuoW5m9lF30Fp/7JOnUvxe4C3v8nfzigEFDhOuKZcItctsgnHzzKa4l/OTcrzW6OWh1emEHYeJ8z4UrBYe01mCzsK6pViZHtjPmJLOqYAeTaaXzfqV4hU3+j9Z/BrBQKBgE4Ppf4kAwsJ5DcRkUltQ+SIHzreX6pjXh8JRDMQf5c2IzrHqAgdFqPKXLivTdZlUcMZ6PHVTsuszC3dYY6etwKnbW4760y/qQ/khlxQQEvbJ2efNfdm87DM4aKQnmgrwpa/y68ZsGj1tQ+G8Dh6UsUq90lmW4FiqJoBzd/HN7h/AoGARhiM64D18HkFqr/QjpMmQd+jfVXZ5Biwd9JnnnFj9+miarFCBkgUMVMYdz/IU2h/0twlu4v3e5P1axW+ecZCfgZ9pRzzyboiPFczFNGWX2sKuCUMcIkr6fWRJB/lvyGp4TFmafrxuIvAoa3vSpq6qL1QFO6aW7QlIht0l4uqfho=";
    public $publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAigLSHqN1fabfDcGXEOEsMSTgkzrs8T/XA8s3Ub9PfroE6+24CHB/UI/ofHt9edvD6v/XrceST1INjgr2XV+qAEfGtoC47ztjC1s+rfXi5E0TNPfxC2//7AGI1f1fT9lnJRADcPjgSEjgp3lA8i2SdlFCfDooeFPhLDHW56Et3NhYCBCQbRAhgW8aiCrmYcSMFRo1flVm5f+BAeljDSh0qMQNfo6ZuJnt9Mggmt1cHvPA7u+XoHv0VjHh5lYosNEdrzMKaflJErkF4PuII+HPWZ5D82QQmzPgdsO8lYSIk3pV4MqKj1Lmz5Jzr3zA13SVxssO2w8k1OMfXPThNu//JQIDAQAB";
    public function __construct(Cart $cart,Order $order,OrderDetail $orderDetail)
    {
        $this->cart=$cart;
        $this->order=$order;
        $this->orderDetail=$orderDetail;
        $this->app_id = '2016101100657577';
        $this->gate_way = 'https://openapi.alipaydev.com/gateway.do';
        $this->notify_url = env('APP_URL').'/notify_url';
        $this->return_url = env('APP_URL').'/return_url';
    }
    //    订单详情
    public function confirm_pay(){
            if(empty(session('name'))){
                echo '请先登录';die;
            }
        $cart = $this->cart->where(['uid'=>session('id')])->get();
//        dd($cart);
        $total = 0;
        foreach($cart->toArray() as $v){
            $total += $v['goods_price'];
        }
//        dd($total);
        return view('index.order',['cart'=>$cart,'total'=>$total]);
    }
        public function pay_order(Request $request){
        $oid=$request->all();
        if(empty($oid)){
            echo '订单有误';die;
        }
        $this->ali_pay($oid['oid']);
    }
    public function do_pay(){
        DB::connection('mysql')->beginTransaction(); //开启事务
        $cart = $this->cart->where(['uid'=>session('id')])->get();
        //dd($cart);
        if(empty($cart)){
            echo '购物车没有此商品';
        }
        $total = 0;
        foreach($cart->toArray() as $v){
            $total += $v['goods_price'];
        }
        $oid = time().mt_rand(1000,1111);  //订单编号
        $order=$this->order->insert([
            'oid'=>$oid,
            'uid'=>session('id'),
            'pay_money'=>$total,
            'pay_time'=>time(),
            'add_time'=>time(),
        ]);
        //dd($order);
        $orderdetail='';
        foreach($cart as $k=>$v){
            $orderdetail=$this->orderDetail->insert([
                'oid'=>$oid,
                'goods_id'=>$v['goods_id'],
                'goods_name'=>$v['goods_name'],
                'goods_pic'=>$v['goods_pic'],
                'add_time'=>time(),
            ]);
        }
        //dd($orderdetail);
        $delete=$this->cart->where('uid',session('id'))->delete();
        if(!$order || !$orderdetail ||!$delete){
            DB::connection('mysql')->rollBack();
            die('操作失败!');
        }

        DB::connection('mysql')->commit();

        $this->ali_pay($oid);
    }
    
    public function rsaSign($params) {
        return $this->sign($this->getSignContent($params));
    }
    protected function sign($data) {
        if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
            $priKey=$this->privateKey;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        }else{
            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
        }
        
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, 'UTF-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }

    

    /**
     * 根据订单号支付
     * [ali_pay description]
     * @param  [type] $oid [description]
     * @return [type]      [description]
     */
    public function ali_pay($oid){
        $order = $this->order->where(['oid'=>$oid,'state'=>1])->first();
        if(empty($order)){
            echo '订单不存在';die;
        }
        $order_info = $order->toArray();
//        dd($order_info['pay_money']);
//        业务参数
        $bizcont = [
            'subject'           => 'Lening-OrderDetail: ' .$oid,
            'out_trade_no'      => $oid,
            'total_amount'      => $order_info['pay_money'],
            'product_code'      => 'FAST_INSTANT_TRADE_PAY',
        ];
        //公共参数
        $data = [
            'app_id'   => $this->app_id,
            'method'   => 'alipay.trade.page.pay',
            'format'   => 'JSON',
            'charset'   => 'utf-8',
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'   => '1.0',
            'notify_url'   => $this->notify_url,        //异步通知地址
            'return_url'   => $this->return_url,        // 同步通知地址
            'biz_content'   => json_encode($bizcont),
        ];
        //签名
        $sign = $this->rsaSign($data);
        $data['sign'] = $sign;
        $param_str = '?';
        foreach($data as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $url = rtrim($param_str,'&');
        $url = $this->gate_way . $url;
//        dd($url);
        header("Location:".$url);
    }
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = 'UTF-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }
    /**
     * 支付宝同步通知回调
     */
    public function aliReturn()
    {
//        dd('222');
        header('Refresh:2;url=index/order_list');
        echo "<h2>订单： ".$_GET['out_trade_no'] . ' 支付成功，正在跳转</h2>';
    }
    /**
     * 支付宝异步通知
     */
    public function aliNotify()
    {
        $data = json_encode($_POST);
        $log_str = '>>>> '.date('Y-m-d H:i:s') . $data . "<<<<\n\n";
        //记录日志
        file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
        //验签
        $res = $this->verify($_POST);
        $log_str = '>>>> ' . date('Y-m-d H:i:s');
        if($res){
            //记录日志 验签失败
            $log_str .= " Sign Failed!<<<<< \n\n";
            file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
        }else{
            $log_str .= " Sign OK!<<<<< \n\n";
            file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
            //验证订单交易状态
            if($_POST['trade_status']=='TRADE_SUCCESS'){
                $oid=$_POST['out_trade_no'];
                $info=[
                    'pay_time'=>[strtotime($_POST('timestamp'))],//支付时间
                    'state'=>2,
                ];
                $order=$this->order->where('oid',$oid)->update($info);//修改支付状态

            }
        }
        
        echo 'success';
    }
    //验签
    function verify($params) {
        $sign = $params['sign'];
        if($this->checkEmpty($this->aliPubKey)){
            $pubKey= $this->publicKey;
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        }else {
            //读取公钥文件
            $pubKey = file_get_contents($this->aliPubKey);
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
        }
        
        
        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($this->getSignContent($params), base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        
        if(!$this->checkEmpty($this->aliPubKey)){
            openssl_free_key($res);
        }
        return $result;
    }
}

   

