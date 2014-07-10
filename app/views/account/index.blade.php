@extends( 'templates.default' )

@section( 'title' , 'Hello ' . $user->first_name )

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="jumbotron">
				<h1>Account and Profile</h1>
				<p>Account Age: {{$age}} (Birthday: {{ date( 'd/m/Y' , strtotime($user->created_at)) }}) <br />Last Login: {{ date( 'H:i, d-m-y' , strtotime($user->last_login)) }}</p>
			</div>
			<h2>Hello {{ $user->first_name }} {{ $user->last_name }}</h2>

			{{var_dump($user)}}

			{{link_to('account/edit', 'Edit My Account', $attributes = array('class' => 'btn btn-primary'), $secure = null);}}
			
		</div>
	</div>

	<div class="row">
		<div class="col-sm-11 col-sm-offset-1">
		   	<h2>Profile?</h2>
		</div>
	</div>

@stop