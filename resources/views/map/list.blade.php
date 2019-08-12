<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>地图</title>
</head>
<body>
	<form action="" >
	<a href="{{url('map/index')}}">继续测量</a>
	<table>
		<tr>
			<td><span>{{$address}}</span></td>
			<td></td>
		</tr>
		
		<tr>
			<td>
				<span>经度{{$info['lng']}}</span><br>
				<span>纬度{{$info['lat']}}</span>
			</td>
		</tr>
		</table>
	</form>
</body>
</html>