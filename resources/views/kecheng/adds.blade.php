<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加课程</title>
</head>
<body>
<div align="center">
    <form action="" >
       <table border=1>
       	<tr>
       		<td>编号</td>
       		<td>openid</td>
       		<td>操作</td>
       	</tr>
       	@foreach($list as $k=>$v)
       	<tr>
       		<td>{{$v->id}}</td>
			<td>{{$v->openid}}</td>
			<td>

				<a href="{{url('kecheng/addss')}}?openid={{$v->openid}}">添加课程</a>
				<a href="{{url('kecheng/update')}}?openid={{$v->openid}}">修改课程</a>
			</td>
       	</tr>
       	@endforeach
       </table>
       	
    </form>
</div>
</body>
</html>
