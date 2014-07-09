@extends( 'admin.dashboard' )

@section( 'title' , 'Edit Content' )

@section('panel')
<h2>{{(isset($content->title) ? 'Edit: '.$content->title : 'More Content!')}}</h2>

	<div class="row">
		<div class="col-sm-12">
			<ul class="list-unstyled">
			@foreach ($errors->all('<li>:message</li>') as $message)
				<p class="text-danger">{{ $message }}</p>
			@endforeach
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8">
		{{---OK So lets use the same page to edit or create a page. Add it to the action URL....--}}
		{{ Form::open(array('url' => 'admin/content/'.(isset($content) ? 'edit/'.$content->id : 'new') , 'role' => 'form' , 'files' => true )) }}
		<div class="form-group">
		    {{ Form::label('title', 'Page Title *') }}
		    {{ Form::text('title', (isset($content->title) ? $content->title : ''), array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('title') }}</p>
		</div>
		<div class="form-group">
		    {{ Form::label('slug', 'Page URL Slug *') }}
		    {{ Form::text('slug', (isset($content->slug) ? $content->slug : ''), array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('slug') }}</p>
		</div>
		<div class="form-group new-menu" id="new-menu">
		    {{ Form::label('menu_name', 'Menu Title') }}
		    {{ Form::text('menu_name', (isset($menu->pivot->name) ? $menu->pivot->name : ''), array('class' => 'form-control') ) }}
		    <p class="text-danger">{{ $errors->first('menu_name') }}</p>
		</div>
		<div class="form-group">
		    {{ Form::label('content', 'Page Content') }}
		    {{ Form::textarea('content', (isset($content->content) ? $content->content : ''), array('class' => 'form-control' , 'id' => 'editor' ) ) }}
		    <p class="text-danger">{{ $errors->first('content') }}</p>
		</div>
		<div class="form-group">
			{{ Form::submit((isset($content) ? 'Edit' : 'Add'), array( 'class' => 'btn btn-default' )) }}
		</div>
			{{ Form::hidden('user_id', (isset($content->user_id) ? $content->user_id : Sentry::getUser()->id)) }}
			{{ Form::hidden('edited_by', Sentry::getUser()->id) }}
			</div>
	<div class="col-sm-4">
	<div class="form-group">
		    {{ Form::label('page', 'Type') }}:<br />
		    {{--Double shorthand ternary shot!!!! Swing and a miss for clean code!--}}
		    {{ Form::radio('page', '0' , (isset($content)&&$content->page==1 ? 0 : 1 ) ); }} Post
		    {{ Form::radio('page', '1' , (isset($content)&&$content->page==0 ? 0 : 1 ) ); }} Page
		</div>	
		<div class="form-group">
		    {{ Form::label('published', 'Published') }}:<br />
		    {{ Form::radio('published', '1' , true ); }} Yes
		    {{ Form::radio('published', '0'); }} No 
		</div>	
		<div class="form-group new-menu" style="display: none;">
		    {{ Form::label('category_id', 'Category') }}:<br />
		    {{ Form::select('category_id', $categories); }}
		</div>		
		<div class="form-group">
		    {{ Form::label('area_id', 'Access Area') }}:<br />
		    {{ Form::select('area_id', $areas); }}
		</div>	
		<div class="form-group new-menu" >
		    {{ Form::label('menu_id', 'Menu') }}:<br />
		    {{ Form::select('menu_id', $menus); }}
		</div>
		<div class="form-group">
		    {{ Form::label('image', 'Featured Image') }}:<br />
		    {{ Form::file('image');; }}

		    <br />
		    <img class="img-responsive img-thumbnail" src="{{isset($content->image) ? $content->image : ''}}" />

		    <p class="text-danger">{{ $errors->first('image') }}</p>
		</div>		
		{{Form::close()}}
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
<script>

$( document ).ready(function() {

    
    CKEDITOR.replace( 'editor' );

    //if it's edit mode, show everything: 
    if('{{$content->title or 0}}'!=0) {
    	$('.new-menu').css('display','block');
    } else {
	    $('input[name=page]:radio').change(function(){
		   $('.new-menu').slideToggle();
		});
    }

	//create the slug from the page title.
	$('input[name=title]:text').blur(function(){
		$('input[name=slug]:text').val(encodeURIComponent($('input[name=title]:text').val()).replace(/%20/g,'+'))
	});
});
</script>
@endsection