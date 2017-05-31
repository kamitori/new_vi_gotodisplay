<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class GoogleDriveCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'google_drive:get';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Google Drive.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$timePath = app_path('files'.DS.'google-drive-sync-time.txt');
		$time = '';
		if( File::exists($timePath) ) {
			$time = File::get($timePath);
		}
		$query = 'mimeType contains \'image\' and mimeType != \'image/svg+xml\'';
		if( !empty($time) ) {
			$query .= ' and modifiedDate > \''.$time.'\'';
		}
		$files = GoogleDrive::listFile($query);
		$arrInsert = $arrDelete = [];
		foreach ($files as $file) {
			if(  $file->explicitlyTrashed ) {
				$arrDelete[] = $file->id;
				continue;
			}
			$image = VIImage::firstOrCreate([
					'path'	=> '',
					'store' => 'google-store',
					'file_id' => $file->id
				]);
			if( !DB::table('imageables')
						->where('image_id', $image->id)
						->where('imageable_id', 0)
						->where('imageable_type', 'Other')
						->count() ) {
				DB::table('imageables')
					->insert([
							'image_id' 		=> $image->id,
							'imageable_id' 	=> 0,
							'imageable_type'=> 'Other',
							'option' 		=> ''
						]);
				$arrInsert[] = $file->id;
			}
		}
		if( !empty($arrDelete) ){
			$images = [];
			foreach($arrDelete as $fileId) {
				$imageId = VIImage::where('file_id', $fileId)
								->where('store', 'google-drive')
								->pluck('id');
				if( $imageId ) {
					$images[] = $imageId;
				}
			}
			VIImage::destroy( $images );
			DB::table('imageables')
					->where('imageable_type', 'Other')
					->where('imageable_id', 0)
					->whereIn('image_id', $images)
					->delete();
		}
		File::put($timePath, gmdate("Y-m-d\TH:i:s"));
		$this->info('Query: "'.$query.'".'."\n".'Inserted '.count($arrInsert).' images(s).'."\n".'Deleted '.count($arrDelete).' image(s).');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
