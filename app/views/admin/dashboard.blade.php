@extends( 'templates.default' )

@section( 'title' , 'Admin Dashboard' )

@section('content')
<h1>{{Site::pluck('name')}} Admin Dashboard</h1>
<div class="row">
	<div class="col-sm-2">
		<ul class="nav nav-pills nav-stacked">
			<li><a href="#" class="admin-nav" id="pages">Pages</a></li>
			<li><a href="#" class="admin-nav" id="posts">Posts</a></li>
			<hr />
			<li><a href="#" class="admin-nav" id="main">Main</a></li>
			<li><a href="#" class="admin-nav" id="users">Users</a></li>
		</ul>
	</div>
	<div class="col-sm-8">

		<div id="admin-panel" ></div>

	</div>
</div>
@stop

@section('script')
<script src="{{asset('js/admin.js')}}"></script>
@stop