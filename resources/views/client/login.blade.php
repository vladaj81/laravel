@extends('layouts.login')

@section('title')
    Login
@endsection

@section('content')
    
	<div class="limiter">
		<div class="container-login100" style="background-image: url('frontend/images/bg-01.jpg');">
			<div class="wrap-login100">
				@if (Session::has('error'))
				<div class="alert alert-danger">
					{{Session::get('error')}}
				</div>
				@endif
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<ul>{{$error}}</ul>
							@endforeach
						</ul>
					</div>
				@endif
				<form action="{{url('/accessaccount')}}" method="POST" class="login100-form validate-form">
				{{csrf_field()}}
					<a href="{{URL::to('/')}}">
						<span class="login100-form-logo">
							<i class="zmdi zmdi-landscape"></i>
						</span>
					</a>
					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter email">
						<input class="input100" type="text" name="email" placeholder="Email">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>
					<!--
					<div class="contact100-form-checkbox">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
						<label class="label-checkbox100" for="ckb1">
							Remember me
						</label>
					</div>
					-->
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>

					<div class="text-center p-t-90">
						<a class="txt1" href="/signup">
							Dont have an account? Sign up
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

    <div id="dropDownSelect1"></div>
    
@endsection
	