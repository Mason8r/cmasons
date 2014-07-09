@extends( 'templates.default' )

@section( 'title' , $page->title )

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="jumbotron" @if($page->image) 'style="background: url('{{$page->image}}') no-repeat;"' @endif >
				<h1 style="-webkit-text-stroke-width: 2px; -webkit-text-stroke-color: #333; color:#fff;" >{{$page->title}}</h1>
			</div>
			{{$page->content}}
		</div>
	</div>

	<div class="row">
		<div class="col-sm-11 col-sm-offset-1">
		   	@foreach($posts as $post)
		   		<h2><a href="{{url('post/'.$post->slug)}}">{{$post->title}}</a></h2>
		   		<p>{{Sentry::findUserById($post->user_id)->first_name}} | {{ date('d/m/y' , strtotime( $post->created_at ) )}}</p>
		   		<img class="img-responsive" src="{{$post->image}}" />
		   		<p>{{$post->content}} <a href="{{url('post/'.$post->slug)}}">[more...]</a></p>
		   		<hr />
		   	@endforeach
		</div>
	</div>

@stop