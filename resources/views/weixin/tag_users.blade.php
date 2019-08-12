<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>列表</title>
</head>
<body>
<center>
<form action="{{url('weixin/tag_do')}}" method="get">
		<table border=1 width="400" height="300">
		<h3>用户</h3>
		<input type="hidden" name="tag_id" value="{{$tag_id}}">
			<tr>
				<td>选择</td>
				<td>openid</td>
				
			</tr>
			
			@foreach($openid as $v)
			
			<tr>
			<td><input type="checkbox" name="openid[]" value="{{$v}}"></td>
				<td>{{$v}}</td>
				
			</tr>
			@endforeach	
			<tr>
				
				<td colspan="2" align="center"><input type="submit" value="粉丝添加标签"></td>

			</tr>
		</table>
		</form>
	</center>
</body>
</html>