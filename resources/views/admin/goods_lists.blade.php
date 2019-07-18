<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>列表展示</title>
</head>
<body>
	<form action="{{url('admin/goods_lists')}}" method="get">
		商品名称：<input type="text" name="search" value="{{$search}}">
		<input type="submit" value="搜索">
	</form>
	<table border=1>
		<tr>
			<th>商品编号</th>
			<th>商品名称</th>
			<th>商品图片</th>
			<th>商品库存</th>
			<th>添加时间</th>
			<th>操作</th>
		</tr>
		@foreach ($goods as $v)
		<tr>
			<td>{{$v->id}}</td>
			<td>{{$v->goods_name}}</td>
			<td><img src="{{$v->goods_pic}}" width="50px" alt=""></td>
			<td>{{$v->goods_num}}</td>
			<td>{{date('Y-m-d H:i:s',$v->add_time)}}</td>
			<td>
				<a href="{{url('admin/goods_del')}}?id={{$v->id}}">删除|</a>
				<a href="{{url('admin/goods_upd')}}?id={{$v->id}}">修改</a>
			</td>

		</tr>
	@endforeach
	</table>
	{{$goods->appends(['search'=>$search])->links()}}
</body>
</html>