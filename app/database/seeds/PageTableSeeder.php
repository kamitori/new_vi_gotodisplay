<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class PageTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			$name = $faker->paragraph;
			Page::create([
				'name' 				=> $name,
				'short_name' 		=> Str::slug($name),
				'meta_title' 		=> $faker->paragraph,
				'meta_description' 	=> $faker->text,
			]);
		}
	}

}