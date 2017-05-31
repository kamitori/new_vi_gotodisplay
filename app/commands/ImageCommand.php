<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class ImageCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'image:process';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Image command.';

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
		$this->path 	= $this->option('path');
		$this->name 	= $this->option('name');
		$this->width 	= $this->option('width');
		$this->type 	= $this->option('type');
		switch ($this->type) {
			case 'resize':
				$this->resize();
				break;
			case 'thumb':
				$this->makeThumb();
				break;
			case 'copy-vi':
				$this->copyFromVI();
				break;
			default:
				$this->makeThumb();
				break;
		}
	}

	private function resize()
	{
		$image = Image::make($this->path.DS.$this->name);
		if( $image->width() > $this->width ) {
		    $image->resize($this->width, null, function($constraint){
		        $constraint->aspectRatio();
		    });
		    $image->save($this->path.DS.$this->name);
		}
	}

	private function makeThumb()
	{
		$image = Image::make($this->path.DS.$this->name);
		$image->resize(200, null, function($constraint){
	        $constraint->aspectRatio();
	    });
	    if( !File::exists($this->path.DS.'thumbs') ) {
	    	File::makeDirectory($this->path.DS.'thumbs', 0777, true);
	    }
		$image->save($this->path.DS.'thumbs'.DS.$this->name);
	}

	private function copyFromVI()
	{
		$public_path = public_path();

		$vi_upload_path = app_path().DS.'..'.DS.'..'.DS.'vi'.DS.'public'.DS.'assets'.DS.'upload';
		$vi2_image_path = public_path().DS.'assets'.DS.'images';

		$arrDirs = ['admins', 'users', 'logos', 'products', 'product_categories', 'banners'];
		foreach($arrDirs as $dir) {
			if( !File::exists($vi2_image_path.DS.$dir) ) {
				File::makeDirectory($vi2_image_path.DS.$dir, 0777, true);
			}
			// File::cleanDirectory($vi2_image_path.DS.$dir);
		}

		$images = VIImage::select('id', 'path')
					->get();
		$arrRemoves = [];
		foreach($images as $image) {
			list($dir, $name) = explode('/', str_replace('assets/images/', '', $image->path));
			if( File::exists($vi2_image_path.DS.$dir.DS.$name) ) {
				continue;
			}
			$name = addslashes($name);
			if( $dir == 'banners' ) {
				$imagePath = $vi_upload_path.DS.'slide'.DS.$name;
			} else {
				$imagePath = $vi_upload_path.DS.$name;
			}
			if( File::exists($imagePath) ) {
				$copy = File::copy($imagePath, $vi2_image_path.DS.$dir.DS.$name);
				if ( $copy ) {
					$this->path = $vi2_image_path.DS.$dir;
					$this->name = $name;
					if( $dir == 'products' ) {
						$this->width = 500;
						$this->resize();
						$this->makeThumb();
					} else if ( $dir == 'product_categories' ) {
						$this->width = 450;
						$this->resize();
					} else if ( $dir == 'banners' ) {
						$this->width = 576;
						$this->resize();
					}
				}
			} else {
				$arrRemoves[] = $image->id;
			}
		}

		if( !empty($arrRemoves) ) {
			VIImage::destroy($arrRemoves);
		}

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
			array('type', null, InputOption::VALUE_REQUIRED, 'Type of image processing.', null),
			array('path', null, InputOption::VALUE_OPTIONAL, 'Path of image.', null),
			array('name', null, InputOption::VALUE_OPTIONAL, 'Name of image.', null),
			array('width', null, InputOption::VALUE_OPTIONAL, 'Name of image.', null),
		);
	}

}
