<!DOCTYPE html>
<html>
<head>
    <title>题库列表</title>
</head>
<body>
<center>
    <a href="{{url('admin/add_papers')}}">添加试卷</a>
    <table border=1>
    <tr>
    	<th>编号</th>
    	<th>题型</th>
    	<th>问题</th>
    	<th>时间</th>
    </tr>
    @foreach($problem as $v)
    <tr>
    	<td>{{$v->id}}</td>
    	<td>{{$v->type_id}}</td>
    	<td>{{$v->problem}}</td>
    	<td>{{date('Y-m-d h:i:s',$v->add_time)}}</td>
    </tr>
    @endforeach
    </table>
</center>
</body>
</html>