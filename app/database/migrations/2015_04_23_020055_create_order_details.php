<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->unsigned()->default(0)->index();
			$table->integer('product_id')->unsigned()->index();
			$table->string('svg_file',250);
			$table->double('sizeh');
			$table->double('sizew');
			$table->double('sell_price');
			$table->integer('quantity');
			$table->double('sum_sub_total');
			$table->double('discount');
			$table->double('tax');
			$table->double('sum_tax');
			$table->double('sum_amount');
			$table->text('option');
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
		Schema::drop('order_details');
	}

}
