<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>商品添加</title>
</head>
<body>
	<form action="{{url('admin/goods_doadd')}}" method="post" enctype="multipart/form-data">
		@csrf
		<table border=1>
			<tr>
				<td>商品名称</td>
				<td><input type="text" name="goods_name"></td>
			</tr>
			<tr>
				<td>商品图片</td>
				<td><input type="file" name="goods_pic"></td>
			</tr>
			<tr>
				<td>商品库存</td>
				<td><input type="text" name="goods_num"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="添加"></td>
			</tr>
		</table>
	</form>
</body>
</html>