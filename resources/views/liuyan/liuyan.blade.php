<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>留言板</title>
</head>
<body>
	<form action="{{url('liuyan/do_liuyan')}}" method="post">
		<table>
			@csrf

			<tr>
				<td>内容</td>	
				<td><textarea name="neirong" id="" cols="30" rows="10"></textarea></td>
			</tr>
			
			<tr>
				<td></td>
				<td><input type="submit" value="发布"></td>
			</tr>
		</table>
	</form>
</body>
</html>