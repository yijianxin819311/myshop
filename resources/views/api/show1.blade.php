<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="{{'/mstore/js/jquery.min.js'}}"></script>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会员展示</title>
</head>
<center>
    <h2>展示用户</h2>
    <input type="text" class="sou"><button onclick="fun()">搜索</button>
    <br>

    <table border="1">
        <tr>

            <td>id</td>
            <td>用户名</td>
            <td>手机</td>
            <td>操作</td>
        </tr>
        <tbody id="list">

        </tbody>

    </table>
    <div class="page">

    </div>
</center>
{{--<body>--}}
{{--    <center>--}}
{{--        <h3>会员展示</h3>--}}
{{--        <a href="{{url('member/add')}}">添加</a>--}}
{{--        <input type="text" name="name" id="word"/>--}}
{{--        <button class="sou" id="{$arr.p}">搜索</button>--}}
{{--        <input type="text" class="sou">--}}
{{--        <button onclick="fun()">搜索</button>--}}
{{--        <input type="hidden" name="p" id="p" value="{$arr.p}">--}}
{{--        <tbody id="td">--}}
{{--        <table border="1">--}}
{{--            <tr >--}}
{{--                <td>编号</td>--}}
{{--                <td>会员姓名</td>--}}
{{--                <td>会员手机</td>--}}
{{--                <td>操作</td>--}}
{{--            </tr>--}}
{{--            <tbody id="list">--}}

{{--            </tbody>--}}

{{--        </table>--}}
{{--        <div class="page">--}}

{{--        </div>--}}
{{--    </center>--}}
{{--</body>--}}
</html>
<script>
    // $(function(){
    //     var url = "http://www.myshop.com/api/member";
    //     $.ajax({
    //         url:"http://www.myshop.com/api/member/shows",
    //         type:"GET",
    //         dataType:"json",
    //         success:function(res){
    //             //console.log(res);
    //             $.each(res.data,function(i,v){
    //                 var tr = $("<tr></tr>");
    //                 tr.append("<td>"+v.member_id+"</td>");
    //                 tr.append("<td>"+v.name+"</td>");
    //                 tr.append("<td>"+v.tel+"</td>");
    //                 tr.append("<td><a href='javascript:;' member_id='"+v.member_id+"' class='del'>删除</a>|" +
    //                     "|<a href='javascript:;' member_id='"+v.member_id+"' class='save'>修改</a></td>");
    //                 $('#list').append(tr);
    //             })
    //         }
    //     })
    // });
    $(document).on('click','.del',function(){
        var member_id=$(this).attr('member_id');
        //alert(member_id);
        var url="http://www.myshop.com/api/member";
        var tr=$(this).parent().parent();
        $.ajax({
            url:url+'/'+member_id,
            data:{member_id:member_id},
            type:'DELETE',
            dataType:'json',
            success:function(res){
                if(res.code==200){
                    alert(res.msg);
                    tr.remove();
                }
            }
        });

    })
    $(document).on('click','.save',function(){
        var member_id=$(this).attr('member_id');
        location.href="http://www.myshop.com/member/save?id="+member_id;
    })






</script>