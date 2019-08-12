<!DOCTYPE html>
<html>
<head>
    <title>微信用户标签列表</title>
</head>
<body>
<center>
   <h3>粉丝列表</h3>
    <table border="1" width="60%">
        <tr>
            <td>id</td>
            <td>操作</td>
        </tr>
        @foreach($list['openid'] as $v)
        <tr>
            <td>{{$v}}</td>
            <td><a href="{{url('weixin/get_user_tag')}}?id={{$v}}">获取用户标签</a></td>
           
        </tr>
        @endforeach
    </table>

</center>
</body>
</html>

   
