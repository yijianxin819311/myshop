@extends('admins.common')

@section('title', '商品展示')
@section('content')
    <center>
        <a href="{{url('admins/goods')}}">添加</a>
        <form action="{{url('admins/goods_list')}}" method="get">
            关键字：<input type="text" name="search" value="{{$search}}">
            所有分类：
            <select name="cate_id" id="" value="{{$cate_id}}">
                <option value="">所有商品</option>
                @foreach($catedata as $v)
                    <option value="{{$v['cate_id']}}">{{$v['cate_name']}}</option>
                @endforeach
            </select>
            <input type="submit" value="搜索">
        </form>
<table border="1" width="500" class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>商品编号</th>
        <th>商品名字</th>
        <th>商品价格</th>
        <th>是否上架</th>
        <th>分类名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="show">
    @foreach($data as $v)
        <tr goods_id="{{$v->goods_id}}">
            <td>{{$v->goods_id}}</td>
            <td field="goods_name">
                <span>{{$v->goods_name}}</span>
                <input type="text" class="changevalue" value="{{$v->goods_name}}" style="display:none;">
            </td>
            <td field="goods_price">
                <span>{{$v->goods_price}}</span>
                <input type="text" class="changevalue" value="{{$v->goods_price}}" style="display:none;">
            </td>
            @if($v->is_up==1)
                <td>√</td>
            @elseif($v->is_up==2)
                <td>×</td>
            @endif
            <td>{{$v->cate_name}}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
        {{$data->appends(['search'=>$search,'cate_id'=>$cate_id])->links()}}
    </center>
@endsection
@section('script')
    <script>
        $(document).on('click','span',function(){
            var _this=$(this);
            _this.hide();
            _this.next('input').show();
        })
        $(function() {
            $(document).on('blur', '.changevalue', function () {
                //重新定义本对象
                var _this = $(this);
                // _this.hide();
                // _this.next('input').show();
                var _value = _this.val();//获取本对象的值
               // alert(_value);return;
                //获取要修改的字段
                var _field = _this.parent('td').attr('field');
                //alert(_field);return;
                //获取要修改的id
                var goods_id = _this.parents('tr').attr('goods_id');
                $.ajax({
                    url: "{{url('admins/change')}}",
                    data: {value: _value, field: _field, goods_id: goods_id},
                    dataType: "json",
                    success: function (res) {
                        if (res.code== 200) {
                            _this.hide();
                            _this.prev('span').text(_value).show();
                        }
                    }
                });
            })
        });
    </script>
@endsection
