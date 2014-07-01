@extends( 'templates.default' )

@section( 'title' , $post->title )

@section('content')
	<h2>{{$post->title}}</h2>
	<p>{{Sentry::findUserById($post->user_id)->first_name}} | {{ date('d/m/y' , strtotime( $post->created_at ) )}}</p>
	<hr />
	{{$post->content}}
@stop