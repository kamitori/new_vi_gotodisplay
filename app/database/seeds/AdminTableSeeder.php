<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class AdminTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		Admin::create([
			'first_name' => 'kei',
			'email'		 => 'hth.tung90@gmail.com',
			'password'	 => Hash::make('240990'),
		]);
		Admin::create([
			'first_name' => 'hung',
			'email'		 => 'hung@mail.com',
			'password'	 => Hash::make('123456'),
		]);
		Admin::create([
			'first_name' => 'tri',
			'email'		 => 'tri@mail.com',
			'password'	 => Hash::make('123456'),
		]);
		Admin::create([
			'first_name' => 'vu',
			'email'		 => 'vu@mail.com',
			'password'	 => Hash::make('123456'),
		]);
		foreach(range(1, 10) as $index)
		{
			Admin::create([
				'first_name' => $faker->firstName,
				'last_name'  => $faker->lastName,
				'email'		 => $faker->email,
				'password'	 => Hash::make('240990'),
			]);
		}
	}

}