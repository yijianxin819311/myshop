<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加竞猜</title>
</head>
<body>
	 <form action="{{url('cai/add_do')}}" method="post">
	 @csrf
	
         添加竞猜球队
         <input type="text" name="q_name" id="">&nbsp;&nbsp;VS&nbsp;&nbsp;<input type="text" name="q_name1" id=""><br>
         竞猜结束时间<input type="text" name="odd_time" id=""><br>
         <input type="submit" value="添加">

	 </form>
</body>
</html>