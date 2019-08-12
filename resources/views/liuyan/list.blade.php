<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>留言列表</title>
</head>
<body>
<form action="{{url('liuyan/list')}}" method="get">
	姓名<input type="text" name="search" value="{{$search}}">
	<input type="submit" value="搜索">
</form>
	<table border=1>
		<tr>
			<th>编号</th>
			<th>留言内容</th>
			<th>留言时间</th>
			<th>姓名</th>
			<th>操作</th>
		</tr>
		@foreach ($liuyan as $v)
		<tr>
			<td>{{$v->id}}</td>
			<td>{{$v->neirong}}</td>
			<td>{{date('Y-m-d H:i:s',$v->add_time)}}</td>
			<td>{{$v->name}}</td>
			
			<td>
				<a href="{{url('liuyan/delete')}}?id={{$v->id}}">删除</a>
			</td>
			
		</tr>
		@endforeach
	</table>
	
</html>