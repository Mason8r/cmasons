@extends( 'templates.default' )

@section( 'title' , $page->title )

@section('content')
    {{$page->content}}
	   	@foreach($posts as $post)
	   		<hr />
	   		<h2><a href="{{url('post/'.$post->slug)}}">{{$post->title}}</a></h2>
	   		<p>{{Sentry::findUserById($post->user_id)->first_name}} | {{ date('d/m/y' , strtotime( $post->created_at ) )}}</p>
	   		<p>{{$post->content}}</p>
	   	@endforeach
@stop