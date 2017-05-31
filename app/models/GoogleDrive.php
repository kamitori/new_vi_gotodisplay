<?php
require_once base_path('vendor/google/apiclient/src/Google/autoload.php');
class GoogleDrive {

	private static function checkConfigure($configure)
	{
		if( empty($configure) ) {
			throw new Exception('Google Drive configure was empty.');
		} else if( !isset($configure['google_drive_email']) ) {
			throw new Exception('Google Drive email was not set.');
		} else if( !isset($configure['google_drive_key_file']) ) {
			throw new Exception('Google Drive key file was not set.');
		} else if( !File::exists($configure['google_drive_key_file']) ) {
			throw new Exception("Google Drive key file cannot be found.\nPath: {$googleDrive['google_drive_key_file']}");
		}
		return true;
	}

	public static function connect($configure = array(), $class='Google_Service_Drive')
	{
		if( empty($configure) ) {
			$configure = Configure::getGoogleDrive();
			self::checkConfigure($configure);
		}
		$auth = new Google_Auth_AssertionCredentials(
		   	$configure['google_drive_email'],
		    [
		    	Google_Service_Drive::DRIVE,
		    	Google_Service_Drive::DRIVE_READONLY,
		    	// Google_Service_Drive::DRIVE_APPDATA,
		    	// Google_Service_Drive::DRIVE_APPS_READONLY,
		    	Google_Service_Drive::DRIVE_FILE,
		    	// Google_Service_Drive::DRIVE_METADATA,
		    	Google_Service_Drive::DRIVE_METADATA_READONLY,
		    ],
		    File::get($configure['google_drive_key_file'])
		);
		$client = new Google_Client();
		$client->setAssertionCredentials($auth);
		return new $class($client);
	}

	public static function addFile($arrData)
	{
		$path = $arrData['path'];
		$title = isset($arrData['title']) ? $arrData['title'] : md5(time());
		$description = isset($arrData['description']) ? $arrData['description'] : '';
		$mimeType = isset($arrData['mime_type']) ? $arrData['mime_type'] : 'image/jpeg';
		//
		$googleDrive = Configure::getGoogleDrive();
		self::checkConfigure($googleDrive);
		$file  = self::connect($googleDrive, 'Google_Service_Drive_DriveFile');
		$service = self::connect($googleDrive);
		//
		$file->setTitle($title);
  		$file->setDescription($description);
  		$file->setMimeType($mimeType);
  		try {
  		    $data = File::get($path);
  		    $createdFile = $service->files->insert($file, [
  		      'data' => $data,
  		      'mimeType' => $mimeType,
  		      'uploadType' => 'media'
  		    ]);
  		    $fileId = $createdFile->getId();
  		    return self::getFile($fileId, $service);
  		} catch (Exception $e) {
			throw new Exception($e->getMessage());
  		}
	}

	public static function listFile($query = '', $service = null)
	{
		if( !($service instanceof Google_Service_Drive) ) {
			$googleDrive = Configure::getGoogleDrive();
			self::checkConfigure($googleDrive);
			$service = self::connect($googleDrive);
		}
		$arrFiles = [];
		$files = $service->files->listFiles([
										'q' =>  $query,
								]);
		foreach($files as $file) {
			$arrFiles[] = $file;
		}
		while( !empty($files->nextPageToken) ) {
			$files = $service->files->listFiles([
											'q' =>  $query,
											'pageToken' => $files->nextPageToken
									]);
			foreach($files as $file) {
				$arrFiles[] = $file;
			}
		}
		return $arrFiles;
	}

	public static function getFile($fileId, $service = null)
	{
		if( !($service instanceof Google_Service_Drive) ) {
			$googleDrive = Configure::getGoogleDrive();
			self::checkConfigure($googleDrive);
			$service = self::connect($googleDrive);
		}
		try {
		    return $service->files->get($fileId);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public static function downloadFile($fileLink, $destination, $service = null)
	{
		if( !($service instanceof Google_Service_Drive) ) {
			$googleDrive = Configure::getGoogleDrive();
			self::checkConfigure($googleDrive);
			$service = self::connect($googleDrive);
		}
		try {
		    $request = new Google_Http_Request($fileLink, 'GET', null, null);
	        $httpRequest = $service->getClient()->getAuth()->authenticatedRequest($request);
	        if ($httpRequest->getResponseHttpCode() == 200) {
	        	return File::put($destination, $httpRequest->getResponseBody());
	        } else {
	          	return false;
	        }
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public static function deleteFile($fileId, $service = null)
	{
		if( !($service instanceof Google_Service_Drive) ) {
			$googleDrive = Configure::getGoogleDrive();
			self::checkConfigure($googleDrive);
			$service = self::connect($googleDrive);
		}
		try {
		    return $service->files->delete($fileId);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
