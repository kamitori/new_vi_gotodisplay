<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class CategoryTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 30) as $index)
		{
			$name = $faker->sentence;
			if( $index < 10 ) {
				$parent_id = 0;
			} else {
				$parent_id = $faker->numberBetween(0,9);
			}
			ProductCategory::create([
				'name'			=> $name,
				'short_name'	=> Str::slug($name),
				'parent_id'		=> $parent_id,
				'meta_title' 	=> $faker->paragraph,
				'meta_description' 	=> $faker->text,
			]);
		}
	}

}