<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->index();
			$table->integer('billing_address_id');
			$table->integer('shipping_address_id');
			$table->string('status', 35);
			$table->double('sum_sub_total');
			$table->double('discount');
			$table->double('tax');
			$table->double('sum_tax');
			$table->double('sum_amount');
			$table->text('note')->nullable();
			$table->integer('created_by')->unsigned()->default(0)->index();
			$table->integer('updated_by')->unsigned()->default(0)->index();
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
		Schema::drop('orders');
	}

}
