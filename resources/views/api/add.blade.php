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
    <title>接口基础 会员添加</title>
</head>
<body>

        <h3>会员添加</h3>
        会员姓名：<input type="text" name="name" ><br>
        会员手机：<input type="text" name="tel"><br>
        <button input type="button" class="sub">添加</button>


</body>
</html>
<script>
    $(".sub").on('click',function(){
        // alert(11);
        var name=$("[name='name']").val();
        var tel=$("[name='tel']").val();
        var url="http://www.myshop.com/api/member";
        //console.log(tel);
        //console.log(name);
        $.ajax({
            url:url,
            data:{name:name,tel:tel},
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
@endsection
@section('script')
    <script>
        $(function(){

        });
    </script>
@endsection