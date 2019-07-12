@extends('layout.common')
@section('title','登录')
@section('body')
 <!-- login -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
                <h3>LOGIN</h3>
            </div>
            <div class="login">
                <div class="row">
                    <form class="col s12" method="post" action="{{url('index/login_do')}}">
                        @csrf
                        <div class="input-field">
                            <input type="text" class="validate" placeholder="USERNAME" required name="names">
                        </div>
                        <div class="input-field">
                            <input type="password" class="validate" placeholder="PASSWORD" required name="password">
                        </div>
                        <a href=""><h6>Forgot Password ?</h6></a>
                        <!-- <a href="" class="btn button-default">LOGIN</a> -->
                       <button>login</button>
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
