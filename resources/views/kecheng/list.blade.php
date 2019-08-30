<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>我的课程</title>
</head>
<body>
<div align="center">
    <table width="300" border="1">
        <tr>
            <th>编号</th>
            <th>第一节课</th>
            <th>第二节课</th>
            <th>第三节课</th>
            <th>第四节课</th>

            <th>时间</th>
        </tr>
        @foreach($list as $k=>$v)
            <tr>
                <td>{{$v->id}}</td>
                <td>{{$v->first_kecheng}}</td>
                <td>{{$v->two_kecheng}}</td>
                <td>{{$v->three_kecheng}}</td>
                <td>{{$v->four_kecheng}}</td>
                <td>{{date('Y-m-d H:i:s',$v->add_time)}}</td>
            </tr>
            @endforeach
    </table>
</div>
</body>
</html>
