<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>用户列表</title>
</head>
<body>

<center>

<h4>用户列表</h4>
    <table border=1 width="500">
      <tr>
        <td>id</td>
        <td>名字</td>
        <td>是否关注</td>
        <td>时间</td>
        <td>操作</td> 
      </tr>
      @foreach($user as $v)
      <tr>
        <td>{{$v->id}}</td>
        <td>{{$v->name}}</td>
        <td>{{$v->state}}</td>
        <td>{{date('Y-m-d H:i:s',$v->reg_time)}}</td>
        <td><a href="{{url('agent/creat_qrcode')}}?uid={{$v->id}}">生成二维码</a></td>
      </tr>
    @endforeach
    </table>
  </center>
</body>
</html>
