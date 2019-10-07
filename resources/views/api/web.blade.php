@extends('index.layout.commons')
@section('title','会员添加')
@section('body')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>域名</title>
</head>
<body>
<center>
    程序端：<input type="text" name="url"><br>
    微信端：<input type="text" name="wechat_url"><br>
    <input type="button" id="sub" value="访问">
</center>
</body>
</html>
@endsection
@section('script')
    <script>
      $('#sub').on('click',function(){
            var url=$("[name='url']").val();
            var wechat_url=$("[name='wechat_url']").val();
            $.ajax({
                url:url,
                //data:{name:name,tel:tel},
                type:'POST',
                dataType:'json',
                success:function(res){

                }
            })

      })
    </script>
@endsection