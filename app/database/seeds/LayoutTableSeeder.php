<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class LayoutTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			Layout::create([
				'name' 			=> $faker->sentence,
				'wall_size_w' 	=> $faker->numberBetween(20, 60),
				'wall_size_h' 	=> $faker->numberBetween(15, 46),
			]);
		}
	}

}