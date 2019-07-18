<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>商品展示</title>
</head>
<body>
	<center>
		<form action="{{url('admin/goods_list')}}" method="get">
					名称：<input type="text" name="search" value="{{$search}}">
					<input type="submit" name="" value="搜索">
		</form>
		<table border="1">
			<tr>
				<th>商品编号</th>
				<th>商品名称</th>
				<th>商品价格</th>
				<th>商品图片</th>
				<th>添加时间</th>
				<th>操作</th>
			</tr>
			@foreach ($goods as $v)
			<tr>
				<td>{{$v->id}}</td>
				<td>{{$v->goods_name}}</td>
				<td>{{$v->goods_price}}</td>
				<td><img src="{{$v->goods_pic}}" alt="" width="50px"></td>
				<td>{{ date('Y-m-d h:i:s',$v->add_time)}}</td>
				<td>

					<a href="{{url('admin/goods_delete')}}?id={{$v->id}}">删除 |</a>
					<a href="{{url('admin/goods_update')}}?id={{$v->id}}">修改</a>
					
				</td>
			</tr>
			@endforeach
		</table>
			{{$goods->appends(['search'=>$search])->links()}}
	</center>
</body>
</html>