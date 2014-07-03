<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('AreasTableSeeder');
		$this->call('ContentcategoriesTableSeeder');
		$this->call('ContentTableSeeder');
		$this->call('GroupsTableSeeder');
		$this->call('SiteconfigTableSeeder');
		$this->call('UsersTableSeeder');
		$this->call('MenuTableSeeder');

	}

}



