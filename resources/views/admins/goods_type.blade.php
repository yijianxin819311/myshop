@extends('admins.common')

@section('title', '类型')
@section('content')
    <br><br>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>类型</title>
    </head>
    <body>
    <center>

        类型名称：<input type="text" name="type"><br>
        <input type="submit" id="sub" value="添加">
    </center>
    </body>
    </html>
@endsection
@section('script')
    <script>
        $("#sub").on('click',function(){
            // alert(11);
            var type=$("[name='type']").val();
            // alert(type);
            $.ajax({
                url:'type_do',
                data:{type:type},
                type:'POST',
                dataType:'json',
                success:function(res){
                    alert(res.msg);
                    if(res.code==200){
                        location.href = 'type_list';
                    }
                }
            });
        })
    </script>
@endsection
