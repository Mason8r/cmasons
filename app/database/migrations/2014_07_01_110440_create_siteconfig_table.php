<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteconfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('siteconfig', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email');
			$table->binary('copyright');
			$table->binary('description');
			$table->string('creator');
			$table->integer('homepage_id');
			$table->integer('postspage_id');
			$table->integer('main_menu');
			$table->timestamps();
		});

		Schema::create('content', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('slug');
			$table->binary('content');
			$table->integer('user_id'); //author
			$table->integer('category_id'); //obv
			$table->boolean('published');
			$table->integer('edited_by'); //user ID of last editor
			$table->integer('area_id'); //Access level?
			$table->integer('page'); //if it's not a page, it's a blog post
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('content_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->binary('description');
			$table->integer('area_id'); // 0 = all, > 0 = Access level
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('areas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->binary('description');
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->binary('description');
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('content_menu', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('menu_id'); // 0 = all, > 0 = Access level
			$table->integer('content_id');
			$table->string('name'); //name to display on page
			$table->timestamps();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('siteconfig');
		Schema::drop('content');
		Schema::drop('content_categories');
		Schema::drop('areas');
		Schema::drop('menus');
		Schema::drop('content_menu');
	}

}

