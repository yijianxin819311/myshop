@extends('layout.common')
@section('title','商品修改')
@section('body')
 <!-- login -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
                <h3>商品修改</h3>
            </div>
            <div class="login">
                <div class="row">
                    <form class="col s12" method="post" action="{{url('admin/goods_update_do')}}" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$goods_info->id}}">
                        @csrf
                        <div class="input-field">
                        商品名称：
                            <input type="text" class="validate"   name="goods_name" value="{{$goods_info->goods_name}}">
                        </div>
                        <div class="input-field">
                        商品价格：

                            <input type="text" class="validate" name="goods_price" value="{{$goods_info->goods_price}}">
                        </div>
                        <div class="input-field">
                        商品图片：
                            <input type="file" class="validate"   name="goods_pic" value="">
                            <img src="{{$goods_info->goods_pic}}" width="50"  alt="">
                        </div>
                        
                       <button>上传</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end login -->
@endsection
@section('script')
<script>
    $(function(){

    });
</script>
@endsection
