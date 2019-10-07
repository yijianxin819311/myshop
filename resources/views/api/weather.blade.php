@extends('index.layout.commons')
@section('title','天气查询')
@section('body')
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>天气查询</title>
</head>
<body>
<form action="" method="post">
    城市名：<input type="text" name="citynm" id="citynm"><br>
    <input type="button" value="查询" id="sub">
</form>
</body>
</html>
@endsection
@section('script')
    <script>
        $('#sub').on('click',function(){
            var citynm=$('#citynm').val();
            $.ajax({
                url:'http://www.myshop.com/api/weather/weather_do',
                dataType : "json",
                data:{citynm:citynm},
                //jsonp:'jsoncallback',
                success:function(res) {
                }
            })
        })


    </script>
@endsection