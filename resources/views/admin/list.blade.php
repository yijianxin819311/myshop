<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>车次信息展示</title>
</head>
<body>
	<h3>车次信息</h3>
<form action="{{url('admin/list')}}" method="get">
					出发地：<input type="text" name="strcfd" value="{{$strcfd}}">
					目的地：<input type="text" name="endddd" value="{{$endddd}}">
					<input type="submit" name="" value="搜索">
		</form>
	<table border=1>
		<tr>
			<th>编号</th>
			<th>车次</th>
			<th>出发地点</th>
			<th>到达地点</th>
			<th>始发时间</th>
			<th>到站时间</th>
			<th>价钱</th>
			<th>数量</th>
			<th>预定</th>
		</tr>
		@foreach($list as $v)
		<tr>
			<td>{{$v->id}}</td>
			<td>{{$v->train}}</td>
			<td>{{$v->cfd}}</td>
			<td>{{$v->ddd}}</td>
			<td>{{$v->start_time}}</td>
			<td>{{$v->end_time}}</td>
			<td>{{$v->price}}</td>
			@if($v->number==0)
				<td>无</td>
			@elseif($v->number>=100)
				<td>有</td>
			@else
			<td>{{$v->number}}</td>
			@endif
			<td><button><a href="{{url('admin/add')}}">预定</a></button></td>
		</tr>
		@endforeach
	</table>
	
</body>
</html>