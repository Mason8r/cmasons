<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class GroupsTableSeeder extends Seeder {

	public function run()
	{
				/*$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Area::create([

			]);
		}*/
		DB::table('groups')->delete();
	}

}