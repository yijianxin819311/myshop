<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- <meta name="csrf-token" content="{{ csrf_token() }}">
	 <script src="/static/jquery.js"></script> -->
	<title>学生信息添加</title>
	
</head>
<body>
<center>
	@if ($errors->any())
    
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        
		@endif
	<form action="{{url('student/do_add')}}" method="post">
		@csrf
		学生姓名:
		<input type="text" name="name"></br>
		年龄：
		<input type="text" name="age"></br>
		性别:
		<input type="text" name="sex"></br>
		班级：
		<input type="text" name="class_id"></br>
		
		<input type="submit" value="提交">
	</form>
</center>
</body>
</html>
<!-- <script type="text/javascript">
	$(function(){
			$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});

	});
</script> -->