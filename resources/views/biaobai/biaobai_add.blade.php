<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>表白菜单</title>
</head>
<body>
<center>
	<form action="{{url('biaobai/biaobai_add_do')}}" method="post">
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
			 事件类型：<select name="event_type">
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
    
    </table>
</center>
</center>
</body>
</html>
