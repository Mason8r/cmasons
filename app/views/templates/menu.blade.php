<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
	<ul class="nav navbar-nav">
	    @foreach(Menu::find(Site::pluck('main_menu'))->pages()->get() as $item)
			<li><a href="{{url('page/'.$item->slug)}}">{{$item->title}}</a></li>
		@endforeach
	</ul>
  </div>
</nav>