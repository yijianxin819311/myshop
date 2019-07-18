@extends("layout.common")
@section("body")
	<!-- wishlist -->
	<div class="wishlist section">
		<div class="container">
			<div class="pages-head">
				<h3>确认订单</h3>
			</div>
			<div class="content">
				@foreach($order as $v)
				<div class="cart-1">
					<div class="row">
						<div class="col s5">
							<h5>图片</h5>
						</div>
						<div class="col s7">
							<img src="{{$v->goods_pic}}" alt="">
						</div>
					</div>
					<div class="row">
						<div class="col s5">
							<h5>货物名称</h5>
						</div>
						<div class="col s7">
							<h5>{{$v->goods_name}}</h5>
						</div>
					</div>
					
					<div class="row">
						<div class="col s5">
							<h5>价格</h5>
						</div>
						<div class="col s7">
							<h5>${{$v->goods_price}}</h5>
						</div>
					</div>
				</div>
				<div class="divider"></div>
				@endforeach
				<div class="total">
				<div class="row">
					<div class="col s7">
						<h6>总金额</h6>
					</div>
					<div class="col s5">
						<h6>${{$total}}</h6>
					</div>
				</div>
			</div>
			
			<a href="{{url('pay')}}" class="btn button-default">确认支付</a>
				
			</div>
		</div>
	</div>
	<!-- end wishlist -->

	<!-- loader -->
	<div id="fakeLoader"></div>
	<!-- end loader -->
	
	@endsection