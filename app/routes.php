<?php

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
		$data['posts'] = Content::allPosts()->get();
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

	$menu->pages()->attach($page);
	$menu->pages()->attach($news);

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
| 
|
*/
Route::get('admin', array('before' => 'auth|group:admin', function()
{
	return View::make('admin.dashboard');
}));

Route::get('admin/panel/{panel}', function($panel)
{
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

});

Route::post('admin/panel/{panel}/{action?}', function( $panel , $action )
{

	echo $panel;
	echo $action;

});



Route::get('denied', function()
{
	return View::make('auth.denied');
});



