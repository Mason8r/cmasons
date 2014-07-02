@extends( 'templates.default' )

@section( 'title' , 'Access Denied' )

@section('content')
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<h1 class="text-danger">Access Denied</h1>
			<p class="text-danger">You do not have access to the <strong>{{ $errors->first() }}</strong> area.</p>
		</div>
	</div>
@stop