@extends('index.layout.commons')
@section('title','会员展示信息')
@section('body')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="{{'/mstore/js/jquery.min.js'}}"></script>
    <link rel="stylesheet" href="{{ URL::asset('css/page.css') }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会员展示</title>

</head>
<body>
<div class="container">
    <center>
        <h3>会员展示</h3>
        <a href="{{url('member/add')}}" class="btn bin-success">添加</a>
        关键字：<input type="text" name="name" id="name">
        <input type="submit"  id="sou" value="搜索" class="btn btn-primary">
        <table  class="table table-hover">
            <tr >
                <td>编号</td>
                <td>会员姓名</td>
                <td>会员手机</td>
                <td>操作</td>
            </tr>
            <tbody id="list">
            </tbody>
        </table>
        <div  class="page ">

        </div>
    </center>
</div>
</body>
</html>
<script>
    //搜索
    $(document).on('click','#sou',function(){
        var name=$('#name').val();
        var url = "http://www.myshop.com/api/member";
        $.ajax({
            url:url,
            type:"GET",
            data:{name:name},
            dataType:"json",
            success:function(res){
                mypage(res);
                //$('#name').text().addClass('color:red');
            }
        })
    });
    //展示
    $(function(){
        var url = "http://www.myshop.com/api/member";
        $.ajax({
            url:url,
            type:"GET",
            dataType:"json",
            processData:false,
            contentType:false,
            success:function(res){
                mypage(res);
            }
        })
    });
    //分页
    $(document).on('click','.page a',function(){
       // 获取当前的页码
        var page=$(this).attr('pages');
        var name=$('#name').val();
        //alert(page);
        var url = "http://www.myshop.com/api/member";
        $.ajax({
            url:url,
            type:"GET",
            data:{page:page,name:name},
            dataType:"json",
            success:function(res){
                //console.log(res);
                //清空之前的数据
                // $('#list').empty();
                // $.each(res.data.data,function(i,v){
                //     var tr = $("<tr></tr>");
                //     tr.append("<td>"+v.member_id+"</td>");
                //     tr.append("<td>"+v.name+"</td>");
                //     tr.append("<td>"+v.tel+"</td>");
                //     tr.append("<td><a href='javascript:;' member_id='"+v.member_id+"' class='del'>删除</a>|" +
                //         "|<a href='javascript:;' member_id='"+v.member_id+"' class='save'>修改</a></td>");
                //     $('#list').append(tr);
                // })
                // var page="";
                // for(var i=1;i<=res.data.last_page;i++){
                //     page +="<a  pages='"+i+"'>第"+i+"页</a>";
                // }
                // $('.page').html(page);
                mypage(res);
            }
        })
    })
    //封装分页搜索共有代码
    function mypage(res){
        $('#list').empty();
        $.each(res.data.data,function(i,v){
            var tr = $("<tr></tr>");
            tr.append("<td>"+v.member_id+"</td>");
            tr.append("<td>"+v.name+"</td>");
            tr.append("<td>"+v.tel+"</td>");
            tr.append("<td><a href='javascript:;' member_id='"+v.member_id+"' class='del  btn btn-danger' >删除</a>|" +
                "|<a href='javascript:;' member_id='"+v.member_id+"' class='save btn btn-info'>修改</a></td>");
            $('#list').append(tr);
        })
        var page="";
        for(var i=1;i<=res.data.last_page;i++){
            if(i==res.data.current_page){
                page +="<a style='color:red'class='pagination pagination-large pagination-centered' pages='"+i+"'>第"+i+"页</a>";
            }else{
                page +="<a  class='pagination pagination-large pagination-centered' pages='"+i+"'>第"+i+"页</a>";
            }
        }
        $('.page').html(page);
    }
    //删除
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
    //修改
    $(document).on('click','.save',function(){
        var member_id=$(this).attr('member_id');
        location.href="http://www.myshop.com/member/save?id="+member_id;
        //alert(member_id);
        // function GetQueryString(name) {
        //     var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        //     var r = window.location.search.substr(1).match(reg);
        //     if (r != null) return unescape(r[2]); return null;
        // }
    })
</script>
@endsection
@section('script')
    <script>
        $(function(){

        });
    </script>
@endsection