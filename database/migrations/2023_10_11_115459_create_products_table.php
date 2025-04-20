<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('order_id')->unsigned();
			$table->string('full_Name');
			$table->text('image_url');
			$table->string('price');
			$table->string('type');
			$table->text('conects_info');
			$table->text('description');
			$table->boolean('is_active')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}
