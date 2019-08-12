<!DOCTYPE html>
<html>
<head>
    <title>车库管理系统-计费</title>
</head>
<body>
    <center>
        <a href="{{url('cheku/add')}}">车库管理系统</a> | <a href=""></a> | <a href=""></a>
        <span>车牌号：{{$cart_num}}</span>
        <span>停车时长：{{$time_info}}</span>
        <span>累计：{{$pay_amount}}元</span>

        <button type="button" id="pay">缴费</button>
    </center>
    <script src="{{asset('mstore/js/jquery.min.js')}}"></script>
    <script>
        $(function(){
           
            $("#pay").click(function(){
                window.location.href="{{url('cheku/del_price')}}?id={{$cart_id}}&price={{$pay_amount}}";
            });
        });
    </script>
</body>
</html>