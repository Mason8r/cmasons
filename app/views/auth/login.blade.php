@extends( 'templates.default' )

@section( 'title' , 'Login' )

@section('content')
	<div class="row">
		<div class="col-lg-4 col-lg-offset-4">
			<h1>Login</h1>
			{{ Form::open(array('url' => 'login' , 'role' => 'form')) }}
			<div class="form-group">
				{{ Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Email') ) }}
				<p class="text-danger">{{ $errors->first('email') }}</p>
			</div>
			<div class="form-group">
				{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password') ) }}
				<p class="text-danger">{{ $errors->first('password') }}</p>
			</div>
			<div class="form-group text-center">
				{{ Form::submit('Login', array( 'class' => 'btn btn-primary btn-lg btn-block' )) }}
			</div>
			{{ Form::close() }}
		</div>
	</div>
@stop