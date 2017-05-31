<?php

class ConfigureTableSeeder extends Seeder {

	public function run()
	{
		Configure::create([
			'cname' 	=> 'Title Site',
			'ckey'		=> 'title_site',
			'cvalue'	=> 'Visual Impact'
		]);

		Configure::create([
			'cname' 	=> 'Meta Description',
			'ckey'		=> 'meta_description',
			'cvalue'	=> 'www.anvydigital.com'
		]);

		Configure::create([
			'cname' 	=> 'Main Logo',
			'ckey'		=> 'main_logo',
			'cvalue'	=> 'assets/images/logos/logo.png'
		]);

		Configure::create([
			'cname' 	=> 'VI Format',
			'ckey'		=> 'vi_format',
			'cvalue'	=> 4
		]);

		Configure::create([
			'cname' 	=> 'Instagram App ID',
			'ckey'		=> 'instagram_app_id',
			'cvalue'	=> 'f6b31259ea3d4f8489da2e137cec4c34'
		]);

		Configure::create([
			'cname' 	=> 'Skydrive App ID',
			'ckey'		=> 'skydrive_app_id',
			'cvalue'	=> '0000000040149c21'
		]);

		Configure::create([
			'cname' 	=> 'Google Drive App ID',
			'ckey'		=> 'googledrive_app_id',
			'cvalue'	=> '542866151209-h64bq9qnogf0e51b7rir1cuni1pnlc8j.apps.googleusercontent.com'
		]);

		Configure::create([
			'cname' 	=> 'Dropbox App ID',
			'ckey'		=> 'dropbox_app_id',
			'cvalue'	=> '4h5nj85dysuxe3s'
		]);

		Configure::create([
			'cname' 	=> 'Flickr App Secret',
			'ckey'		=> 'flickr_app_secret',
			'cvalue'	=> '58db44a1386f0b4e'
		]);

		Configure::create([
			'cname' 	=> 'Flickr App ID',
			'ckey'		=> 'flickr_app_id',
			'cvalue'	=> '24fdd4da6151132517c7d4572c29d1f0'
		]);

		Configure::create([
			'cname' 	=> 'Facebook APP ID',
			'ckey'		=> 'facebook_app_id',
			'cvalue'	=> '1601264390104375'
		]);
	}

}