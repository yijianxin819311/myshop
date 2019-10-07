@extends('admins.common')

@section('title', '后台')
@section('content')
    <br><br>
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>分类</title>
</head>
<body>
<center>
    <form action="{{url('admins/add_do')}}" method="post"  class="form-horizontal">
        分类：<input type="text" name="cate_name" id="name"><span id="sp"></span><br>
        <select name="pid"id="pid" >
            <option value="0">--请选择--</option>
             @foreach($data as $v)
            <option value="{{$v['cate_id']}}"> {{str_repeat('-',$v['level'])}}{{$v['cate_name']}} </option>
            @endforeach
        </select><br>
        <input type="submit" id="sub" value="添加">
    </form>
</center>
</body>
</html>
@endsection
@section('script')
    <script>
    $(function(){
        var fas=false;
        $('[name="cate_name"]').blur(function(){
            var cate_name=$(this).val();
            var _this=$(this);
            _this.next('span').empty();
            if(cate_name==''){
                _this.next('span').append('分类名称不可为空'); return;
            }
            $.get("{{url('admins/add_dodo')}}",{cate_name:cate_name},function(res){
                if(res.code==202){
                    _this.next('span').append(res.msg);
                }else{
                    fas=true;
                }
            },'json');
            return false;
        });
        $('#myform').submit(function () {
            if(fas==true){
                var data = $('#myform').serialize();
                $.post("{{url('admins/add_do')}}", data, function (res) {
                    alert(res.msg);
                    if(res.code==200){
                        location.href='lists';
                    }
                }, 'json');
            }
            return false;
        });
    });
    </script>
@endsection
