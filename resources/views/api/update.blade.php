<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="{{'/mstore/js/jquery.min.js'}}"></script>
    <meta name="viewport
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> 商品修改</title>
</head>
<body>
<center>

    商品名称：<input type="text" name="goods_name" ><br>
    商品图片：<input type="file" name="goods_file" id="goods_file">
              <img src="" alt="" id="img" width="50"><br>
    商品价格：<input type="text" name="goods_price"><br>
    <button input type="button" class="sub">修改</button>

</center>
</body>
</html>
<script>
        // alert(11);
        //获取url地址上的参数也就是id
        var g_id=GetQueryString('id');
        //alert(g_id);
        var url='http://www.myshop.com/api/goods';
        function GetQueryString(goods_name) {
            var reg = new RegExp("(^|&)" + goods_name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
        $.ajax({
            url:url+'/'+g_id,
            data:{g_id:g_id},
            type:'GET',
            dataType:'json',
            success:function(res){
                //修改val赋值默认值接收过来
                $("[name='goods_name']").val(res.data.goods_name);
                $("[name='goods_price']").val(res.data.goods_price);
                $('#img').attr('src','/'+res.data.goods_file);

            }
        });
        $(".sub").on('click',function(){
            //alert(11);
            var g_id=GetQueryString('id');
            var goods_name=$("[name='goods_name']").val();
            var goods_price=$("[name='goods_price']").val();
            var fd=new FormData();
            var goods_file=$('#goods_file')[0].files[0];
            fd.append('g_id',g_id);
            fd.append('goods_name',goods_name);
            fd.append('goods_price',goods_price);
            fd.append('goods_file',goods_file);
            fd.append('_method','PUT');
            var url='http://www.myshop.com/api/goods';
            $.ajax({
                url:url+'/'+g_id,
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
