<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ImageTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		if( !File::exists(public_path().DS.'assets'.DS.'images') ) {
			File::makeDirectory(public_path().DS.'assets'.DS.'images', 0777);
		}

		foreach(['users', 'admins', 'products', 'product_categories', 'banners', 'logos'] as $path) {
			$path = public_path().DS.'assets'.DS.'images'.DS.$path;
			if( !File::exists($path) ) {
				File::makeDirectory($path, 0777);
			}
			File::cleanDirectory($path);
		}

		$arrImages = [];

		foreach(range(1, 75) as $index)
		{
			$type = $faker->randomElement(['users','admins', 'products', 'product_categories', 'banners', 'logos']);
			$w = 110;
			$h = $faker->numberBetween(90, 340);
			if( $type == 'products' ) {
				$w = 500;
				$h = $faker->numberBetween(325, 846);
			} else if( $type == 'banners' ) {
				$w = 576;
				$h = 330;
			} else if( $type == 'logos' ) {
				$w = 400;
				$h = $faker->numberBetween(120, 200);
			}
			$path = public_path().DS.'assets/images/'.$type;
			$arrImages[$type][] = $index;
			$image = $faker->image($path, $w, $h);
			if( $type == 'products' ) {
				$name = str_replace($path.DS, '', $image);
				BackgroundProcess::resize($w, $path, $name);
				BackgroundProcess::makeThumb($path, $name);
			}
			$image = str_replace(public_path().DS, '', $image);
			$image = str_replace(DS,'/',$image);
			VIImage::create([
				'path'			=> $image,
			]);
		}

		Cache::put('arrImages', $arrImages, 5);
	}

}