<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>列表</title>
</head>
<body>

<center>
<a href="{{url('weixin/get_user_info')}}">刷新用户列表</a>
<h4>粉丝列表</h4>
		<table border=1 width="500">
			<tr>
				<td>openid</td>
				
				<td>操作</td>	
			</tr>
			@foreach($res as $v)
			<tr>
				<td>{{$v->openid}}</td>
				<td>
					<a href="{{url('weixin/lists')}}?openid={{$v->openid}}">详情</a>
					<a href="{{url('kecheng/add')}}?openid={{$v->openid}}">课程管理</a>
					<a href="{{url('kecheng/list')}}?openid={{$v->openid}}">查看课程</a>
				</td>
			</tr>
		@endforeach
		</table>
	</center>
</body>
</html>