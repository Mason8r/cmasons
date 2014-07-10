<?php

//View Share!
View::share( 'mainMenuItems' , Menu::find(Site::pluck('main_menu'))->pages()->where('published','=',1)->get());

/*
|--------------------------------------------------------------------------
| Page and Post Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
|
*/
Route::get('/', function()
{
	//Hack to force an error if there is no content. 
	try {

		$data['page'] = Content::find(Site::pluck('homepage_id'));

		//Hack to force an error.
		$data['page']->id;


		return View::make('content.page' , $data );

	} 
	catch (ErrorException $e) {

		//if the homepage doesn't exist, the site needs installing.
		return Redirect::to('install');

	}
});

Route::get('page/{slug}', function($slug)
{

	$data['page'] = Content::where('slug' , '=' , $slug)->first();

	//If its the news page, get the news
	if( $data['page']->id === Site::pluck('postspage_id') )
	{
		$data['posts'] = Content::allPosts()->where('published' , '=' , 1 )
		->orderBy('created_at','desc')
		->get();

		$data['latest'] = $data['posts']->shift();
		
		return View::make('content.news' , $data );		
	}

	return View::make('content.page' , $data );
});

Route::get('post/{slug}', function($slug)
{

	$data['post'] = Content::where('slug' , '=' , $slug)->first();
	return View::make('content.post' , $data );

});

/*
|--------------------------------------------------------------------------
| User Auth Stuff
|--------------------------------------------------------------------------
|
| Login/out etc so forth
| 
|
*/
Route::get('login', function()
{
	return View::make('auth.login');
});

Route::post('login', function()
{
	try
	{
	    // Login credentials
	    $credentials = array(
	        'email'    => Input::get('email'),
	        'password' => Input::get('password'),
	    );

		// Authenticate the user
		$user = Sentry::authenticate($credentials, false);
	}
	catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
	{
	    return Redirect::to( 'login' )->withErrors('Login field is required.');
	}
	catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
	{
	    return Redirect::to( 'login' )->withErrors('Password field is required.');
	}
	catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
	{
	    return Redirect::to( 'login' )->withErrors('Wrong password, try again.');
	}
	catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
	{
	    return Redirect::to( 'login' )->withErrors('User was not found.');
	}
	catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
	{
	   return Redirect::to( 'login' )->withErrors('User is not activated.');
	}

	// The following is only required if the throttling is enabled
	catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
	{
	    return Redirect::to( 'login' )->withErrors('User is suspended.');
	}
	catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
	{
	    return Redirect::to( 'login' )->withErrors('User is banned.');
	}

	return Redirect::intended('/');

});

Route::get('logout' , function()
{
	Sentry::logout();

	return Redirect::to('/');

});


/*--------------------------------------------------------------------------
| Installation Controller
|---------------------------------------------------------------------------
| 
*/
Route::controller('install', 'InstallController');

/*--------------------------------------------------------------------------
| Account Controller
|---------------------------------------------------------------------------
| 
*/
Route::controller('account', 'AccountController');

/*
|--------------------------------------------------------------------------
| Administration - Sort out those pages!
|--------------------------------------------------------------------------
|
| Check if user is admin
| Display admin page - Pages, Posts, Users, fucking allsorts.
| Don't do fancy JS shit, just load it up. HTML.
| 
|
*/
Route::group(array('prefix' => 'admin' , 'before' => 'auth|group:admin'), function()
{

    Route::get('content/{action?}/{id?}', function( $action = null , $id = null )
    {
		//Pages and Posts
		//Actions = edit, new, unpublish, delete
		switch ($action) {
			case 'edit':
			case 'new' :
				
				$data = retrieveContentArray();

				if(isset($id)) {
					$data['content'] = Content::find($id);
					$data['menu'] = Content::find($id)->menus()->first();
				}

				return View::make('admin.panel.content.new' , $data );

				break;

			case 'publish':
				$content = Content::find($id);
				$content->published = ($content->published == 0 ? 1 : 0);
				$content->save();
				return Redirect::to('admin/content')->withMessage('Changed.');
				break;	

			case 'delete':
				$content = Content::withTrashed()->where('id','=',$id)->first();

				if($content->deleted_at) {
					$content->restore();
				} else {
					$content->delete();
				}
		
				return Redirect::to('admin/content')->withMessage('Deleted.');
				break;	

			default:
				$data['content'] = Content::withTrashed()
					->orderBy('deleted_at','asc')
					->orderBy('page','desc')
					->orderBy('updated_at','desc')
					->with('menus')
					->get();

				return View::make('admin.panel.content.main' , $data );
				break;
		}
 	});

	//is this worth it?
	Route::post( 'content/{action}/{id?}' , function( $action , $id = null ) {

		Input::flash();

		$data = Input::all();

		$rules = array(
			'title' 		=> array('required', (is_null($id) ? 'unique:content,title' : '')),
			'slug' 			=> array('required', (is_null($id) ? 'unique:content,slug' : '')),
			'content'		=> array('required'),
			'menu_name'		=> array((is_null($id) ? 'unique:content_menu,name' : '')),
			'image'			=> array('mimes:jpeg,jpg,png,gif','max:5000')
			);

		$validation = Validator::make( $data, $rules );

		if( $validation->fails() )
		{
			if($action=='edit') {
				return Redirect::to( 'admin/content/edit/'. $id )->withErrors($validation)->withInput();
			}
			return Redirect::to('admin/content/new')->withErrors($validation)->withInput();
		}

		//Add the new page!
		if(is_null($id)) {

	    	$content = new Content;

		} else {

			$content = Content::find($id);

		}

		if(Input::file('image')) {

			$destination = public_path().'/uploads/content/images/';
			$name = str_random(10) . '.' . Input::file('image')->getClientOriginalExtension();
			$upload = Input::file('image')->move( $destination , $name );
			
			$content->image = asset('/uploads/content/images/' . $name );

		}

		//Save the content
	    $content->title = $data['title'];
	    $content->slug = $data['slug'];
		$content->content = $data['content'];
		$content->user_id = $data['user_id'];
	    $content->category_id = $data['category_id'];
		$content->published = $data['published'];
		$content->edited_by = $data['user_id'];
		$content->area_id = $data['area_id'];
		$content->page = $data['page'];
		$content->save();

		//if it's a page, add it to the menu.
		if($content->page) {
			
			//Remove the old menu and add the new
			$content->menus()->sync(array($data['menu_id'] => array('name' => ($data['menu_name'] == '' ? $data['title'] : $data['menu_name']))));
			//$menu = Menu::find($data['menu_id']);

			//$menu->pages()->attach($content , array('name' => ($data['menu_name'] == '' ? $data['title'] : $data['menu_name'])));
		}

		return Redirect::to('admin/content')->withMessage('New page '.$data['title'].' added!');

	});

	Route::get('/', function()
	{
		return Redirect::to('admin/content');
	});

});

Route::get('denied', function()
{
	return View::make('auth.denied');
});



