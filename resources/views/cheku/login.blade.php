<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登录</title>
</head>
<body>
	<form action="{{url('cheku/login_do')}}" method="post">
	@csrf
		<table>
			<tr>
				<td>账号</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td>密码</td>
				<td><input type="password" name="pwd"></td>
			</tr>	
			<tr>
				<td></td>
				<td><input type="submit" value="登录"></td>
			</tr>
		</table>
	</form>
</body>
</html>