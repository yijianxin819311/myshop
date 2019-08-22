<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>我的留言</title>
</head>
<body>
<center>
	<table border=1 width="500">
		<tr>
			<td>id</td>
			<td>uid</td>
			<td>名字</td>
			<td>内容</td>
			<td>时间</td>
			
		</tr>
		@foreach($list as $v)
		<tr>
			<td>{{$v->id}}</td>
			<td>{{$v->uid}}</td>
			<td>{{$v->name}}</td>
			<td>{{$v->neirong}}</td>
			<td>{{date('Y-m-d h:i:s',$v->add_time)}}</td>
			
		</tr>
		@endforeach
	</table>
</center>
</body>
</html>