<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>车辆入库</title>
</head>
<body>
	<form action="{{url('cheku/adds_do')}}" method="post">
	@csrf
	 	<table>
			<tr>
				<td>车牌</td>
				<td><input type="text" name="cart_num"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="入库"></td>
			</tr>
		</table>
	</form>
</body>
</html>