@extends('index.layout.commons')
@section('title','会员添加')
@section('body')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="{{'/mstore/js/jquery.min.js'}}"></script>
    <meta name="viewport
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>接口基础 商品添加</title>
</head>
<body>

        <h3>商品添加</h3>
        商品名称：<input type="text" name="goods_name" ><br>
        商品图片：<input type="file" name="goods_file" id="goods_file"><br>
       商品价格：<input type="text" name="goods_price"><br>
        <button input type="button" class="sub">添加</button>


</body>
</html>
<script>
    $(".sub").on('click',function(){
        //alert(11);
        var goods_name=$("[name='goods_name']").val();
        // alert(goods_name);
        var goods_price=$("[name='goods_price']").val();
        var fd=new FormData();
        var goods_file=$('#goods_file')[0].files[0];
        fd.append('goods_name',goods_name);
        fd.append('goods_price',goods_price);
        fd.append('goods_file',goods_file);
       // alert(goods_file);
        var url="http://www.myshop.com/api/goods";
        $.ajax({
            url:url,
            data:fd,
            type:'POST',
            dataType:'json',
            processData:false,
           contentType:false,
            success:function(res){
                alert(res.msg);
                if(res.code==200){
                    location.href = 'http://www.myshop.com/goods/list';
                }
            }
        });
    })
</script>
@endsection
