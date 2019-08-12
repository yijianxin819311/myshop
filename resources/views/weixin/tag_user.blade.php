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
            
        </tr>
        @foreach($list as $v)
        <tr>
            <td>{{$v['openid']}}</td>
            
        </tr>
        @endforeach
    </table>

</center>
</body>
</html>

   
