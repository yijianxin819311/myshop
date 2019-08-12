<!DOCTYPE html>
<html>
<head>
    <title>微信用户标签列表</title>
</head>
<body>
<center>
    
   
    <table border="1" width="60%">
        <tr>
            <td>id</td>
            <td>素材名字</td>
            <td>时间</td>
            
        </tr>
        @foreach($list as $v)
        <tr>
            <td>{{$v['media_id']}}</td>
            <td>{{$v['name']}}</td>
            
            
        </tr>
        @endforeach
    </table>

</center>
</body>
</html>

   
