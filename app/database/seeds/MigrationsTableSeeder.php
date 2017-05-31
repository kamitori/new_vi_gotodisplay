<?php

class MigrationsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('migrations')->delete();
        
		\DB::table('migrations')->insert(array (
			0 => 
			array (
				'migration' => '2015_01_28_070725_create_admin',
				'batch' => 1,
			),
			1 => 
			array (
				'migration' => '2015_01_28_071018_create_user',
				'batch' => 1,
			),
			2 => 
			array (
				'migration' => '2015_01_28_083125_create_menu',
				'batch' => 1,
			),
			3 => 
			array (
				'migration' => '2015_01_29_082049_create_product',
				'batch' => 1,
			),
			4 => 
			array (
				'migration' => '2015_01_29_091150_create_image',
				'batch' => 1,
			),
			5 => 
			array (
				'migration' => '2015_01_29_100950_create_category',
				'batch' => 1,
			),
			6 => 
			array (
				'migration' => '2015_01_30_023547_create_imageable',
				'batch' => 1,
			),
			7 => 
			array (
				'migration' => '2015_01_30_032336_create_option',
				'batch' => 1,
			),
			8 => 
			array (
				'migration' => '2015_01_30_064131_create_product_category',
				'batch' => 1,
			),
			9 => 
			array (
				'migration' => '2015_03_17_084859_create_option_group',
				'batch' => 1,
			),
			10 => 
			array (
				'migration' => '2015_03_17_085935_create_optionable',
				'batch' => 1,
			),
			11 => 
			array (
				'migration' => '2015_03_19_021221_create_type',
				'batch' => 1,
			),
			12 => 
			array (
				'migration' => '2015_03_19_073756_create_price_break',
				'batch' => 1,
			),
			13 => 
			array (
				'migration' => '2015_03_20_071759_create_size_list',
				'batch' => 1,
			),
			14 => 
			array (
				'migration' => '2015_04_07_083850_create_layout',
				'batch' => 1,
			),
			15 => 
			array (
				'migration' => '2015_04_07_084218_create_layout_detail',
				'batch' => 1,
			),
			16 => 
			array (
				'migration' => '2015_04_09_070936_create_banner',
				'batch' => 1,
			),
			17 => 
			array (
				'migration' => '2015_04_10_022417_create_configure',
				'batch' => 1,
			),
			18 => 
			array (
				'migration' => '2015_04_10_090125_create_page',
				'batch' => 1,
			),
			19 => 
			array (
				'migration' => '2015_04_20_024011_create_contact',
				'batch' => 1,
			),
			20 => 
			array (
				'migration' => '2015_04_21_024840_entrust_setup_tables',
				'batch' => 1,
			),
			21 => 
			array (
				'migration' => '2015_04_21_031560_create_order',
				'batch' => 1,
			),
			22 => 
			array (
				'migration' => '2015_04_21_090041_create_addresses',
				'batch' => 1,
			),
			23 => 
			array (
				'migration' => '2015_04_22_044734_create_password_reminders_table',
				'batch' => 1,
			),
			24 => 
			array (
				'migration' => '2015_04_22_075507_create_notification',
				'batch' => 1,
			),
			25 => 
			array (
				'migration' => '2015_04_23_020055_create_order_details',
				'batch' => 1,
			),
			26 => 
			array (
				'migration' => '2015_04_24_012936_create_email_templates',
				'batch' => 1,
			),
		));
	}

}
