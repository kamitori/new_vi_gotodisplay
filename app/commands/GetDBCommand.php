<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetDBCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'getDB:get';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sync database from Jobtraq';

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
		$path = public_path().DS.'mongodump';

		if( !File::exists($path) ){
			File::makeDirectory($path, 0777, true);
		}

		File::cleanDirectory($path);

		$path .= DS.'mongodump-'.(date('y.m.d'));

		if( !File::exists($path) ) {
			File::makeDirectory($path, 0777, true);
		}

		if( URL == 'http://vi-demo.anvyonline.com' ) {
			$lastSync = DB::connection('jobtraq-demo.anvyonline.com')
			               	->collection('tb_stuffs')
			               	->select('_id', 'sync_time', 'name', 'date_modified')
							->where('name', 'last_sync_date')
							->first();
			if( is_object($lastSync) ) {
				$lastSync = $lastSync->toArray();
			}
			$lastSync['sync_time']++;
			$lastSync['date_modified'] = new MongoDate();
			if( isset($lastSync['_id']) ){
				$_id = $lastSync['_id'];
				unset($lastSync['_id']);
				DB::connection('jobtraq-demo.anvyonline.com')
	               	->collection('tb_stuffs')
	               	->where('_id', $_id)
					->update($lastSync);
			} else {
				DB::connection('jobtraq-demo.anvyonline.com')
	               	->collection('tb_stuffs')
	               	->insert($lastSync);
			}
		} else {
			$lastSync = JTStuff::select('_id', 'sync_time', 'name', 'date_modified')
							->where('name', 'last_sync_date')
							->first();

			if( empty($lastSync) ) {
				$lastSync = [
						'name' => 'last_sync_date',
						'sync_time' => 0,
						'date_modified'	=> new MongoDate()
				];
			}
			foreach(['tb_contact', 'tb_company', 'tb_country', 'tb_province', 'tb_tax', 'tb_product', 'tb_settings'] as $collection) {
				$lastRecord = DB::connection(JT_DB)
									->collection($collection)
									->select('_id')
									->orderBy('_id', 'desc')
									->first();
				$query = '{ \'_id\' : { \'$gte\' : ObjectId(\''.$lastRecord['_id'].'\') } }';
				if( DS == '/' ) {
					$query = '{ \'_id\' : { \$gte : ObjectId(\''.$lastRecord['_id'].'\') } }';
				}
				$command = 'mongodump -h '. JT_IP .' -u sadmin -p'.JT_PASS.' --port 27017 -d jobtraq -q "'.$query.'" -c '.$collection.' -o '.$path;
				exec($command);
				if( is_file($path.DS.'jobtraq'.DS.$collection.'.bson') ){
					exec("mongorestore  -d jobtraq -c $collection {$path}".DS."jobtraq".DS."$collection.bson");
				}
				$newRecords = DB::connection(JT_DB)
									->collection($collection)
									->whereRaw([
										'date_modified' => [ '$gte' => $lastSync['date_modified'] ],
										'_id'			=> [ '$lte' => $lastRecord['_id'] ]
									])
									->get();
				if( !is_null($newRecords) ){
					foreach($newRecords as $record) {
						foreach($record as $key => $value) {
							if( strpos($key, '_id') !== false
									&& is_string($value) && strlen($value) == 24 ) {
								$record[$key] = new MongoId($value);
							} else if( $key == 'updated_at' && is_string($value) ) {
								$record[$key] = new MongoDate(strtotime($value));
							}
						}
						$_id = $record['_id'];
						unset($record['_id']);
						DB::connection('anvyonline.com')
							->collection($collection)
							->where('_id', $_id)
							->update($record);
					}
				}

			}
			$updateBleed = DB::connection(JT_DB)
							->collection('tb_JTStuffs')
							->select('name', 'value', 'option', 'deleted')
							->where('value', 'bleed_type')
							->first();
			if( isset($updateBleed['_id']) ) {
				$bleed = JTStuff::where('value', 'bleed_type')
								->first();
				if( isset($bleed['_id']) ) {
					unset($bleed['_id']);
					JTStuff::where('value', 'bleed_type')
						->update($updateBleed);
				} else {
					JTStuff::insert($updateBleed);
				}
			}
			if( is_object($lastSync) ) {
				$lastSync = $lastSync->toArray();
			}
			$lastSync['sync_time']++;
			$lastSync['date_modified'] = new MongoDate();
			if( isset($lastSync['_id']) ){
				$_id = $lastSync['_id'];
				unset($lastSync['_id']);
				JTStuff::where('_id', $_id)
						->update($lastSync);
			} else {
				JTStuff::insert($lastSync);
			}
		}
		$arrData = [
					'event'		=> 'DBSync',
					'message' 	=> 'Database has been synchronized to the lastest version.',
					'status' 	=> 'success',
					'updated_at' 	=> date('d M, y H:i', $lastSync['date_modified']->sec),
					'updated_time' 	=> number_format($lastSync['sync_time'])
				];
		Notification::sendSocket($arrData, true);
		$this->info("Notification is sent. \nURL: ".URL."\nAPP_ID: ". PUSHER_APP_ID . "\nKEY: ". PUSHER_KEY . "\nSECRET: ".PUSHER_SECRET);
	}
}
