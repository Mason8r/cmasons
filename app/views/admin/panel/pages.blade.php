<div class="row">
	<div class="col-sm-6">
		<h2>Pages</h2>
	</div>
	<div class="col-sm-6">
		<p class="text-right"><a href="" class="btn btn-primary">Add New</a></p>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
		  <table class="table table-striped table-bordered">
		  	<tr>
		  		<th>ID</th>
		  		<th>Name</th>
		  		<th>Slug</th>
		  		<th>Creator</th>
		  		<th>Last Edited</th>
		  		<th>Area</th>
		  		<th>Live</th>
		  		<th colspan='3' >Actions</th>
		  	</tr>
		    @foreach($pages as $page)
		    	<tr id="{{$page->id}}" >
		    		<td>{{$page->id}}</td>
		    		<td>{{$page->title}}</td>
		    		<td>{{$page->slug}}</td>
		    		<td>{{Sentry::findUserById($page->user_id)->first_name}}</td>
		    		<td>{{date('d/m/y H:m' , strtotime($page->updated_at))}}</td>
		    		<td>{{Area::find($page->area_id)->pluck('name')}}</td>
		    		<td>
		    			@if($page->published)
		    				Yes
		    			@else
		    				No
		    			@endif
		    		</td>
		    		<td><a href="#" class="page-nav" id="edit" >Edit</a></td>
		    		<td><a href="#" class="page-nav" id="delete" >Delete</a></td>
		    		<td><a href="#" class="page-nav" id="publish" >
		    			@if($page->published)
		    				Unpublish
		    			@else
		    				Publish
		    			@endif
		    		</a></td>
		    	</tr>
		    @endforeach
		  </table>
		</div>
	</div>
</div>