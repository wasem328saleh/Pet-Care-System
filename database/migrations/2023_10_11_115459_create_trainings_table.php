<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrainingsTable extends Migration {

	public function up()
	{
		Schema::create('trainings', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->bigInteger('order_id')->unsigned();
			$table->text('text');
			$table->text('image_url')->nullable();
			$table->text('video_url')->nullable();
			$table->text('youtube_url')->nullable();
			$table->boolean('is_active')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('trainings');
	}
}
