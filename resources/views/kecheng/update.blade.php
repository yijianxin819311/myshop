<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>课程添加</title>
</head>
<body>
  <center>
    <form action="{{url('kecheng/update_do')}}" method="post">
      <h3>课程管理</h3>
        <input type="hidden" name="openid" value="{{$data->openid}}}">
      @csrf
      第一节课：<select name="first_kecheng" id="" value="{{$data->first_kecheng}}">
            <option value="1">php</option>
            <option value="2">语文</option>
            <option value="3">数学</option>
            <option value="4">英语</option>
          </select><br>
      第二节课：<select name="two_kecheng" id="" value="{{$data->two_kecheng}}">
            <option value="1">php</option>
            <option value="2">语文</option>
            <option value="3">数学</option>
            <option value="4">英语</option>
          </select><br>
      第三节课：<select name="three_kecheng" id="" value="{{$data->three_kecheng}}">
            <option value="1">php</option>
            <option value="2">语文</option>
            <option value="3">数学</option>
            <option value="4">英语</option>
          </select><br>
      第四节课：<select name="four_kecheng" id="" value="{{$data->four_kecheng}}">
            <option value="1">php</option>
            <option value="2">语文</option>
            <option value="3">数学</option>
            <option value="4">英语</option>
          </select><br>
      <input type="submit" value="修改">

    </form>
  </center>
</body>
</html>