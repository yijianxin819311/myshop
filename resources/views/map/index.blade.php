<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>地图</title>
</head>
<body>
	<form action="{{url('map/add')}}" method="post">
	@csrf
	<table>
		<tr>
			<td><span>地名</span></td>
		</tr>
		<tr>
			<td><textarea name="address" id="" cols="30" rows="10"></textarea></td>
		</tr>
		<tr>
			<td><button>地名解析</button></td>
		</tr>
		</table>
	</form>
</body>
</html>