<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>比赛结果</title>
</head>
<body>
	<form action="{{url('cai/jieguo_do')}}" method="post">
	@csrf
	 <input type="hidden" name="q_id" value="{{$info->q_id}}">
         <h3>比赛结果</h3>
         <h3>{{$info->q_name}}&nbsp;&nbsp; VS&nbsp;&nbsp;{{$info->q_name1}}</h3>
        
         <input type="radio" name="w_cai" value="1" id=""> &nbsp;胜&nbsp;&nbsp;&nbsp;
         <input type="radio" name="w_cai" value="2" id=""> &nbsp;平&nbsp;&nbsp;&nbsp;
         <input type="radio" name="w_cai" value="3" id=""> &nbsp;负&nbsp;&nbsp;&nbsp;<br>
         <input type="submit" value="添加结果"><br>
       


	</form> 
</body>
</html>