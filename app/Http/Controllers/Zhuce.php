<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Zhuce extends Controller
{
	//注册视图
   public function registers(){
   		return view('zhuce/registers');
   }
   //发送邮件
   public function sendEmail()
   {
      //控制器接收ajax传来的邮箱
      $user_email=input('post.user_email');
      //验证邮箱
      //PHP正则验证格式
      $reg='/^\w+@\w+\.com$/';
      if(empty($user_email)){
          fail('邮箱必填');
      }else if(!preg_match($reg,$user_email)){
          fail('邮箱格式有误');
      }
      $code=createCode();
      $subject='注册成功';
      $body="您的验证码是:".$code.',5分钟内有效，打死不能说,五分钟内有效';
      $res=sendEmail($user_email,$subject,$body);
      //dump($res);die;
      if($res){
          //成功写入cookie 时间邮箱 验证码
          $emailInfo=[
              'account'=>$user_email,
              'code'=>$code,
              'send_time'=>time(),
          ];
          cookie('emailInfo',$emailInfo);
          successly('发送成功');
      }else{
          fail('发送失败');
      }
   }
   public function login(Request $request){
      
   }
}