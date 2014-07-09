@extends( 'templates.default' )

@section( 'title' , $post->title )

@section('content')
	<h2><a href="{{url('post/'.$post->slug)}}">{{$post->title}}</a></h2>
	<p>{{Sentry::findUserById($post->user_id)->first_name}} | {{ date('d/m/y' , strtotime( $post->created_at ) )}}</p>
	<img class="img-responsive" src="{{$post->image}}" />
	<hr />
	{{$post->content}}
@stop