<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>菜单</title>
</head>
<body>
<center>
	<form action="{{url('caidan/add_do')}}" method="post">
		@csrf
		<table>
			菜单类型：
			<select name="menu_type" id="">
				<option value="1">一级菜单</option>
				<option value="2">二级菜单</option>
			</select><br/>
			一级菜单名称：<input type="text" name="menu_name"><br/>
			二级菜单名称：<input type="text" name="menu_names"><br/>
			
			 菜单标识或url：<input type="text" name="menu_tag" ><br/><br/>
			 事件类型：<select name="event_type" >
            <option value="click">click</option>
            <option value="view">view</option>
            <option value="scancode_push">scancode_push</option>
            <option value="scancode_waitmsg">scancode_waitmsg</option>
            <option value="pic_sysphoto">pic_sysphoto</option>
            <option value="pic_photo_or_album">pic_photo_or_album</option>
            <option value="pic_weixin">pic_weixin</option>
            <option value="location_select">location_select</option>
            <option value="media_id">media_id</option>
        </select><br/><br/>
        <input type="submit" value="提交">
		</table>
	</form>
	 <br><br>
    <h3>菜单展示</h3>
    <table border="1" width="1000">
        <tr>
            <td width="7%">菜单结构</td>
            <td width="7%">菜单编号</td>
            <td width="10%">菜单名称</td>
            <td width="10%">二级菜单名</td>
            <td>菜单等级</td>
            <td>事件类型</td>
            <td>菜单标识</td>
            <td>操作</td>
        </tr>
        @foreach($list as $v)
        <tr>
            <td>{{$v['menu_str']}}</td>
            <td>{{$v['menu_num']}}</td>
            <td>@if(empty($v['menu_names'])){{$v['menu_name']}}@endif</td>
            <td>{{$v['menu_names']}}</td>
            <td>{{$v['menu_type']}}</td>
            <td>{{$v['event_type']}}</td>
            <td>{{$v['menu_tag']}}</td>
            <td><a href="">删除</a></td>
        </tr>
        @endforeach
    </table>
</center>
</center>
</body>
</html>