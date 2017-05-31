<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShapeLayoutDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shape_layout_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->double('width')->default(0);
			$table->double('height')->default(0);
			$table->double('coor_x')->default(0);
			$table->double('coor_y')->default(0);
			$table->integer('rotate')->default(0);
			$table->text('points')->nullable();
			$table->text('transform')->nullable();
			$table->string('shape_type', 100);
			$table->integer('shape_layout_id')->default(0)->index();
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
		Schema::drop('shape_layout_details');
	}

}
