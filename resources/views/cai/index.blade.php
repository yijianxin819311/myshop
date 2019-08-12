<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>竞猜显示</title>
</head>
<body>
	<table align="center">
	@csrf
           <h3>竞猜列表</h3>
           @foreach($info as $v)
             {{$v->q_name}}vs{{$v->q_name1}}  
              
             @if($v->odd_time< time())
             <button><a href="{{url('cai/list')}}?id={{$v->q_id}}">查看结果</a></button><br>
             @else
             <button><a href="{{url('cai/list_do')}}?id={{$v->q_id}}">竞猜</a></button>
             <br>
             
             @endif
           @endforeach
	</table>
</body>
</html>