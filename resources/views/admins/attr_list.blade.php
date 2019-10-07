@extends('admins.common')

@section('title', '后台')
@section('content')
    <center>
<table border="1" width="500" class="table table-bordered table-hover table-striped">
    <a href="{{url('admins/goods_attr')}}">添加</a>
    按商品类型显示：
    <select name="t_id" id="t_id">
        @foreach($re as $v)
        <option value="{{$v->t_id}}">{{$v->type}}</option>
            @endforeach
    </select>
    <thead>
    <tr>
{{--        <th><input type="checkbox" id="qx"></th>--}}
        <th><input type="checkbox" id="qx">编号</th>
        <th>属性名称</th>
        <th>所属类型</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="show">
    @foreach($data as $v)
        <tr>
            <td><input type="checkbox" name="a_id" value="{{$v->a_id}}">{{$v->a_id}}</td>
            <td>{{$v->attr_name}}</td>
            <td>{{$v->type}}</td>
            <td></td>
        </tr>
        @endforeach
        <button id="fx">反选</button>
        <button id="del">批量删除</button>
    </tbody>
</table>
    </center>
@endsection
@section('script')
    <script>
        //内容改变事件
        $('#t_id').on('change',function(){
            // alert(1);
            var t_id=$(this).val();
            //alert(t_id);
            $.ajax({
                url:'attr_lists',
                data:{t_id:t_id},
                type:'GET',
                dataType:'json',
                success:function(res){
                    $('#show').empty();
                    $.each(res,function (i,v) {
                      var tr=$("<tr></tr>");
                        tr.append("<td>"+v.a_id+"</td>");
                        tr.append("<td>"+v.attr_name+"</td>");
                        tr.append("<td>"+v.type+"</td>");
                        tr.append("<td>"+v.attr_type+"</td>" );
                        $('#show').append(tr);
                    });
                }
            });
        });
        //全选、全不选
        $('#qx').on('click',function(){
            if($(this).prop('checked')){
                $(':checkbox').prop('checked',true);
            }else{
                $(':checkbox').prop('checked',false);
            }
        });
        //反选
        $('#fx').on('click',function(){
            $(':checkbox:gt(0)').each(function(){
                if($(this).prop('checked')){
                    $(this).prop('checked',false);
                }else{
                    $(this).prop('checked',true);
                }
            })
        });
        //批量删除
        $('#del').on('click',function(){
                if (!confirm("确认删除？")) {
                    return;
                }
            var arr = [];
            $("input[type='checkbox']:checked").each(function () {
                arr.push(this.value);
            })
            $.ajax({
                url:"{{url('admins/del')}}",
                data:{a_id:arr.toString()},
                dataType:"json",
                success:function(res){
                    if(res.code==1){
                        alert(res.msg);
                        $("input[type='checkbox']:checked").each(function () {
                            $(this).parent().parent().remove();//删除每一行
                        })
                    };
                }
            });
        });
    </script>
@endsection
