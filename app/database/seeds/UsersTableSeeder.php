<?php

class UsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->delete();
        
		\DB::table('users')->insert(array (
			0 => 
			array (
				'id' => 1,
				'first_name' => 'Ernestina',
				'last_name' => 'Homenick',
				'email' => 'ukunze@caspersimonis.com',
				'password' => '$2y$10$Ymp3UUwY/4tx2cFkKly1xO02DQqZyYtDLaQk5HbnXOiDiY98FIQgS',
				'subscribe' => 0,
				'remember_token' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:26:57',
				'updated_at' => '2015-04-16 08:26:57',
			),
			1 => 
			array (
				'id' => 2,
				'first_name' => 'Julius',
				'last_name' => 'Corkery',
				'email' => 'wlegros@grady.com',
				'password' => '$2y$10$OEhSqUV5tOebpPOXNRDc9OCn8RpfR.xttNLBXup4t1y5HArA4L1oi',
				'subscribe' => 0,
				'remember_token' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:26:57',
				'updated_at' => '2015-04-16 08:26:57',
			),
			2 => 
			array (
				'id' => 3,
				'first_name' => 'Elyse',
				'last_name' => 'Fay',
				'email' => 'layne.o\'keefe@lebsack.com',
				'password' => '$2y$10$ROsVQRgiZceJQi9WeZNCC.DlCdzrhWbR/3iqBUqrG3OZ.kVmBfBhK',
				'subscribe' => 0,
				'remember_token' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:26:57',
				'updated_at' => '2015-04-16 08:26:57',
			),
			3 => 
			array (
				'id' => 4,
				'first_name' => 'Luigi',
				'last_name' => 'Kulas',
				'email' => 'kessler.kathryn@hessel.com',
				'password' => '$2y$10$BpWHN69ILWl1MKmmCLdimOVtS6D3BRI1W37RcCHG9wIbFt0cUdMqC',
				'subscribe' => 0,
				'remember_token' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:26:57',
				'updated_at' => '2015-04-16 08:26:57',
			),
			4 => 
			array (
				'id' => 5,
				'first_name' => 'Dayton',
				'last_name' => 'Senger',
				'email' => 'alvis.weissnat@dubuquekuphal.org',
				'password' => '$2y$10$IxMcN/khNcbFO8f1G7puM.yztvvMmm/i5aMyUSNL7dhe.G5mJh/uW',
				'subscribe' => 0,
				'remember_token' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:26:57',
				'updated_at' => '2015-04-16 08:26:57',
			),
		));
	}

}
