<?php

class InstallController extends BaseController {

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
	public function getIndex()
	{
		//if a group called admin exists, never do this.
		if(!Site::pluck('id')) {

			return View::make('installation.install');

		}

		return Redirect::to('/');
	}

	public function postIndex()
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

	    //create a default user group
	    $adminGroup = Sentry::createGroup(array(
	        'name'        => 'User',
	        'permissions' => array(
	            'user' => 1,
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
	
	}

}
