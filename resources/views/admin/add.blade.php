<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>车票</title>
</head>
<body>
<form action="{{url('admin/add_do')}}" method="post"> 
@csrf
	<table>
	
		<tr>
			<td>车次</td>
			<td><input type="text" name="train"></td>
		</tr>
		<tr>
			<td>出发地点</td>
			<td><input type="text" name="cfd"></td>
		</tr>
		<tr>
			<td>到达地点</td>
			<td><input type="text" name="ddd"></td>
		</tr>
		<tr>
			<td>价格</td>
			<td><input type="text" name="price"></td>
		</tr>
		<tr>
			<td>数量</td>
			<td><input type="text" name="number"></td>
		</tr>
		<tr>
			<td>出发时间</td>
			<td><input type="date" name="start_time"></td>
		</tr>
		<tr>
			<td>结束时间</td>
			<td><input type="date" name="end_time"></td>
		</tr>
		<tr>
			<td>提交订票</td>
			<td><input type="submit" value="预定"></td>
		</tr>
	</table>
</form>
</body>
</html>