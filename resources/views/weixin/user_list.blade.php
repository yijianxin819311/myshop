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
				<td>id</td>
				
				<td>操作</td>	
			</tr>
			@foreach($res as $v)
			<tr>
				<td>{{$v->openid}}</td>
				<td><a href="{{url('weixin/lists')}}?id={{$v->id}}">详情</a></td>
			</tr>
		@endforeach
		</table>
	</center>
</body>
</html>