<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>车库管理系统</title>
</head>
<body>
	<form action="" method="post">
	@csrf
	 	<table>
			<h4>车库管理系统</h4>
			<span>小区车位：400</span>
        	<span>剩余车位：{{$cart_left_num}}</span><br>
			<button><a href="{{url('cheku/adds')}}">车辆入库</a></button>
			<button><a href="{{url('cheku/out')}}">车辆出库</a></button>
		</table>
	</form>
</body>
</html>