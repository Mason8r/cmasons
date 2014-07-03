<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
  	<a class="navbar-brand" href="#">{{Site::pluck('name')}}</a>
	<ul class="nav navbar-nav">
		@foreach($mainMenuItems as $item)
			<li><a href="{{url('page/'.$item->slug)}}">{{$item->pivot->name}}</a></li>
		@endforeach
	</ul>

	<ul class="nav navbar-nav navbar-right">

		@if(!Sentry::check())
	    <li><a href="{{url('login')}}">Login</a></li>
	    @else
	    <li class="dropdown">
	    	<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{Sentry::getUser()->first_name}}</a>
		    <ul class="dropdown-menu" role="menu">
			    <!--<li><a href="{{url('mail')}}">Mail</a></li>
			    <li><a href="{{url('tickets')}}">Tickets</a></li>-->
			    <li><a href="{{url('account')}}">My Account</a></li>
			    @if(Sentry::getUser()->hasAccess('admin'))
			    	<li><a href="{{url('admin')}}">Admin</a></li>
			    @endif
			    <li class="divider"></li>
			    <li><a href="{{url('logout')}}">Logout</a></li>
		    </ul>


	    </li>
	    @endif

    </ul>

  </div>
</nav>