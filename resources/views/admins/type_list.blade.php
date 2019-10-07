@extends('admins.common')

@section('title', '后台')
@section('content')
    <center>
<table border="1" width="500" height="200" class="table table-bordered table-hover table-striped">
    <a href="{{url('admins/goods_type')}}">添加</a>
    <thead>
    <tr>
        <th>编号</th>
        <th>类型</th>
        <th>属性数量</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="show">
    @foreach($data as $v)
        <tr>
            <td>{{$v['t_id']}}</td>
            <td>{{$v['type']}}</td>
            <td>{{$v['count']}}</td>
            <td><a href="{{url('admins/attr_list')}}?t_id={{$v['t_id']}}">属性列表</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
    </center>
@endsection
@section('script')
    <script>


    </script>
@endsection
