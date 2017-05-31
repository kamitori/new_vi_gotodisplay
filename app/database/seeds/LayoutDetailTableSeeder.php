<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class LayoutDetailTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 30) as $index)
		{
			LayoutDetail::create([
				'width' 	=> $faker->randomFloat(2,10,30),
				'height' 	=> $faker->randomFloat(2,5,40),
				'coor_x' 	=> $faker->randomFloat(2,0,60),
				'coor_y' 	=> $faker->randomFloat(2,0,40),
				'layout_id'	=> $faker->numberBetween(1, 10),

			]);
		}
	}

}