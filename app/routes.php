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
	$data['page'] = Content::find(Site::pluck('homepage_id'));

	return View::make('content.page' , $data );
});

Route::get('page/{slug}', function($slug)
{

	$data['page'] = Content::where('slug' , '=' , $slug)->first();

	//If its the news page, get the news
	if( $data['page']->id === Site::pluck('postspage_id') )
	{
		$data['posts'] = Content::allPosts()->where('published' , '=' , 1 )->get();
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


/*
|--------------------------------------------------------------------------
| Installation Stuff
|--------------------------------------------------------------------------
|
| If any of the site data is missing, direct user to the install page
| Create an admin user
| Create an admin group
| Create a default home page with content
| Create a default posts page and post
| 
|
*/
Route::get('install', function()
{
	//if a group called admin exists, never do this.
	if(!Site::pluck('id')) {
		return View::make('installation.install');
	}
	return Redirect::to('/');
});

Route::post('install', function()
{
	Input::flash();

	$data = Input::all();

	$rules = array(
		'sitename' 		=> 'required',
		'creator' 		=> 'required',
		'email'			=> 'required|email',
		'password'		=> 'required|confirmed',
		'copyright' 	=> 'required',
		'description' 	=> 'required',
		);

	$validation = Validator::make( $data, $rules );

	if( $validation->fails() )
	{
		return Redirect::to('install')->withErrors($validation)->withInput();
	}

	//create the admin group
    $adminGroup = Sentry::createGroup(array(
        'name'        => 'Admin',
        'permissions' => array(
            'admin' => 1,
        ),
    ));

    //create the admin user
    $user = Sentry::createUser(array(
        'email'     => $data['email'],
        'password'  => $data['password'],
        'first_name'	=> $data['creator'],
        'activated' => true,
    ));

    $user->addGroup($adminGroup);

    $area = new Area;
	$area->name = 'public';
	$area->description = 'For all posts available to the public.';
	$area->save();

	$cat = new ContentCategory;
	$cat->name = 'default';
	$cat->description = 'default category for all content.';
	$cat->save();

    //create the default homepage
    $page = new Content;
    $page->title = 'Home';
    $page->slug = 'home';
	$page->content = '<h1>Welcome to '.$data['sitename'].'</h1><p>Get making some content!</p>';
	$page->user_id = $user->id;
    $page->category_id = $cat->id;
	$page->published = 1;
	$page->edited_by = $user->id;
	$page->area_id = $area->id;
	$page->page = 1;
	$page->save();

	//create the default news
    $news = new Content;
    $news->title = 'News';
    $news->slug = 'news';
	$news->content = '<h1>News Page</h1><p>All the posts appear below!</p>';
	$news->user_id = $user->id;
    $news->category_id = $cat->id;
	$news->published = 1;
	$news->edited_by = $user->id;
	$news->area_id = $area->id;
	$news->page = 1;
	$news->save();

	//create first post
    $post = new Content;
    $post->title = 'First Post!';
    $post->slug = 'first-post';
	$post->content = '<h3>This is my first post!</h3><p>isnt it great!</p>';
	$post->user_id = $user->id;
    $post->category_id = $cat->id;
	$post->published = 1;
	$post->edited_by = $user->id;
	$post->area_id = $area->id;
	$post->page = 0;
	$post->save();

	$menu = new Menu;
	$menu->name = 'Main';
	$menu->description = 'Main menu for the site';
	$menu->save();

	$menu->pages()->attach($page , array('name' => 'Welcome'));
	$menu->pages()->attach($news , array('name' => 'Blog'));

	//Finall enter the default site shite
	$site = new Site;
	$site->name		= $data['sitename'];
	$site->creator 		= $data['creator'];
	$site->email 		= $data['email'];
	$site->copyright 	= $data['copyright'];
	$site->description 	= $data['description'];
	$site->homepage_id	= $page->id;
	$site->postspage_id	= $news->id;
	$site->main_menu	= $menu->id;
	$site->save();

	return Redirect::to('admin');

});

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
			'menu_name'		=> array('required', (is_null($id) ? 'unique:content_menu,name' : '')),
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




/*

Route::get('admin', array('before' => 'auth|group:admin', function()
{
	return View::make('admin.dashboard');
}));

//////
Route::get('admin/panel/{panel?}/{action?}', array('before' => 'auth|group:admin', function($panel = 'pages', $action = false)
{
	$data['panel'] = $panel;
	$data['action'] = $action;
	return View::make('admin.main' , $data );
}));
////

Route::get('admin/panel/{panel}/{action?}' , function($panel, $action = false)
{
	if(!$action) {
		//get different data for each of the fucking pages
		switch ($panel) {
			case 'pages':
				$data['pages'] = Content::where('page','=',1)->get();
				break;
			case 'posts':
				$data['posts'] = Content::where('page','=',0)->get();
				break;
			case 'users':
				$data['users'] = Cartalyst\Sentry\Users\Eloquent\User::all();
				break;		
			default:
				$data['settings'] = Site::all();
				break;
		}
			
		return View::make('admin.panel.'. $panel , $data);	
	}

	if($action=='new') {
		return Redirect::to( 'admin/content/new/' . $panel );
	}
	

});

//Add a new page or post
Route::get('admin/content/new/{type}' , function( $type ) {

	$data['type'] = $type;

	$db['menus'] = Menu::all();
	$db['areas'] = Area::all();
	$db['categories'] = ContentCategory::all();

	//extract all the data needed from the mother fucking DB
	foreach ($db as $key => $objects ) {
		foreach ($objects as $value ) {
			$data[$key][$value->id] = $value->name;
		}
	}

	return View::make('admin.panel.content.new' , $data );

});
//Add a new page or post
Route::post('admin/content/new/{type}' , function( $type ) {

	Input::flash();

	$data = Input::all();

	$rules = array(
		'title' 	=> 'required',
		'slug' 		=> 'required',
		'content'	=> 'required',
		);

	$validation = Validator::make( $data, $rules );

	if( $validation->fails() )
	{
		return Redirect::to( 'admin/content/new/' . $data['page'] )->withErrors($validation)->withInput();
	}

	$post = new Content;
    $post->title = $data['title'];
    $post->slug = urlencode($data['slug']);
	$post->content = $data['content'];
	$post->user_id = $data['user_id'];
    $post->category_id = $data['category_id'];
	$post->published = $data['published'];
	$post->edited_by = 0;
	$post->area_id = $data['area_id'];
	$post->page = ($data['page'] == 'page' ? 1 : 0);
	$post->save();

	return Redirect::to('admin')->withMessage('Success!');

});

Route::post('admin/panel/{panel}/{action?}', function( $panel , $action )
{
	//get different data for each of the fucking pages
	//pages & posts	
	switch ($panel) {
		case 'users':
			//Users controller right here man...
			return 'USERS!';
			break;
		case 'main':
			//Users controller right here man...
			return 'MAIN SITE SETTINGS!';
			break;
		default:
			# code...
			break;
	}

	//users
	//main
	echo $panel;
	echo $action;
	echo Input::get('id');

});

*/

Route::get('denied', function()
{
	return View::make('auth.denied');
});



