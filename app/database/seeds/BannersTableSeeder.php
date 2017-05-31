<?php

class BannersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('banners')->delete();
        
		\DB::table('banners')->insert(array (
			0 => 
			array (
				'id' => 3,
				'name' => 'Banner 1',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			1 => 
			array (
				'id' => 4,
				'name' => 'Banner 2',
				'order_no' => 2,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			2 => 
			array (
				'id' => 5,
				'name' => 'Banner 3',
				'order_no' => 3,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			3 => 
			array (
				'id' => 6,
				'name' => 'Banner 4',
				'order_no' => 5,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			4 => 
			array (
				'id' => 7,
				'name' => 'Banner 5',
				'order_no' => 6,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			5 => 
			array (
				'id' => 8,
				'name' => 'Banner 6',
				'order_no' => 0,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			6 => 
			array (
				'id' => 9,
				'name' => 'Banner 7',
				'order_no' => 7,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			7 => 
			array (
				'id' => 10,
				'name' => 'Banner 8',
				'order_no' => 8,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			8 => 
			array (
				'id' => 12,
				'name' => 'Banner 9',
				'order_no' => 9,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
