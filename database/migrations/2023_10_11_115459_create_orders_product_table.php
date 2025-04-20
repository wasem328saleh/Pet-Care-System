<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersProductTable extends Migration {

	public function up()
	{
		Schema::create('orders_product', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('user_id')->unsigned();
			$table->boolean('is_active')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('orders_product');
	}
}
