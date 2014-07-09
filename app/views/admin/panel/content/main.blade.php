@extends( 'admin.dashboard' )

@section( 'title' , 'Edit Content' )

@section('panel')
<div class="row">
	<div class="col-sm-6">
		<h2>Content</h2>
	</div>
	<div class="col-sm-6">
		<p class="text-right"><a href="{{url('admin/content/new')}}" id="new" name="page" class="btn btn-primary">Add New</a></p>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
		  <table class="table table-striped table-bordered table-hover">
		  	<tr>
		  		<th>ID</th>
		  		<th>Name</th>
		  		<th>Creator</th>
		  		<th>Last Edited</th>
		  		<th>Area</th>
		  		<th>Type</th>
		  		<th>Live</th>
		  		<th colspan='3' >Actions</th>
		  	</tr>
		    @foreach($content as $page)
		    	<tr {{($page->deleted_at != null ? 'class="danger"' : '')}} >
		    		<td>{{$page->id}}</td>
		    		<td>{{$page->title}} 
		    		@foreach($page->menus as $menu)
		    			({{$menu->pivot->name}}) 
		    		@endforeach
		    		</td>
		    		<td>{{Sentry::findUserById($page->user_id)->first_name}}</td>
		    		<td>{{date('d/m/y H:m' , strtotime($page->updated_at))}}</td>
		    		<td>{{Area::find($page->area_id)->pluck('name')}}</td>
		    		<td>
		    			@if($page->page)
		    				Page
		    			@else
		    				Post
		    			@endif
		    		</td>
		    		<td>
		    			@if($page->published)
		    				Yes
		    			@else
		    				No
		    			@endif
		    		</td>
		    		<td><a href="{{url('admin/content/edit/'.$page->id)}}" class="panel-nav" id="edit" >Edit</a></td>
		    		<td @if($page->deleted_at != null) colspan="3" align="center" @endif>
		    			@if($page->deleted_at == null)
		    				<a href="{{url('admin/content/delete/'.$page->id)}}"  class="panel-nav" id="delete" >Delete</a>
		    			@else
		    				<a href="{{url('admin/content/delete/'.$page->id)}}"  class="panel-nav" id="delete" >Revive!</a>
		    			@endif
		    		</td>
		    		@if($page->deleted_at == null)
		    		<td><a href="{{url('admin/content/publish/'.$page->id)}}"  class="panel-nav" id="publish" >
		    			@if($page->published)
		    				Unpublish
		    			@else
		    				Publish
		    			@endif
		    		</a></td>
		    		@endif
		    	</tr>
		    @endforeach
		  </table>
		</div>
	</div>
</div>
@endsection