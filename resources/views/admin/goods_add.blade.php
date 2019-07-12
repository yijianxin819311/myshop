@extends('layout.common')
@section('title','商品上传')
@section('body')
 <!-- login -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
                <h3>goods</h3>
            </div>
            <div class="login">
                <div class="row">
                    <form class="col s12" method="post" action="{{url('admin/goods_add_do')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-field">
                            <input type="file" class="validate"   name="goods_pic">
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
