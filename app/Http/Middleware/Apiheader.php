<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
class Apiheader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //    *等价于所有
        // 制定允许其他域名访问
        header('Access-Control-Allow-Origin:*');
        // 响应类型
        header('Access-Control-Allow-Methods:*');
        //请求头
        header('Access-Control-Allow-Headers:*');
        // 响应头设置
        header('Access-Control-Allow-Credentials:false');
        //数据类型
       // header('content-type:application:json;charset=utf8');
        header("content-type:text/html;charset=utf-8");  //设置编码
        //接口防刷获取客户端ip
//        $ip = $_SERVER['REMOTE_ADDR'];
//        $redis = new \Redis;
//        $redis->connect('127.0.0.1','6379');
//        $time = time();
//        if($redis->get('time'.$ip)==false){
//            $redis->set('time'.$ip,$time,60);
//            $redis->del('ip'.$ip);
//        }else{
//            $redis->incr('ip'.$ip);
//            if($redis->get('ip'.$ip)>10){
//                echo json_encode(['code'=>201,'msg'=>'请求次数过多，请稍后尝试！']);die;
//            }
//        }
        //ip或者ua客户端信息
        $ip=$_SERVER['REMOTE_ADDR'];
//        $ua=$_SERVER['HTTP_USER_AGENT'];
//        var_dump($ua);die;
        $cache_key='pass_time_'.$ip;
        $num=Cache::get($cache_key);
        if(!$num){
            $num=0;
        }
        if($num>60){
            echo json_encode(['code'=>201,'msg'=>'请求次数过多，请稍后尝试！']);die;
        }else{
            $num+=1;
            Cache::put($cache_key,$num,60);
        }
        echo $num;
        return $next($request);
    }
}
