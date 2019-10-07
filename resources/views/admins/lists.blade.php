@extends('admins.common')

@section('title', '后台')
@section('content')
    <center>
<table border="1" width="500" class="table table-bordered table-hover table-striped">
    <a href="{{url('admins/cate')}}">添加</a>
    <thead>
    <tr>
        <th>分类id</th>
        <th>分类名字</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="show">
    @foreach($data as $v)
        <tr  cate_id="{{$v['cate_id']}}" pid="{{$v['pid']}}" style="display:none;">
            <td>
                {{str_repeat("-",$v['level']*2)}}
                <a href="javascript:;" class="show">+</a>
            </td>
            <td>{{str_repeat('--',$v['level']*2)}}{{$v['cate_name']}}</td>

            <td>
                <a href="" class="btn btn-danger">删除</a>&nbsp&nbsp
                <a href="" class="btn btn-warning">修改</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    </center>
@endsection
@section('script')
    <script>
        $("tr[pid=0]").show();
        // 点击+
        $(document).on('click','.show',function(){
            // alert(1);
            var a=$(this).text();
            // alert(a);
            var cate_id=$(this).parents('tr').attr('cate_id');
            // console.log(cate_id);
            //alert(cate_id);
            if(a=='+'){
                if($("tr[pid='"+cate_id+"']").length>0){
                    $("tr[pid='"+cate_id+"']").show();
                    $(this).text('-');
                }
            }else{
                $("tr[pid='"+cate_id+"']").hide();
                $(this).text('+');
            }
            return false;
        });
    </script>
@endsection
