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

        属性名称：<input type="text" name="attr_name"><br>
        所属类型：<select name="t_id" id="">
            @foreach($data as $v)
            <option value="{{$v->t_id}}">{{$v->type}}</option>
           @endforeach
        </select><br>
        属性的划分：<input type="radio" name="attr_type" value="1" checked >可选参数
        <input type="radio" name="attr_type" value="2"  >可不选参数</br>
        <input type="submit" id="sub" value="添加">
    </center>
    </body>
    </html>
@endsection
@section('script')
    <script>
        $("#sub").on('click',function(){
            // alert(11);
            var attr_name=$("[name='attr_name']").val();
            var t_id=$("[name='t_id']").val();
            var attr_type=$("[name='attr_type']:checked").val();
           // alert(attr_type);
            $.ajax({
                url:'attr_do',
                data:{attr_name:attr_name,t_id:t_id,attr_type:attr_type},
                type:'POST',
                dataType:'json',
                success:function(res){
                    alert(res.msg);
                    if(res.code==200){
                        location.href = 'attr_list?t_id='+t_id;
                    }
                }
            });
        })
    </script>
@endsection
