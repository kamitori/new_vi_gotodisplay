<?php

class RolesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('roles')->delete();
        
		\DB::table('roles')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Root',
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
		));
	}

}
