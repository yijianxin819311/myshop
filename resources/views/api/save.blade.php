<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="{{'/mstore/js/jquery.min.js'}}"></script>
    <meta name="viewport
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>接口基础 会员修改</title>
</head>
<body>
<center>
    <h3>会员修改</h3>
    会员姓名：<input type="text" name="name" ><br>
    会员手机：<input type="text" name="tel"><br>
    <button input type="button" class="sub">修改</button>

</center>
</body>
</html>
<script>
        // alert(11);
        //获取url地址上的参数也就是id
        var member_id=GetQueryString('id');
        //alert(member_id);
        var url='http://www.myshop.com/api/member';
        function GetQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
        //console.log(tel);
        //console.log(name);
        $.ajax({
            url:url+'/'+member_id,
            data:{member_id:member_id},
            type:'GET',
            dataType:'json',
            success:function(res){
                //修改val赋值默认值接收过来
                $("[name='name']").val(res.data.name);
                $("[name='tel']").val(res.data.tel);
            }
        });
        $(".sub").on('click',function(){
            // alert(11);
            var member_id=GetQueryString('id');
            var name=$("[name='name']").val();
            var tel=$("[name='tel']").val();
            // console.log(tel);
            // console.log(name);
            var url='http://www.myshop.com/api/member';
            $.ajax({
                url:url+'/'+member_id,
                data:{_method:'PUT',name:name,tel:tel,member_id:member_id},
                type:'POST',
                dataType:'json',
                success:function(res){
                    alert(res.msg);
                    if(res.code==200){
                        location.href = 'http://www.myshop.com/member/show';
                    }
                }
            });
        })



</script>
