<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class AreasTableSeeder extends Seeder {

	public function run()
	{
		
		DB::table('areas')->delete();

		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Area::create([				
				'name' 			=> $faker->unique()->word,
				'description' 	=> $faker->sentence($nbWords = 6),

			]);
		}

	}

}