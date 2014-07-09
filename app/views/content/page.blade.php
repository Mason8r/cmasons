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
@stop