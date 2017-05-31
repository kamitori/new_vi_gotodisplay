<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class DBCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:command';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'DB commands.';

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
		switch ($this->option('type')) {
			case 'image_tags':
				$this->imageTags();
				break;
			case 'image_store':
				$this->imageStore();
			case 'product_size':
				$this->productSize();
				break;
			case 'product_custom_size':
				$this->productCustomSize();
			case 'layout':
				$this->layout();
				break;
		}
	}

	private function imageTags()
	{
		$data = DB::select(DB::raw('select name from mysql.proc where name = "imageTags" and db="'.DB::connection()->getDatabaseName().'"'));
		if( empty($data) ) {
			DB::unprepared('
				            CREATE PROCEDURE imageTags()
				            BEGIN
				                CREATE TEMPORARY TABLE temp (val CHAR(255));
				                SET @S1 = CONCAT("INSERT INTO temp (val) VALUES (\'",REPLACE((SELECT GROUP_CONCAT( DISTINCT  `option`) AS data FROM `imageables` WHERE `imageables`.`imageable_type` = "Other"), ",", "\'),(\'"),"\');");
				                PREPARE stmt1 FROM @s1;
				                EXECUTE stmt1;
				                SELECT DISTINCT(val) FROM temp;
				            END;
				        ');
		}
		DB::statement('ALTER TABLE imageables ENGINE="MyISAM";');
		DB::statement('ALTER TABLE imageables ADD FULLTEXT tag(`option`);');
	}

	private function imageStore()
	{
		DB::statement('ALTER TABLE `images` ADD `store` VARCHAR(100) NOT NULL DEFAULT "local" AFTER `path`;');
		DB::statement('ALTER TABLE `images` ADD `file_id` VARCHAR(100) NULL  AFTER `store`;');
	}

	private function productCustomSize()
	{
		DB::statement('ALTER TABLE `products` ADD `custom_size` TINYINT(1) NOT NULL DEFAULT 1 AFTER `svg_layout_id`;');
	}

	private function productSize()
	{
		$arrInsert = [];
		$products = Product::select('id', 'sku', 'margin_up', 'svg_layout_id')
							->get();
		foreach($products as $product) {
			if( !SizeList::where('product_id', $product->id)
						->where('sizew', 12)
						->where('sizeh', 12)
						->count() ) {
				$sizew = $sizeh = 12;
				$costPrice = 0;
				$jtProduct = JTProduct::select('code', 'name', 'sku', 'sell_price', 'oum', 'oum_depend', 'sell_by','pricebreaks', 'sellprices', 'unit_price','options', 'products_upload', 'product_desciption', 'is_custom_size')
							->where('deleted', false)
							->where('sku', $product->sku)
							->where('product_type', 'web')
							->first();
				if( is_object($jtProduct) ) {
					$costPrice = JTProduct::calculateCostPrice($jtProduct, 12, 12);
					if( isset($jtProduct['sell_by']) && strtolower($jtProduct['sell_by']) == 'unit' ) {
						$sizew = $sizeh = 1;
						if( $product->svg_layout_id ) {
							$layout = Layout::select('wall_size_h', 'wall_size_w')
										->where('id', $product->svg_layout_id)
										->first();
							if( $layout ) {
								$sizew = $layout->wall_size_w;
								$sizeh = $layout->wall_size_h;
							}
						}
					}
				}
				if( !$costPrice ) {
					continue;
				}
				$sellPercent = 100 + $product->margin_up;
				$biggerPercent = 100;
				$biggerPrice = $sellPrice = $costPrice * $sellPercent / 100;
				$arrInsert[] = [
								'product_id' => $product->id,
								'sizew' 	 => $sizew,
								'sizeh' 	 => $sizeh,
								'cost_price' 		=> $costPrice,
								'sell_percent' 		=> $sellPercent,
								'bigger_percent' 	=> $biggerPercent,
								'sell_price' 		=> $sellPrice,
								'bigger_price' 		=> $biggerPrice,
							];
			}
		}
		if( !empty($arrInsert) ) {
			SizeList::insert($arrInsert);
		}
	}

	private function shapePath()
	{
		try {
			DB::statement('ALTER TABLE `shape_layout_details` CHANGE `points` `d` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;');
		} catch( Exception $e) {

		}
		DB::statement('ALTER TABLE `shape_layout_details` CHANGE `d` `d` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;');
		DB::statement('ALTER TABLE `shape_layout_details` CHANGE `transform` `transform` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;');
	}

	private function layout()
	{
		foreach(['rotate', 'transform', 'shape_type'] as $column) {
			try {
				DB::statement('ALTER TABLE `layout_details` DROP `'.$column.'`;');
			} catch( Exception $e) {

			}
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
			array('type', null, InputOption::VALUE_REQUIRED, 'Type of DB command.', null),
		);
	}

}
