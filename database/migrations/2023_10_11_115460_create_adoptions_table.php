<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdoptionsTable extends Migration {

	public function up()
	{
		Schema::create('adoptions', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('order_id')->unsigned();
			$table->string('type_animal');
			$table->text('image_url');
			$table->text('conects_info');
			$table->text('description');
			$table->text('Reason_for_adoption');
			$table->boolean('is_active')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('adoptions');
	}
}
