
@extends('layout.common')
@section('title','注册')
@section('body')
 <!-- register -->
	<div class="pages section">
		<div class="container">
			<div class="pages-head">
				<h3>REGISTER</h3>
			</div>
			@if ($errors->any())
    
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        
			@endif
			<div class="register" >
				<div class="row">
					<form class="col s12" action="{{url('index/register_do')}}" method="post">
					@csrf
						<div class="input-field">
							<input type="text" class="validate" placeholder="NAME" name="names">
						</div>
						<div class="input-field">
							<input type="email" placeholder="EMAIL" class="validate" name="email">
						</div>
						<div class="input-field">
							<input type="password" placeholder="PASSWORD" class="validate" name="password">
						</div>
						<div >
							<button class="validate">提交</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- end register -->
@endsection
@section('script')
<script>
    $(function(){

    });
</script>
@endsection
