<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    public static function post($url, $data = []){
        //初使化init方法
        $ch = curl_init();
        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送什么数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //发送请求
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }
    /**
     * get请求
     * @param $url
     */
    public static function get($url)
    {
        //1初始化
        $ch = curl_init();
        //2设置
        curl_setopt($ch,CURLOPT_URL,$url); //访问地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回格式
        //请求网址是https
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); // 对认证证书来源的检查
        //3执行
        $content = curl_exec($ch);
        //4关闭
        curl_close($ch);
        return $content;
    }
    /**
     * get、post
     */
    public static function curl($url,$method="GET",$data = [],$header = []){
        //1初始化
        $ch = curl_init();
        //2设置
        curl_setopt($ch,CURLOPT_URL,$url); //访问地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回格式
        if($method ="POST"){
            curl_setopt($ch,CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data); // Post提交的数据包
        }
        // 设置请求头部信息
        if(!empty($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        }
        // $header[] = "Content-type: application/json;charset='utf-8'"; //如果发送json数据
        // $header[] = "Content-type: text/xml"; //如果发送xml数据

        //请求网址是https
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); // 对认证证书来源的检查
        //3执行
        $content = curl_exec($ch);
        //4关闭
        curl_close($ch);
        return $content;
    }
}
