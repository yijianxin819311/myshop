<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>车辆出库</title>
</head>
<body>
	<form action="{{url('cheku/out_do')}}" method="post">
	@csrf
	 	<table>
			<tr>
				<td>车牌</td>
				<td><input type="text" name="cart_num"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="出库"></td>
			</tr>
		</table>
	</form>
</body>
</html>