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
    <title>商品展示</title>

</head>
<body>
<div class="container">
    <center>
        <h3>商品展示</h3>
        <a href="{{url('goods/adds')}}">添加</a>
        关键字：<input type="text" name="name" id="name">
        <input type="submit"  id="sou" value="搜索" class="btn btn-primary">
        <table  class="table table-hover">
            <tr >
                <td>商品编号</td>
                <td>商品名称</td>
                <td>商品图片</td>
                <td>商品价格</td>
                <td>时间</td>
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
@endsection
@section('script')
    <script>
        //展示
        $(function(){
            //alert(1);
            var url="http://www.myshop.com/api/goods";
            $.ajax({
                url:url,
                type:"GET",
                dataType:"json",
                //processData:false,
                //contentType:false,
                success:function(res){

                    $.each(res.data.data,function(i,v){
                        var tr = $("<tr></tr>");
                        tr.append("<td>"+v.g_id+"</td>");
                        tr.append("<td>"+v.goods_name+"</td>");
                        tr.append("<td><img width='50' id='img' src='/"+v.goods_file+"'></td>");
                        tr.append("<td>"+v.goods_price+"</td>");
                        tr.append("<td>"+v.add_time+"</td>");
                        //tr.append("<td>"+date('Y-m-d H:i:s',v.add_time)+"</td>");
                        tr.append("<td><a href='javascript:;' g_id='"+v.g_id+"' class='del  btn btn-danger' >删除</a>|" +
                            "|<a href='javascript:;' g_id='"+v.g_id+"' class='save btn btn-info'>修改</a></td>");
                        $('#list').append(tr);
                    })
                    var page="";
                    for(var i=1;i<=res.data.last_page;i++){
                        if(i==res.data.current_page){
                            page +="<a style='color:red'class='pagination pagination-large pagination-centered' pages='"+i+"'>"+i+"</a>";
                        }else{
                            page +="<a  class='pagination pagination-large pagination-centered' pages='"+i+"'>"+i+"</a>";
                        }
                    }
                    $('.page').html(page);
                }
            })
        });
        //搜索
        $(document).on('click','#sou',function(){
            //alert(1);
            var url="http://www.myshop.com/api/goods";
            var name=$('#name').val();
            //alert(goods_name);
            $.ajax({
                url:url,
                type:"GET",
                data:{name:name},
                dataType:"json",
                //processData:false,
                //contentType:false,
                success:function(res){
                    $('#list').empty();
                    $.each(res.data.data,function(i,v){
                        var tr = $("<tr></tr>");
                        tr.append("<td>"+v.g_id+"</td>");
                        tr.append("<td>"+v.goods_name+"</td>");
                        tr.append("<td><img width='50' src='/"+v.goods_file+"'></td>");
                        tr.append("<td>"+v.goods_price+"</td>");
                        tr.append("<td>"+v.add_time+"</td>");
                        //tr.append("<td>"+date('Y-m-d H:i:s',v.add_time)+"</td>");
                        tr.append("<td><a href='javascript:;' g_id='"+v.g_id+"' class='del  btn btn-danger' >删除</a>|" +
                            "|<a href='javascript:;' g_id='"+v.g_id+"' class='save btn btn-info'>修改</a></td>");
                        $('#list').append(tr);
                    })
                    var page="";
                    for(var i=1;i<=res.data.last_page;i++){
                        if(i==res.data.current_page){
                            page +="<a style='color:red'class='pagination pagination-large pagination-centered' pages='"+i+"'>"+i+"</a>";
                        }else{
                            page +="<a  class='pagination pagination-large pagination-centered' pages='"+i+"'>"+i+"</a>";
                        }
                    }
                    $('.page').html(page);
                }
            })
        });
        //分页
        $(document).on('click','.page a',function(){
            // 获取当前的页码
            var page=$(this).attr('pages');
            var name=$('#name').val();
            //alert(page);
            var url = "http://www.myshop.com/api/goods";
            $.ajax({
                url:url,
                type:"GET",
                data:{page:page,name:name},
                dataType:"json",
                success:function(res){
                    //console.log(res);
                    //清空之前的数据
                    $('#list').empty();
                    $.each(res.data.data,function(i,v){
                        var tr = $("<tr></tr>");
                        tr.append("<td>"+v.g_id+"</td>");
                        tr.append("<td>"+v.goods_name+"</td>");
                        tr.append("<td><img width='50' id='img' src='/"+v.goods_file+"'></td>");
                        tr.append("<td>"+v.goods_price+"</td>");
                        tr.append("<td><a href='javascript:;' g_id='"+v.g_id+"' class='del'>删除</a>|" +
                            "|<a href='javascript:;' g_id='"+v.g_id+"' class='save'>修改</a></td>");
                        $('#list').append(tr);
                    })
                    var page="";
                    for(var i=1;i<=res.data.last_page;i++){
                        if(i==res.data.current_page){
                            page +="<a style='color:red'class='pagination pagination-large pagination-centered' pages='"+i+"'>"+i+"</a>";
                        }else{
                            page +="<a  class='pagination pagination-large pagination-centered' pages='"+i+"'>"+i+"</a>";
                        }
                    }
                    $('.page').html(page);

                }
            })
        })
        //删除
        $(document).on('click','.del',function(){
            var url="http://www.myshop.com/api/goods";
            var g_id=$(this).attr('g_id');
            var tr=$(this).parent().parent();
            //alert(g_id);
            // alert(1);xx
            $.ajax({
                url:url+'/'+g_id,
                data:{g_id:g_id},
                type:'DELETE',
                dataType:'json',
                success:function(res){
                    if(res.code==200){
                        alert(res.msg);
                        tr.remove();

                    }
                }

            })
        })
        //修改
        $(document).on('click','.save',function(){
            var g_id=$(this).attr('g_id');
            location.href="http://www.myshop.com/goods/update?id="+g_id;
        })
    </script>
@endsection