<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShapeLayouts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shape_layouts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 150);
			$table->double('wall_size_h')->default(0);
			$table->double('wall_size_w')->default(0);
			$table->double('current_zoom')->default(1);
			$table->string('svg_file', 200)->nullable();
			$table->boolean('active')->default(1);
			$table->integer('created_by')->default(0);
			$table->integer('updated_by')->default(0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shape_layouts');
	}

}
