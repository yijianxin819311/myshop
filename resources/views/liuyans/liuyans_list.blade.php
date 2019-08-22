<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>微信留言主页</title>
</head>
<body>
<center>
	<table border=1 width="500">
		<tr>
			<td>uid</td>
			<td>名字</td>
			<td>操作</td>
		</tr>
		@foreach($list as $v)
		<tr>
			<td>{{$v->uid}}</td>
			<td>{{$v->name}}</td>
			<td><a href="{{url('liuyans/add')}}?uid={{$v->uid}}">我要留言</a></td>
		</tr>
		@endforeach
	</table>
</center>
</body>
</html>