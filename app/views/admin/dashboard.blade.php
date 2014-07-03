@extends( 'templates.default' )

@section('content')

<h1>{{Site::pluck('name')}} Admin Dashboard</h1>
<div class="row">
	<div class="col-sm-2">
		<ul class="nav nav-pills nav-stacked">
			<li><a href="{{url('admin/content')}}" id="content">Content</a></li>
			<hr />
			<li><a href="{{url('admin/settings')}}" id="main">Main</a></li>
			<li><a href="{{url('admin/users')}}" id="users">Users</a></li>
		</ul>
	</div>
	<div class="col-sm-10">

		@yield('panel')

	</div>
</div>
@stop

@section('script')

@stop