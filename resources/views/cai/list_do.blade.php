<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>竞猜列表</title>
</head>
<body>
	<form action="{{url('cai/do_cai')}}" method="post">
	@csrf
	 <input type="hidden" name="q_id" value="{{$info->q_id}}">
         <h3>我要竞猜</h3>
         <h3>{{$info->q_name}}&nbsp;&nbsp; VS&nbsp;&nbsp;{{$info->q_name1}}</h3>
        
         <input type="radio" name="w_cai" value="1" id=""> &nbsp;胜&nbsp;&nbsp;&nbsp;
         <input type="radio" name="w_cai" value="2" id=""> &nbsp;平&nbsp;&nbsp;&nbsp;
         <input type="radio" name="w_cai" value="3" id=""> &nbsp;负&nbsp;&nbsp;&nbsp;<br>
         <input type="submit" value="竞猜"><br>
       


	</form> 
</body>
</html>