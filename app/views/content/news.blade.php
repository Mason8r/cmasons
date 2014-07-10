@extends( 'templates.default' )

@section( 'title' , $page->title )

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="jumbotron" @if($page->image) 'style="background: url('{{$page->image}}') no-repeat;"' @endif >
				<h1 style="-webkit-text-stroke-width: 2px; -webkit-text-stroke-color: #333; color:#fff;" >{{$page->title}}</h1>
			</div>
			{{--$page->content--}}
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
   			<h1><a href="{{url('post/'.$latest->slug)}}">{{$latest->title}}</a></h1>
   			<img src="{{$latest->image}}" class="img-responsive" />
   			<p>{{Sentry::findUserById($latest->user_id)->first_name}} | {{ date('d/m/y' , strtotime( $latest->created_at ) )}}</p>
   			<p>{{$latest->content}} <a href="{{url('post/'.$latest->slug)}}">[more...]</a></p>
	   		<hr />
		</div>
		<div class="col-sm-4">
			<h3>Recent Posts:</h3>
			<hr />
		   	@foreach($posts as $post)
		   		<div class="post">
			   		<img src="{{$post->image}}" height="80px" style="float:right;"/>
		   			<h4><a href="{{url('post/'.$post->slug)}}">{{$post->title}}</a></h4>
		   			<p>{{Sentry::findUserById($post->user_id)->first_name}} | {{ date('d/m/y' , strtotime( $post->created_at ) )}}</p>
		   			<p><a href="{{url('post/'.$post->slug)}}">[Read]</a></p>
		   		</div>
		   		<div style="clear:both;"></div>
		   	@endforeach
		</div>
	</div>

@stop