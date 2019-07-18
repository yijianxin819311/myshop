<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>商品添加</title>
</head>
<body>
	<form action="{{url('admin/goods_doupdate')}}" method="post" enctype="multipart/form-data">
		@csrf
		<table border=1>
			<input type="hidden" name="id" value="{{$goods_info->id}}">
			<tr>
				<td>商品名称</td>
				<td><input type="text" name="goods_name" value="{{$goods_info->goods_name}}"></td>
			</tr>
			<tr>
				<td>商品图片</td>
				<td><input type="file" name="goods_pic">
					<img src="{{$goods_info->goods_pic}}" alt="">
				</td>
			</tr>
			<tr>
				<td>商品库存</td>
				<td><input type="text" name="goods_num" value="{{$goods_info->goods_num}}"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="修改"></td>
			</tr>
		</table>
	</form>
</body>
</html>