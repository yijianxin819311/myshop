<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>模板列表</title>
</head>
<body>
	<table border="1">
		<tr>
			<td>id</td>
			<td>标题</td>
			<td>内容</td>
			<td>操作</td>
			
		</tr>
		@foreach($res as $v)
		<tr>
			<td>{{$v['template_id']}}</td>
			<td>{{$v['title']}}</td>
			<td>{{$v['content']}}</td>
			<td><a href="{{url('weixin/del_template')}}?id={{$v['template_id']}}">删除</a></td>
			
		</tr>
	@endforeach
	</table>
</body>
</html>