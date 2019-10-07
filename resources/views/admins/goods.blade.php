@extends('admins.common')
@section('title', '商品添加')
@section('content')
    <div id="aaa">
    <h3>商品添加</h3>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="javascript:;" name='basic'>基本信息</a></li>
        <li role="presentation" ><a href="javascript:;" name='attr'>商品属性</a></li>
        <li role="presentation" ><a href="javascript:;" name='detail'>商品详情</a></li>
    </ul>
    <br>
    <form action='{{url('admins/goods_add_do')}}' method="POST" enctype="multipart/form-data" id='myform'>

        <div class='div_basic div_form'>
            <div class="form-group">
                <label for="exampleInputEmail1">商品名称</label>
                <input type="text" class="form-control" name='goods_name'>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">商品分类</label>
                <select class="form-control" name='cate_id'>
                    @foreach($cate as $v)
                    <option value='{{$v->cate_id}}'>{{$v->cate_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">商品货号</label>
                <input type="text" class="form-control" name='goods_huohao'>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">是否上架</label>
                <input type="radio" name="is_up" value="1"  checked>是
                <input type="radio" name="is_up" value="2"  >否</br>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">商品价钱</label>
                <input type="text" class="form-control" name='goods_price'>
            </div>

            <div class="form-group">
                <label for="exampleInputFile">商品图片</label>
                <input type="file" name='file' class="file">
            </div>
        </div>
        <div class='div_detail div_form' style='display:none'>
            <div class="form-group">
                <label for="exampleInputFile">商品详情</label>
                <script
                        id="container" name="content" type="text/plain">
                </script>
{{--                <textarea class="form-control" name="texts" rows="3"></textarea>--}}
            </div>
        </div>
        <div class='div_attr div_form' style='display:none'>
            <div class="form-group">
                <label for="exampleInputEmail1">商品类型</label>
                <select class="form-control" name='t_id' id="t_id">
                    @foreach($type as $v)
                        <option value='{{$v->t_id}}'>{{$v->type}}</option>
                    @endforeach
                </select>
            </div>
            <br>
            <table width="100%" id="attrTable" class='table table-bordered'>
{{--                <tr>--}}
{{--                    <td>前置摄像头</td>--}}
{{--                    <td>--}}
{{--                        <input type="hidden" name="attr_id_list[]" value="211">--}}
{{--                        <input name="attr_value_list[]" type="text" value="" size="20">--}}
{{--                        <input type="hidden" name="attr_price_list[]" value="0">--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td><a href="javascript:;">[+]</a>颜色</td>--}}
{{--                    <td>--}}
{{--                        <input type="hidden" name="attr_id_list[]" value="214">--}}
{{--                        <input name="attr_value_list[]" type="text" value="" size="20">--}}
{{--                        属性价格 <input type="text" name="attr_price_list[]" value="" size="5" maxlength="10">--}}
{{--                    </td>--}}
{{--                </tr>--}}
            </table>
            <!-- <div class="form-group">
                    颜色:
                    <input type="text" name='attr_value_list[]'>
            </div> -->
            <!-- <div class="form-group" style='padding-left:26px'>
                <a href="javascript:;">[+]</a>内存:
                <input type="text" name='attr_value_list[]'>
                属性价格:<input type="text" name='attr_price_list[][]'>
            </div> -->

        </div>
{{--        <input type="submit"value="添加">--}}
        <button type="submit" class="btn btn-default" id='btn'>添加</button>
    </form>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('/ueditor/ueditor.config.js')}}"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="{{asset('/ueditor/ueditor.all.js')}}"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var editor = UE.getEditor('container');
    </script>
    <script>
        //标签页 页面渲染
        $(".nav-tabs a").on("click",function(){
            $(this).parent().siblings('li').removeClass('active');
            $(this).parent().addClass('active');
            var name = $(this).attr('name');  // attr basic
            $(".div_form").hide();
            $(".div_"+name).show();  // $(".div_"+name)
        })
    //内容改变事件
        $('#t_id').on('change',function(){
            // alert(1);
            var t_id=$(this).val();
            // alert(t_id);
            $.ajax({
                url:'attr_add',
                data:{t_id:t_id},
                type:'GET',
                dataType:'json',
                success:function(res){
                    //console.log(res);return;
                   $('#attrTable').empty();
                    //res中有几个数据就循环添加几个tr
                    $.each(res,function (i,v) {
                       // alert(v);return;
                        if(v.attr_type==2){
                           // alert(1);return;
                            //普通不可选参数
                            var tr= '<tr>\
                                <td>'+v.attr_name+'</td>\
                                <td>\
                                    <input type="hidden" name="attr_id_list[]" value="'+v.a_id+'">\
                                    <input name="attr_value_list[]" type="text" value="" size="20">\
                                    <input type="hidden" name="attr_price_list[]" value="0">\
                                 </td>\
                             </tr>';
                            $('#attrTable').append(tr);
                        }else{
                           // alert(2);return;
                            //可选参数
                            var tr='<tr>\
                            <td><a href="javascript:;" class="add_row">[+]</a>'+v.attr_name+'</td>\
                            <td>\
                            <input type="hidden" name="attr_id_list[]" value="'+v.a_id+'">\
                                <input name="attr_value_list[]" type="text" value="" size="20">\
                                属性价格 <input type="text" name="attr_price_list[]" value="" size="5" maxlength="10">\
                                </td>\
                                </tr>';
                            $('#attrTable').append(tr);
                        }

                    })
                }
            });
        });
        $(document).on('click','.add_row',function(){
           //alert(1);
            // 如果点击加号
            var val=$(this).html();
            // alert(val);return;
            if(val=="[+]"){
                //通过a标签获得tr
                //复制之前先把加号换成减号
                $(this).html("[-]");
                var tr_clone=$(this).parent().parent().clone();
                $(this).parent().parent().after(tr_clone);
                $(this).html("[+]");
            }else{
                //如果是减号点击减号清空整行
                $(this).parent().parent().remove();
            }
        });

    </script>
@endsection

