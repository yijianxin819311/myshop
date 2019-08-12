<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>注册</title>
</head>
<body>
	<form action="">
		<table>
			<tr>
				<td><input type="text" name="user_email"  id="user_email"  placeholder="请输入邮箱账号"></td>
			</tr>
			<tr>
				<td>
				<input type="text" name="user_code" id="email_code"   placeholder="请输入验证码"><button><span  id="span_email">获取</span></button>
				</td>
			</tr>
			<tr>
				<td><input type="password" name="user_pwd" id="email_pwd"  placeholder="设置密码"></td>
			</tr>
			<tr>
				<td><input type="password" name="user_pwd1" id="email_pwd1"   placeholder="确认密码"></td>
			</tr>
			<tr>
				<td><input type="submit" value="注册"></td>
			</tr>
		</table>
	</form>
</body>
</html>
 
 <script src="{{asset('mstore/js/jquery.min.js')}}"></script>


<script>
	$(function(){
		$('#span_email').click(function(){
			//1获取文本框邮箱的值
			var user_email=$('#user_email').val();
			//alert(user_email);
			///2.验证邮箱是否为空是否格式正确
			//正则验证格式
			var reg=/^\w+@\w+\.com$/;
			if(user_email==""){
				alert('邮箱必填');
				return false;
			}else if(!reg.test(user_email)){
				alert('邮箱格式错误');
				return false;
			}
			// 秒数倒计时
		var his=30;
		$('#span_email').text(his+'s');
		//定时器
		_time=setInterval(lessSecound,1000);//没多少秒执行一次

		function lessSecound(){
			//获取秒数
			var num=$('#span_email').text();
			num=parseInt(num);

			if(num<=0){
			$('#span_email').text('获取');	
			//清除定时器
			clearInterval(_time);
			//按钮生效
			$('#span_email').css('pointer-events','auto');
			}else{
				//把秒数-1
				num=num-1;
			$('#span_email').text(num+'s');
			//按钮失效
			$('#span_email').css('pointer-events','none');
			}
			
		}

			//4.把已经验证好的邮箱通过ajax技术传给控制器
			$.post("{:url('sendEmail')}",
				{user_email:user_email},
				function(res){
					//console.log(res);
					layer.msg(res.msg,{icon:res.code});

				},'json');
			
		});
		
	});
</script>