<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLayout extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('layouts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 150);
			$table->float('wall_size_h')->default(0);
			$table->float('wall_size_w')->default(0);
			$table->float('current_zoom')->default(1);
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
		Schema::drop('layouts');
	}

}
