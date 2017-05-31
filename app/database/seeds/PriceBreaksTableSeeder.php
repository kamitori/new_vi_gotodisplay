<?php

class PriceBreaksTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('price_breaks')->delete();
        
		\DB::table('price_breaks')->insert(array (
			0 => 
			array (
				'id' => 2,
				'range_from' => 25,
				'range_to' => 27,
				'sell_price' => 0,
				'product_id' => 183,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-13 19:34:06',
				'updated_at' => '2015-02-13 20:05:28',
			),
			1 => 
			array (
				'id' => 3,
				'range_from' => 27,
				'range_to' => 36,
				'sell_price' => 0,
				'product_id' => 183,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-13 19:34:07',
				'updated_at' => '2015-02-13 20:05:41',
			),
			2 => 
			array (
				'id' => 4,
				'range_from' => 15,
				'range_to' => 27,
				'sell_price' => 0,
				'product_id' => 183,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-13 19:34:08',
				'updated_at' => '2015-02-13 20:05:49',
			),
			3 => 
			array (
				'id' => 5,
				'range_from' => 20,
				'range_to' => 25,
				'sell_price' => 0,
				'product_id' => 183,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-13 19:37:29',
				'updated_at' => '2015-02-13 19:37:29',
			),
			4 => 
			array (
				'id' => 7,
				'range_from' => 0,
				'range_to' => 5,
				'sell_price' => 12,
				'product_id' => 96,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-14 00:10:12',
				'updated_at' => '2015-04-17 09:02:30',
			),
			5 => 
			array (
				'id' => 8,
				'range_from' => 5,
				'range_to' => 10,
				'sell_price' => 25,
				'product_id' => 96,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-14 00:10:18',
				'updated_at' => '2015-04-17 09:02:30',
			),
			6 => 
			array (
				'id' => 9,
				'range_from' => 10,
				'range_to' => 15,
				'sell_price' => 25,
				'product_id' => 96,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-14 00:10:25',
				'updated_at' => '2015-04-17 09:02:30',
			),
			7 => 
			array (
				'id' => 13,
				'range_from' => 1,
				'range_to' => 5,
				'sell_price' => 55,
				'product_id' => 184,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-14 18:53:18',
				'updated_at' => '2015-02-14 18:53:34',
			),
			8 => 
			array (
				'id' => 14,
				'range_from' => 5,
				'range_to' => 10,
				'sell_price' => 40,
				'product_id' => 184,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-14 18:53:24',
				'updated_at' => '2015-02-14 18:53:36',
			),
			9 => 
			array (
				'id' => 15,
				'range_from' => 10,
				'range_to' => 15,
				'sell_price' => 35,
				'product_id' => 184,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-14 18:53:29',
				'updated_at' => '2015-02-14 18:53:38',
			),
			10 => 
			array (
				'id' => 16,
				'range_from' => 1,
				'range_to' => 10,
				'sell_price' => 26,
				'product_id' => 186,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-26 01:35:14',
				'updated_at' => '2015-02-26 01:35:39',
			),
			11 => 
			array (
				'id' => 17,
				'range_from' => 10,
				'range_to' => 15,
				'sell_price' => 20,
				'product_id' => 186,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-26 01:36:18',
				'updated_at' => '2015-02-26 01:36:23',
			),
			12 => 
			array (
				'id' => 18,
				'range_from' => 0,
				'range_to' => 5,
				'sell_price' => 11,
				'product_id' => 73,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-26 20:22:02',
				'updated_at' => '2015-02-27 19:42:33',
			),
			13 => 
			array (
				'id' => 19,
				'range_from' => 5,
				'range_to' => 999999,
				'sell_price' => 8,
				'product_id' => 73,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-02-26 20:22:24',
				'updated_at' => '2015-02-27 19:43:12',
			),
		));
	}

}
