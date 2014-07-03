<?php

Class Content extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	use SoftDeletingTrait;

	protected $table = 'content';

	//protected $dates = ['deleted_at'];

	public function scopeAllPosts($query)
	{
		return $query->where('page', '=', 0);
	}

	public function menus()
	{
		return $this->belongsToMany('Menu')->withPivot('name');
	}

}
