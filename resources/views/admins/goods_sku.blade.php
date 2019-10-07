@extends('admins.common')

@section('title', '货品添加')
@section('content')
<h3>货品添加</h3>
<form action="{{url('admins/goods_sku_do')}}" method="post">
<table width="100%" id="table_list" class='table table-bordered'>
    <input type="hidden" name="goods_id" value="{{$goods_id}}">
    <tbody>
    <tr>
      <th colspan="20" scope="col">商品名称：{{$goodsdata['goods_name']}}&nbsp;&nbsp;&nbsp;&nbsp;货号：ECS000075</th>
    </tr>

    <tr>
      <!-- start for specifications -->
        @foreach($shuxing as $v)
        <td scope="col"><div align="center"><strong>{{$v[0]['attr_name']}}</strong></div></td>
        @endforeach

            <!-- end for specifications -->
      <td class="label_2">货号</td>
      <td class="label_2">库存</td>
      <td class="label_2">&nbsp;</td>
    </tr>
    
    <tr id="attr_row">
	    <!-- start for specifications_value -->
        @foreach($shuxing as $v)
		<td align="center" style="background-color: rgb(255, 255, 255);">
			<select name="goods_attr[]">
				<option value="" selected="">请选择...</option>
                @foreach($v as $vv)
				<option value="{{$vv['attr_value_list']}}">{{$vv['attr_value_list']}}</option>
                @endforeach
			</select>
		</td>
        @endforeach
{{--		<td align="center" style="background-color: rgb(255, 255, 255);">--}}
{{--			<select name="attr[214][]">--}}
{{--				<option value="" selected="">请选择...</option>--}}
{{--			    <option value="土豪金">土豪金</option>--}}
{{--			    <option value="太空灰">太空灰</option>--}}
{{--			</select>--}}
{{--		</td>--}}
	    <!-- end for specifications_value -->
		<td class="label_2" style="background-color: rgb(255, 255, 255);"><input type="text" name="goods_sn[]" value="" size="20"></td>
		<td class="label_2" style="background-color: rgb(255, 255, 255);"><input type="text" name="goods_num[]" value="1" size="10"></td>
		<td style="background-color: rgb(255, 255, 255);"><input type="button" class="button" id="btn" value=" + " ></td>
    </tr>

    <tr>
      <td align="center" colspan="5" style="background-color: rgb(255, 255, 255);">
        <input type="submit" class="button" value=" 添加 ">
      </td>
    </tr>
  </tbody>
</table>
</form>
@endsection
@section('script')

    <script>
        //alert(1);
        $(document).on('click','#btn',function(){
            // alert(1);

            var val=$(this).val();
            // alert(val);return;
            if(val==" + "){
                //alert(1);return;
                //通过a标签获得tr
                //复制之前先把加号换成减号
               $(this).val(" - ");
                var tr_clone=$(this).parent().parent().clone();
                $(this).parent().parent().after(tr_clone);
                $(this).val(" + ");
            }else{
                // alert(2);return;
                //如果是减号点击减号清空整行
                $(this).parent().parent().remove();
            }
        });

    </script>
@endsection