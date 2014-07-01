<?php

class Menu extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'menus';

	public function pages()
	{
		return $this->belongsToMany('Content');
	}

}