<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>课程添加</title>
</head>
<body>
  <center>
    <form action="{{url('kecheng/add_do')}}" method="post">
      <h3>课程管理</h3>
        <input type="hidden" name="openid" value="{{$openid}}}">
      @csrf
      第一节课：<select name="first_kecheng" id="">
            <option value="php">php</option>
            <option value="语文">语文</option>
            <option value="数学">数学</option>
            <option value="英语">英语</option>
          </select><br>
      第二节课：<select name="two_kecheng" id="">
             <option value="php">php</option>
            <option value="语文">语文</option>
            <option value="数学">数学</option>
            <option value="英语">英语</option>
          </select><br>
      第三节课：<select name="three_kecheng" id="">
             <option value="php">php</option>
            <option value="语文">语文</option>
            <option value="数学">数学</option>
            <option value="英语">英语</option>
          </select><br>
      第四节课：<select name="four_kecheng" id="">
             <option value="php">php</option>
            <option value="语文">语文</option>
            <option value="数学">数学</option>
            <option value="英语">英语</option>
          </select><br>
      <input type="submit" value="提交">

    </form>
  </center>
</body>
</html>