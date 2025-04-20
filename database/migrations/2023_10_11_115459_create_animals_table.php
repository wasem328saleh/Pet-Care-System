<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnimalsTable extends Migration {

	public function up()
	{
		Schema::create('animals', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->text('image_url');
			$table->text('description');
			$table->string('type');
		});
	}

	public function down()
	{
		Schema::drop('animals');
	}
}
