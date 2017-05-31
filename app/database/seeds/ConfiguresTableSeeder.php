<?php

class ConfiguresTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('configures')->delete();
        
		\DB::table('configures')->insert(array (
			0 => 
			array (
				'id' => 1,
				'cname' => 'Title Site',
				'ckey' => 'title_site',
				'cvalue' => 'Visual Impact',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			1 => 
			array (
				'id' => 2,
				'cname' => 'Meta Description',
				'ckey' => 'meta_description',
				'cvalue' => 'www.anvydigital.com',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			2 => 
			array (
				'id' => 3,
				'cname' => 'Main Logo',
				'ckey' => 'main_logo',
				'cvalue' => 'assets/images/logos/logo.png',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			3 => 
			array (
				'id' => 4,
				'cname' => 'VI Format',
				'ckey' => 'vi_format',
				'cvalue' => '2',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 3,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-20 07:24:48',
			),
			4 => 
			array (
				'id' => 5,
				'cname' => 'Instagram App ID',
				'ckey' => 'instagram_app_id',
				'cvalue' => 'f6b31259ea3d4f8489da2e137cec4c34',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			5 => 
			array (
				'id' => 6,
				'cname' => 'Skydrive App ID',
				'ckey' => 'skydrive_app_id',
				'cvalue' => '0000000040149c21',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			6 => 
			array (
				'id' => 7,
				'cname' => 'Google Drive App ID',
				'ckey' => 'googledrive_app_id',
				'cvalue' => '542866151209-h64bq9qnogf0e51b7rir1cuni1pnlc8j.apps.googleusercontent.com',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			7 => 
			array (
				'id' => 8,
				'cname' => 'Dropbox App ID',
				'ckey' => 'dropbox_app_id',
				'cvalue' => '4h5nj85dysuxe3s',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			8 => 
			array (
				'id' => 9,
				'cname' => 'Flickr App Secret',
				'ckey' => 'flickr_app_secret',
				'cvalue' => '58db44a1386f0b4e',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			9 => 
			array (
				'id' => 10,
				'cname' => 'Flickr App ID',
				'ckey' => 'flickr_app_id',
				'cvalue' => '24fdd4da6151132517c7d4572c29d1f0',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
			10 => 
			array (
				'id' => 11,
				'cname' => 'Facebook APP ID',
				'ckey' => 'facebook_app_id',
				'cvalue' => '1601264390104375',
				'cdescription' => NULL,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-04-16 08:27:04',
				'updated_at' => '2015-04-16 08:27:04',
			),
		));
	}

}
