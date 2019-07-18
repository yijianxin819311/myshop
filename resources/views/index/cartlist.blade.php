@extends('layout.common')
@section('title', '购物车列表')

@section('body')


<!-- cart -->
<div class="cart section">
    <div class="container">
        <div class="pages-head">
            <h3>CART</h3>
        </div>
        <div class="content">
          @foreach($cart as $v)
            <div class="cart-1">
                <div class="row">
                    <div class="col s5">
                        <h5>Image</h5>
                    </div>
                    <div class="col s7">
                        <img src="{{$v->goods_pic}}" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col s5">
                        <h5>Name</h5>
                    </div>
                    <div class="col s7">
                        <h5><a href="">{{$v->goods_name}}</a></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col s5">
                        <h5>Quantity</h5>
                    </div>
                    <div class="col s7">
                        <input value="1" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="col s5">
                        <h5>Price</h5>
                    </div>
                    <div class="col s7">
                        <h5>￥{{$v->goods_price}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col s5">
                        <h5>Action</h5>
                    </div>
                    <div class="col s7">
                        <h5><i class="fa fa-trash"></i></h5>
                    </div>
                </div>
            </div>
    @endforeach
        </div>
        <div class="total">
      @foreach($cart as $v)
            <div class="row">
                <div class="col s7">
                    <h5>{{$v->goods_name}}</h5>
                </div>
                <div class="col s5">
                    <h5>￥{{$v->goods_price}}</h5>
                </div>
            </div>
   @endforeach

            <div class="row">
                <div class="col s7">
                    <h6>Total</h6>
                </div>
                <div class="col s5">
                    <h6>{{$total}}</h6>
                </div>
            </div>
        </div>
        <!-- <button class="btn button-default">去结算</button> -->
        <a href="{{url('order/add')}}" class="btn button-default">去结算</a>
    </div>
</div>
<!-- end cart -->

@endsection
