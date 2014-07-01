<?php

Class Content extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'content';

	public function scopeAllPosts($query)
	{
		return $query->where('page', '=', 0);
	}

}
