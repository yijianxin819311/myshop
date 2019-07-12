<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>student</title>
</head>
<body>
	<center>	
		<h1>学生列表</h1>
		<form action="{{url('student/index')}}" method="get">
			姓名：<input type="text" name="search" value="{{$search}}">
			<input type="submit" name="" value="搜索">
		</form>
		
		<table border="1">
			<tr>
				<td>id</td>
				<td>姓名</td>
				<td>年龄</td>
				<td>性别</td>
				<td>班级</td>
				<td>时间</td>
				<td>操作</td>
			</tr>
			@foreach ($student as $v)
			<tr>
				<td>{{$v->id}}</td>
				<td>{{$v->name}}</td>
				<td>{{$v->age}}</td>
				<td>{{$v->sex}}</td>
				<td>{{$v->class_id}}</td>
				<td>{{ date('Y-m-d h:i:s',$v->addtime)}}</td>
				<td>
					<a href="{{url('student/delete')}}?id={{$v->id}}">删除</a>
					<a href="{{url('student/update')}}?id={{$v->id}}">修改</a>
				</td>
			</tr>
			@endforeach
		</table>
		{{$student->appends(['search'=>$search])->links()}}
	<center>
</body>
</html>