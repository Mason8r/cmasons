@extends( 'templates.default' )

@section( 'title' , 'Register New Account' )

@section('content')
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
		<h2>Hello!</h2>
		{{ Form::open(array('url' => 'account/register/' , 'role' => 'form' )) }}
		{{--Code, Firstname, Lastname, Email, Password --}}
		<div class="form-group {{$errors->first('code') ? 'has-error' : ''}}">
		    {{ Form::label('code', 'Your Secret Registration Code *') }}
		    {{ Form::text('code', (Input::get('code') ? Input::get('code') : Input::old('code')), array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('code') }}</p>
		</div>

		<div class="form-group {{$errors->first('first_name') ? 'has-error' : ''}}">
		    {{ Form::label('first_name', 'First Name *') }}
		    {{ Form::text('first_name', (Input::get('first_name') ? Input::get('first_name') : Input::old('first_name')), array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('first_name') }}</p>
		</div>

		<div class="form-group {{$errors->first('last_name') ? 'has-error' : ''}}">
		    {{ Form::label('last_name', 'Last Name *') }}
		    {{ Form::text('last_name', (Input::get('last_name') ? Input::get('last_name') : Input::old('last_name')), array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('last_name') }}</p>
		</div>

		<div class="form-group {{$errors->first('email') ? 'has-error' : ''}}">
		    {{ Form::label('email', 'E-Mail Address (use this for login) *') }}
		    {{ Form::email('email', (Input::get('email') ? Input::get('email') : Input::old('email')), array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('email') }}</p>
		</div>
		<hr />
		<div class="form-group {{$errors->first('password') ? 'has-error' : ''}}">
		    {{ Form::label('password', 'Password *') }}
		    {{ Form::password('password', array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('password') }}</p>
		</div>
		<div class="form-group {{$errors->first('password_confirmation') ? 'has-error' : ''}}">
		    {{ Form::label('password_confirmation', 'Confirm') }}
		    {{ Form::password('password_confirmation', array('class' => 'form-control') ) }}
			<p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
		</div>
		<div class="form-group">
			{{ Form::submit((isset($content) ? 'Edit' : 'Add'), array( 'class' => 'btn btn-default' )) }}
		</div>
		{{Form::close()}}
	</div>
</div>
@endsection

@section('script')

@endsection