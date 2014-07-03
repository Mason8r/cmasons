<?php

class BaseController extends Controller {

	/*public function __construct()
	{
		//Get the menu data @foreach(Menu::find(Site::pluck('main_menu'))->pages()->get() as $item)
		dd('fish');
		$mainMenuItems = Menu::find(Site::pluck('main_menu'))->pages()->where('published','=',1)->get();
		dd($mainMenuItems);
		View::share( 'mainMenuItems' , $mainMenuItems );
	}*/
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
