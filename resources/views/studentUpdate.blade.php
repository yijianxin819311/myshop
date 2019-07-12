
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>修改</title>
</head>
<body>
	<form action="{{url('student/do_update')}}" method="post">
		<input type="hidden" name="id" value="{{$student_info->id}}">
		@csrf
		学生姓名:
		<input type="text" name="name" value="{{$student_info->name}}"></br>
		年龄：
		<input type="text" name="age" value="{{$student_info->age}}"></br>
		性别:
		<input type="text" name="sex" value="{{$student_info->sex}}"></br>
		班级：
		<input type="text" name="class_id" value="{{$student_info->class_id}}"></br>
		时间：
		<input type="text" name="addtime" value="{{$student_info->addtime}}"></br>
		<input type="submit" value="修改">
	</form>
</body>
</html>