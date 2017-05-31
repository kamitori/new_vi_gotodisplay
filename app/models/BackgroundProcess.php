<?php

class BackgroundProcess {

	public static function resize($width, $path, $name)
	{
		$cmd = PHP_PATH.' '.ARTISAN." image:process --type=resize --path={$path} --name={$name} --width={$width}";
		return self::proccess($cmd);
	}

	public static function makeThumb($path, $name)
	{
		$cmd = PHP_PATH.' '.ARTISAN." image:process --type=thumb --path={$path} --name={$name}";
		return self::proccess($cmd);
	}

	public static function copyFromVI()
	{
		$cmd = PHP_PATH.' '.ARTISAN." image:process --type=copy-vi";
		return self::proccess($cmd);
	}

	public static function sync()
	{
		$cmd = PHP_PATH.' '.ARTISAN." getDB:get";
		return self::proccess($cmd);
	}

	public static function newsletterMail($arrData)
	{
		$subject = $email = $name = '';
		$unsubscribe = 0;
		if (isset($arrData['subject'])) {
			$subject = $arrData['subject'];
		}
		if (isset($arrData['email'])) {
			$email = $arrData['email'];
		}
		if (isset($arrData['name'])) {
			$name = $arrData['name'];
		}
		if (isset($arrData['unsubscribe'])) {
			$unsubscribe = $arrData['unsubscribe'];
		}

		$cmd = PHP_PATH.' '.ARTISAN." mail:auto --newsletter --subject=\"{$subject}\" --send_address=\"{$email}\" --send_name=\"{$name}\" --unsubscribe=\"{$unsubscribe}\"";
		return self::proccess($cmd);
	}

	public static function waitactiveMail($arrData)
	{
		$subject = $user_id = '';
		if (isset($arrData['subject'])) {
			$subject = $arrData['subject'];
		}
		if (isset($arrData['user_id'])) {
			$user_id = $arrData['user_id'];
		}
		$cmd = PHP_PATH.' '.ARTISAN." mail:auto --waitactive --subject=\"{$subject}\" --user_id={$user_id}";
		//echo $cmd;die;
		return self::proccess($cmd);
	}

	public static function proccess($cmd)
	{
		if( DS == '\\') {
	        	return pclose(popen("start /B ". $cmd, "r"));
	   	 } else {
	       		return exec($cmd.' > /dev/null &');
	    	}
	}

}
