<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>app测试</title>
    <script src="/js/jquery.js" type="text/javascript"></script>
</head>
<style>
    #aa{
        float:left;
    }
    #bb{
        float:left;
        margin-left: 30px;
    }
</style>
<body>
<center>
<div id="aa">

<form enctype="multipart/form-data" id="addForm" >
    请求地址：<input type="text" id="url"><p>
        请求方式：<input type="radio" name="type" value="get">GET &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="type" value="post">POST</p>
    <div id='div'>
        <p id="p">
            参数名称：<input type="text" class="name"> &nbsp;&nbsp;&nbsp;&nbsp;
            参数值:<input type="text" name="" class='val'>
        </p></div>

</form>
<p>
    <input type="button" value="添加一个参数" id="btn">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" id="sub" value="提交">
</p>
</div>
<div id='bb'>
   返回结果 <br>
    <div id="cc">
    <textarea name="" id="txt" cols="30" rows="10">
    </textarea>
    </div>
</div>
</center>
<script type="text/javascript">
    //动态加参数
    $('#btn').on('click', function() {
        var input = $('#p').clone();
        $('#div').after(input);
    });
    //获取name
    $(document).on('blur','.name', function() {
        var name = $(this).val();
        $(this).next().prop('name',name);
    });
    $(document).on('click','#sub', function () {
        var data = new FormData($( "#addForm" )[0]);
        // console.log(enCode(data));return;
        var type = $(":checked").val();
        var url = $("#url").val();
        // console.log(url);return;
        $.ajax({
            type:type,
            url: url,
            cache: false,    //上传文件不需缓存
            processData: false, //需设置为false。因为data值是FormData对象，不需要对数据做处理
            contentType: false, //需设置为false。因为是FormData对象，且已经声明了属性enctype="multipart/form-data"
            data:data,
            dataType:'json',
            success:function(res){
                //alert(res);
               // document.getElementById("txt").value =(res.result.msg);
                 $('#txt').html(res.result.msg);
            },
        });
    });

</script>
</body>
</html>


