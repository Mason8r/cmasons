@extends( 'templates.default' )

@section( 'title' , 'Login' )

@section('content')
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<h1>Login</h1>
			<p class="text-danger">{{ $errors->first() }}</p>
			{{ Form::open(array('url' => 'login' , 'role' => 'form')) }}
			<div class="form-group">
				{{ Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Email') ) }}
			</div>
			<div class="form-group">
				{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password') ) }}
			</div>
			<div class="form-group text-center">
				{{ Form::submit('Login', array( 'class' => 'btn btn-primary btn-lg btn-block' )) }}
			</div>
			{{ Form::close() }}
		</div>
	</div>
@stop