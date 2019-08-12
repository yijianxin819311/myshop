<!DOCTYPE html>
<html>
<head>
    <title>微信用户标签列表</title>
</head>
<body>
<center>
    <a href="{{url('weixin/add_tag')}}">添加标签</a> |
    <a href="{{url('weixin/user_list')}}">粉丝列表</a>
    <br/>
    <br/>
    <br/>
    <table border="1" width="60%">
        <tr>
            <td>id</td>
            <td>标签名称</td>
            <td>标签下粉丝数</td>
            <td>操作</td>
        </tr>
        @foreach($list as $v)
        <tr>
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['count']}}</td>
            <td>
                <a href="{{url('weixin/del_tag')}}?id={{$v['id']}}">删除</a> |
                @if($v['count']!=0)
                <a href="{{url('weixin/tag_user')}}?tag_id={{$v['id']}}">粉丝列表</a> |
                @endif
                <a href="{{url('weixin/add_user_tag')}}?tag_id={{$v['id']}}">为粉丝打标签</a>
                <a href="{{url('weixin/tuisong')}}?tag_id={{$v['id']}}">推送消息</a>
            </td>
        </tr>
        @endforeach
    </table>

</center>
</body>
</html>

   
