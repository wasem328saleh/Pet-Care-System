<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('orders_adoption', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders_product', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('orders_training', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('adoptions', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders_adoption')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders_product')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('trainings', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders_training')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('orders_adoption', function(Blueprint $table) {
			$table->dropForeign('orders_adoption_user_id_foreign');
		});
		Schema::table('orders_product', function(Blueprint $table) {
			$table->dropForeign('orders_product_user_id_foreign');
		});
		Schema::table('orders_training', function(Blueprint $table) {
			$table->dropForeign('orders_training_user_id_foreign');
		});
		Schema::table('adoptions', function(Blueprint $table) {
			$table->dropForeign('adoptions_order_id_foreign');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->dropForeign('products_order_id_foreign');
		});
		Schema::table('trainings', function(Blueprint $table) {
			$table->dropForeign('trainings_order_id_foreign');
		});
	}
}